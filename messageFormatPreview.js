// Generates samples for a MessageFormat string. The arguments are the string, the locale, which
// will be used to generate all the cases, an optional comment which can contain sample values for
// variables, for singular/plural, and options, which can contain rules for default variable 
// values for both simple variables (simpleRules) and select variables (selectRules) [defaults 
// available] and the method of generating all the combinations (method: "smart" | "exponential", 
// default "smart").
// Returns an array of strings
var generateSamples = function(locale, mf, comment, options){
  // Default options
  comment = comment || '';
  options = options || {};
  options.simpleRules = options.simpleRules || [
    [/URL|LINK/i, function(string){ return '#'}],
    [/NUM/i, function(string){ return Math.floor(Math.random()*20)}],
    [/NAME/i, function(string){ return 'Mustermann'}]
  ];
  options.selectRules = options.selectRules || [
    [/GENDER/i, function(string){ return 'male|female'}]
  ];

  // Returns the default value for a variable. The user can supply their own rules in an array
  // containing [regex, callback] pairs, where if the regex is matched, the callback is used to 
  // generate the default. If the user hasn't supplied their own rules, there are some 
  // predefined rules that will be used.
  function defaultValueFor(string, rules){
    for (var i = 0; i < rules.length; i++){
      if (rules[i][0].test(string)){
        return rules[i][1](string);
      }
    }
    return string;
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

  // Finds all the simple variables (variables that aren't appearing in a control structure)
  var simpleVars = mf.match(/\{\s*([^\s,\}]+)\s*\}/g);
  // Regex ~ { WORD }                                  stripping bracket and whitespace
  simpleVars = (simpleVars) ? simpleVars.map(function(x){return x.match(/[^\{,\s\}]+/)[0];}).unique() : [];

  // This loop generates the one combination of variables which are used in none of the constructs
  // ('simple variables')
  var combinations = [{}];
  for (var i = 0; i < simpleVars.length; i++){
    // If the comment specified a sample value, use that, otherwise generate it using this function
    var cases = (examples[simpleVars[i]] || defaultValueFor(simpleVars[i],options.simpleRules)).split('|');
    addCasesToCombinations(simpleVars[i],cases)
  }


  // Finds all the variables that have been used within a 'plural' control structure, along with
  // the constant cases used within that structure, i.e. [['NUM_ITEMS',0],...]
  var matches = mf.match(/\{\s*([^\s,]+)\s*,\s*plural\s*|=[0-9]+/g);
  // Matches both the variable in { VAR, plural, ... } and constant cases like '=0'
  var pluralVars = [];
  if (matches !== null){
    for (var i = 0; i < matches.length; i++){
      // If constant, add to last variable's array
      if (matches[i].match(/=[0-9]+/))
        pluralVars[pluralVars.length-1].push(parseInt(matches[i].substring(1)));
      // Otherwise add new array to the array
      else
        pluralVars.push([matches[i].match(/[^\{ ,]+/)[0]]);
    }
    // Remove duplicate values but keep potentially distinct constants
    pluralVars = pluralVars.sort();
    var n = [pluralVars[0]]
    for (var i = 1; i < pluralVars.length; i++){
      // Same variable
      if (pluralVars[i-1][0] === pluralVars[i][0]){
        // Add constants to the other variable
        n[n.length-1] = n[n.length-1].concat(pluralVars[i].slice(1)).unique();
      }
      else
        n.push(pluralVars[i]);
    }
    pluralVars = n;
  }

  // This loop extends and replicates the single combination from above such that for every 
  // 'plural variable' there is at least one combination in which it takes each of the values
  // necessary for the current local (one+other in English, one+few+many+other in Polish, etc.)
  // For every plural variable ...
  for (var i = 0; i < pluralVars.length; i++){
    // ... generate the necessary cases by combining the specified constants and this language.
    // 'numbers' is an array of constants that are defined in the control structure (like '=0') 
    // and which can therefore not be randomly chosen as a representative of one of the groups
    // (one/few/many/...)
    var numbers = pluralVars[i].slice(1);
    // Everything mod 20, so we can use 20 as 0 in a continuous plural interval
    var groups;
    switch (locale) {
      case 'cs':; case 'sk':; case 'pl':
        groups = [[1,1],[2,4],[5,20]]; break;
      case 'de':; case 'en':; case 'es':; case 'it':; case 'nl': 
        groups = [[1,1],[2,20]]; break;
      case 'fr': 
        groups = [[0,1],[2,19]]; break;
      case 'hu': 
        groups = [[1,20]]; break;
    }

    // Take the constants out of the intervals
    for (var j = 0; j < groups.length; j++){
      var reducedGroup = [];
      for (var k = groups[j][0]; k <= groups[j][1]; k++){
        // Only add a number to the reduced group if it's not one of the constants      
        if (!!$.inArray(k % 20,numbers))
          reducedGroup.push(k % 20);
      }
      // If the remaining group is empty it means all its cases are already covered, if not, pick a
      // random number and add it to the list as this group's representative
      if (reducedGroup.length > 0)
        numbers.push(reducedGroup[Math.floor(Math.random()*reducedGroup.length)]);
    }

    addCasesToCombinations(pluralVars[i][0],numbers);
  }


  // Finds all the variables that have been used within a 'select' control structure
  var selectVars = mf.match(/\{\s*([^\s,]+)\s*,\s*select/g);
  // Matches the variable in { VAR, select, ...}
  selectVars = (selectVars) ? selectVars.map(function(x){return x.match(/[^\{,\s\}]+/)[0];}) : [];

  // This loop extends the combinations with values from the select variables, such that each of 
  // the specified value occurs in at least one combination
  for (var i = 0; i < selectVars.length; i++){
    // The cases are either from the example, or from the rules
    var cases = (examples[selectVars[i]] || defaultValueFor(selectVars[i],options.selectRules))
      .split('|');
    addCasesToCombinations(selectVars[i],cases);
  }

  // From each combination, generate one sample
  var samples = [];
  var compiled = (new MessageFormat(locale)).compile(mf);
  for (var i = 0; i < combinations.length; i++)
    samples.push(compiled(combinations[i]));

  return samples;
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