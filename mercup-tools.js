/** 
 * Converts a string written in 'Mercup' to a MessageFormat string, i.e. parses our custom markdown
 * @param {string} string - The Mercup string
 * @param {object} options - Option object that is passed to the marked.js parser
 * @param {function} callback - Callback that is passed to the marked.js parser
 * @returns {string} - String containing HTML and MessageFormat elements
 */
function toMessageFormat(string,options,callback){
  // Replacing newlines by br's if they are the third or more newline in sequence. Because markdown
  // will collapse any amount of newlines into a single line break, this allows us to use (n+1) newlines
  // to get n br's in the output. Special care for single newlines before opening MF Syntax
  string = string.replace(/\n{2,}/g,function(ns){ return '\n\n'+Array(ns.length-1).join("<br>\n"); }).replace(/\n\{/g," {");
  // Escaping MF special characters
  // { => \{, \{ => \\\{, \\ => \\\\, ...
  string = string.replace(/([#\\])/g,'\\$1');
  // Custom marked renderer to suppress enclosing <p>...</p>
  options = options || {};
  if (!!options.renderer){
    console.log("WARNING: A custom renderer might produce unwanted whitespace or invalid MessageFormat elements");
  }
  else{
    options.renderer = new marked.Renderer();
    options.renderer.paragraph = function(string){ 
      return string.replace(/\s+/g," ")+'<br>\n'; 
    };
  }
  // We don't want Github flavour because we want to ignore line breaks in paragraphs
  if (options.gfm){
    console.log("WARNING: GitHub Flavour will be disabled to deal with newlines correctly")
  }
  options.gfm = false;
  // We also have to remove a potential trailing <br>
  return marked(string,options,callback).split(/<br>\s*$/)[0];
}

/**
 * Generates combinations such that every variable is bound to every possible case at least once
 * @param {string} locale - The two-letter locale is used to cover all plural cases
 * @param {string} mf - The MessageFormat string for which the combinations should be generated
 * @param {string} comment [''] - The comment containing variable samples of the form 'VARIABLE: [sample]'
 * @param {string} options.method ['smart'] - The method to produce the combinations ('smart' | 'exponential')
 * @param {Object[]} options.defaultRules [...] - Rules used to produce default variable values based on variable names
 * @param {RegExp} options.defaultRules[].regexp - A RegExp that the variable name is tested against
 * @param {function(string) => string} options.defaultRules[].generator - The function used to produce the value
 * @returns {Object[]} - List of combinations that can be used to evaluate this MF-string
 */
function samplesForString(locale, mf, comment, options){
  // Default options
  comment = comment || '';
  options = options || {};
  options.defaultRules = options.defaultRules || [
    {regexp: /URL|LINK/i, generator: function(string){ return '#';}},
    {regexp: /NUM/i,      generator: function(string){ return Math.floor(Math.random()*20);}},
    {regexp: /NAME/i,     generator: function(string){ return 'Mustermann';}},
    {regexp: /GENDER/i,   generator: function(string){ return 'male|female';}}
  ];
  var addCasesToCombinations = (options.method === 'exponential') ? exponentialCombine : smartCombine

  function defaultValueFor(variable){
    for (var rule in options.defaultRules.length){
      if (rule.regexp.test(variable)){
        return rule.generator(variable);
      }
    }
    // If there's no match, the variable's name will be used
    return variable.toUpperCase();
  }

  function smartCombine(variable, cases) {
    cases = cases.shuffled()
    // For all cases or existing combinations, whatever is biggest
    for (var j = 0; j < Math.max(cases.length,combinations.length); j++){
      // (If we've run out of combinations, we duplicate a random one)
      if (j >= combinations.length)
        combinations.push(
          JSON.parse(JSON.stringify(
            combinations[Math.floor(Math.random()*combinations.length)]
          ))
        );
      // ... add one of the cases to the combinations, wrapping around if there are more
      // combinations than cases
      combinations[j][variable] = cases[j % cases.length];
    }
  }
  function exponentialCombine(variable,cases) {
    var n = combinations.map(function(combination){
      return cases.map(function(_case){
        var c = JSON.parse(JSON.stringify(combination))
        c[variable] = _case
        return c;
      });
    });
    return [].concat.apply([], n);
  }

  // Matches and extracts 'VAR_NAME: some comment [EXAMPLE]'s from comment
  var examples = {};
  (comment.match(/^\w+:.*?\[.*?\]/gm) || []).forEach(function addMatchToExamples(match){
    examples[match.match(/\w+/)[0]] = match.match(/\[.*?\]/)[0].slice(1,-1);
  });

  var combinations = [{}];

  var rawVariables = [];
  
  findVariables((new MessageFormat('de')).parse(mf).program);

  function findVariables(ast){
    switch (ast.type) {
      case 'messageFormatPattern':; case 'messageFormatPatternRight':
        for (var i = 0; i < ast.statements.length; i++)
          findVariables(ast.statements[i]);
        break;
      case 'messageFormatElement':
        // Simple var
        if (ast.output)
          rawVariables.push({
            type: 'simple',
            name: ast.argumentIndex,
            cases: []
          });
        // Plural or select
        else {
          rawVariables.push({
            name:   ast.argumentIndex,
            type:   (ast.elementFormat.key === 'plural') ? "number" : "string",
            cases:  ast.elementFormat.val.pluralForms.map(function findMoreAndReturnKey(e,i){ 
                      findVariables(e.val); 
                      return e.key; 
                    })
          });
        }
      break;
    }
  }


  if (rawVariables.length === 0)
    return combinations;

  rawVariables = rawVariables.sort(function lexNameType(v1,v2){
    return (v1.name === v2.name) ? v1.type < v2.type : v1.name < v2.name;
  });
  
  // Iterating through sorted variables, merging duplicate variables' cases
  var variables = [rawVariables[0]];
  for (var i = 1; i < rawVariables.length; i++){
    var existing = variables[variables.length-1]
    var next = rawVariables[i];
    // Duplicate variable, merge cases
    if (existing.name === next.name){
      // Different types, error
      if (existing.type !== next.type && existing.type !== 'simple' && next.type !== 'simple')
        throw new Error(a.name+" used as both a string and a number");
      existing.cases = existing.cases.append(next.cases)
      if (existing.type === 'simple')
        existing.type = next.type;
    }
    else
      variables.push(next);
  }

  variables.forEach(function(variable){
    if (variable.type === 'number') {
      variable.cases = variable.cases.filter(isNumeric).unique();
      // Add a random number from every predefined case
      // (Everything mod 20, so we can use 20 as 0 as a continuous plural interval)
      var intervals;
      switch (locale) {
        case 'cs':; case 'sk':; case 'pl':
          intervals = [[1,1],[2,4],[5,20]]; break;
        case 'de':; case 'en':; case 'es':; case 'it':; case 'nl': 
          intervals = [[1,1],[2,20]]; break;
        case 'fr': 
          intervals = [[0,1],[2,19]]; break;
        case 'hu': 
          intervals = [[1,20]]; break;
      }
      for (var i = 0; i < intervals.length; i++){
        var reducedInterval = [];
        for (var j = intervals[i][0]; j <= intervals[i][1]; j++){
          // Only add a number to the reduced interval if it's not a case already   
          if (!!$.inArray(j % 20,variable.cases))
            reducedInterval.push(j % 20);
        }
        // If the remaining interval is empty it means all its cases are already covered, if not, pick a
        // random number and add it to the list as this interval's representative
        if (reducedInterval.length > 0)
          variable.cases.push(reducedInterval[Math.floor(Math.random()*reducedInterval.length)]);
      }
    }
    else /* type == 'string' || 'simple' */ {
      if (examples[variable.name])
        variable.cases = examples[variable.name].split('|');
      if (variable.cases.length === 0)
        variable.cases = defaultValueFor(variable.name).split('|');
    }
    addCasesToCombinations(variable.name,variable.cases);
  });

  return combinations;
}

// Source: http://stackoverflow.com/questions/2450954/how-to-randomize-shuffle-a-javascript-array
Array.prototype.shuffled = function() {
  var currentIndex = this.length, temporaryValue, randomIndex
  while (0 !== currentIndex) {
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex -= 1;
    temporaryValue = this[currentIndex];
    this[currentIndex] = this[randomIndex];
    this[randomIndex] = temporaryValue;
  }
  return this;
};

// Source: http://stackoverflow.com/questions/1960473/unique-values-in-an-array
Array.prototype.unique = function() {
  return this.filter(function(value, index, self){ return self.indexOf(value) === index; });
};

// Source: http://stackoverflow.com/questions/9716468/is-there-any-function-like-isnumeric-in-javascript-to-validate-numbers
var isNumeric = function(num){
  return Number(parseFloat(num)) == num;
}