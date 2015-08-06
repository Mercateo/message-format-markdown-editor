// Generates samples for a MessageFormat string. The arguments are the string, the locale, which
// will be used to generate all the cases, an optional comment which can contain sample values for
// variables, for singular/plural, and options, which can contain rules for default variable 
// values for both simple variables (simpleRules) and select variables (selectRules) [defaults 
// available] and the method of generating all the combinations (method: "smart" | "exponential", 
// default "smart").
// Returns an array of strings
var samplesFor = function(locale, mf, comment, options){
  // Default options
  comment = comment || '';
  options = options || {};
  options.simpleRules = options.simpleRules || [
    [/URL|LINK/i, function(string){ return '#';}],
    [/NUM/i, function(string){ return Math.floor(Math.random()*20);}],
    [/NAME/i, function(string){ return 'Mustermann';}]
  ];
  options.selectRules = options.selectRules || [
    [/GENDER/i, function(string){ return 'male|female';}]
  ];

  // Returns the default value for a variable. The user can supply their own rules in an array
  // containing [regex, callback] pairs, where if the regex is matched, the callback is used to 
  // generate the default. If the user hasn't supplied their own rules, there are some 
  // predefined rules that will be used.
  function defaultValueFor(variable, rules){
    for (var i = 0; i < rules.length; i++){
      if (rules[i][0].test(variable)){
        return rules[i][1](variable);
      }
    }
    // If there's no match, the variable's name will be used
    return variable;
  }

  options.method =  options.method || 'smart';

  // Determining algorithm for generating combinations
  var smartAlgo = function(variable, cases) {
    cases = cases.shuffled()
    // For all cases or existing combinations, whatever is biggest, ...
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
  var exponentialAlgo = function(variable,cases) {
    // For every combination...
    var n = combinations.map(function(combination){
      // ... generate a new one for every case
      return cases.map(function(_case){
        var c = JSON.parse(JSON.stringify(combination))
        c[variable] = _case
        return c
      })
    })
    // flatten and return
    return [].concat.apply([], n);
  }
  var addCasesToCombinations = (options.method === 'exponential') ? exponentialAlgo : smartAlgo

  // Extracts examples from the comment
  // Matches and extracts (VAR_NAME): some comment [(EXAMPLE)]
  comment = (comment.match(/[A-Za-z\_\-1-9]+:[^\[]+\[[^\]]*]/g) || []).map(function (line) {
    return line.replace(/^\s*([A-Za-z\_\-1-9]+):[^\[]*\[\s*([^\]]+)\]\s*$/,'$1~|$2').split('~|');
  })
  var examples = {}
  for (var i = 0; i < comment.length; i++)
    examples[comment[i][0]] = comment[i][1];

  var combinations = [{}];

  var rawVariables = [];

  function findVariables(ast){
    switch (ast.type) {
      case 'messageFormatPattern':; case 'messageFormatPatternRight':
        for (var i = 0; i < ast.statements.length; i++)
          findVariables(ast.statements[i]);
        return;
      case 'messageFormatElement':
        // Simple var
        if (ast.output)
          rawVariables.push({
            type: 'simple',
            name:ast.argumentIndex,
            cases: []
          });
        // Plural or select
        else {
          rawVariables.push({
            name: ast.argumentIndex,
            type: (ast.elementFormat.key == 'plural') ? "number" : "string",
            cases: ast.elementFormat.val.pluralForms.map(function(e,i){ 
              findVariables(e.val); return e.key; 
            })
          })
        }
      return;
    }
  }

  findVariables((new MessageFormat('de')).parse(mf).program);

  if (rawVariables.length == 0)
    return combinations;

  // Sorting the variables to weed out duplicates
  rawVariables = rawVariables.sort(function(var1,var2){
    return (var1.name == var2.name) ? var1.type < var2.type : var1.name < var2.name;
  });
  
  // Iterating through sorted variables, merging duplicate variables' cases
  var variables = [rawVariables[0]];
  for (var i = 1; i < rawVariables.length; i++){
    var a = rawVariables[i], b = variables[variables.length-1]
    // Duplicate variable, merge cases
    if (a.name == b.name){
      // Different types, error
      if (a.type != b.type && a.type != 'simple' && b.type != 'simple')
        throw new Error(a.name+" used as both a string and a number");
      // Append cases
      b.cases = b.cases.append(a.cases)
      // If previous variable was simple, it takes on added variables type
      if (b.type == 'simple')
        b.type = a.type;
    }
    else
      variables.push(a);
  }

  variables.forEach(function(e){
    if (e.type == 'number') {
      // Filter out non-constants
      e.cases = e.cases.filter(isNumeric).unique();
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
          if (!!$.inArray(j % 20,e.cases))
            reducedInterval.push(j % 20);
        }
        // If the remaining interval is empty it means all its cases are already covered, if not, pick a
        // random number and add it to the list as this interval's representative
        if (reducedInterval.length > 0)
          e.cases.push(reducedInterval[Math.floor(Math.random()*reducedInterval.length)]);
      }
    }
    else {
      // If an example exists, replace cases by example
      if (examples[e.name])
        e.cases = examples[e.name].split('|');
      // If still no cases, use default
      if (e.cases.length == 0)
        e.cases = defaultValueFor(e.name,options.stringRules).split('|');
    }
    addCasesToCombinations(e.name,e.cases);
  });

  console.log(combinations);
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