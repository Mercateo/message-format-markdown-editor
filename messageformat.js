(function(mod) {
  if (typeof exports == "object" && typeof module == "object") // CommonJS
    mod(require("../../lib/codemirror"));
  else if (typeof define == "function" && define.amd) // AMD
    define(["../../lib/codemirror"], mod);
  else // Plain browser env
    mod(CodeMirror);
})(function(CodeMirror) {
"use strict";

CodeMirror.defineMode("messageformat.js", function(config, parserConfig) {

  var VARIABLE = 'def',
      ATOM = 'atom',
      BRACKET = 'bracket',
      ERROR = 'error',
      ERROR2 = 'invalidchar',
      KEYWORD = 'keyword',
      BOLD = 'strong',
      ITALICS = 'em',
      LINK = 'link';

  var LITERAL = 1, 
      CODE_BLOCK = 2,
      ILLEGAL_BLOCK = 3;

  function globals(state){
    var s = ""
    if (state.bold)
      s += " " + BOLD;
    if (state.italics)
      s += " " + ITALICS;
    if (state.link)
      s += " " + LINK;
    if (state.href)
      s += " " + ITALICS;
    return s;
  }

  var identifierRegexp = /^[0-9a-zA-Z$_][^ \t\n\r,.+={}]*/;

  function literalModeToken(stream, state) {
    // Only highlight the # as a variable within a block
    if(stream.eat('#')) {
      return state.stack.length ? VARIABLE : globals(state);
    }
    // Escaped special characters
    if(stream.match(/^\\[{}#\\]/)) {
      return ATOM;
    }
    // Unicode
    if(stream.match(/^\\u([0-9a-fA-F]{0,4})/)) {
      return unicodeMatch[1].length == 4 ? ATOM : ERROR2;
    }
    if(stream.eat("[")){
      state.link = true;
      return BRACKET;
    }
    if (stream.eat("]")){
      if (stream.peek() == '('){
        stream.eat("(");
        state.link = false;
        state.href = true;
        return BRACKET;
      }
      else{
        state.stack.push({type: ILLEGAL_BLOCK})        
        return ERROR;
      }
    }
    if (stream.eat(")")){
      if (state.href){
        state.href = false;
        return BRACKET;
      }
      else 
        return globals(state);
    }
    if(stream.eat("*")) {
      if (stream.peek() == '*'){
        stream.eat("*")
        state.bold = !state.bold
      }
      else {
        state.italics = !state.italics
      }
      return BRACKET;
    }
    // Entering a code block
    if(stream.eat("{")) {
      state.stack.push({type: CODE_BLOCK});
      return BRACKET;
    }
    // Exiting a code block, error if we weren't in one to begin with
    if(stream.eat("}")) {
      if(state.stack.length) {
        state.stack.pop();
        return BRACKET;
      } 
      else
        return ERROR;
    }
    

    var inString = true;
    while(inString) {
      var spaceEaten = stream.eat(" ");
      inString = stream.eatWhile(/[^ \t\\{\*\[\]\)}#]+/);
      if(!inString && spaceEaten) {
        stream.backUp(1);
      }
    }
    if(stream.current() !== "") 
      return globals(state);
    stream.next();
    return ERROR;
  }

  function illegalBlockToken(stream, state) {
    if(stream.eat("{")) {
      state.stack.push({type: ILLEGAL_BLOCK});
      return [ERROR,BRACKET].join(" ");
    }
    if(stream.eat("}")) {
      state.stack.pop();
      return [ERROR,BRACKET].join(" ");
    }
    stream.eatWhile(/[^{}]/);
    return ERROR;
  }

  function codeBlock(stream, state) {
    // Get rid of white space
    if(stream.eatSpace()) {
      return null;
    }

    // This holds this block's config
    var currentState = state.stack[state.stack.length - 1];

    // Determining variable name
    if(!currentState.varName) {
      var varNameMatch = stream.match(identifierRegexp);
      if(varNameMatch) {
        currentState.varName = varNameMatch[0];
        return VARIABLE;
      }
    } 
    // Determining block type
    else if(!currentState.elementFormat) {
      if(!currentState.expectingElementFormat) {
        if(stream.eat(",")) {
          currentState.expectingElementFormat = true;
          return BRACKET;
        }
        else if(stream.eat("}")) {
          state.stack.pop();
          return BRACKET;
        }
      } 
      else {
        var elementFormatMatch = stream.match(identifierRegexp);
        if(elementFormatMatch) {
          currentState.elementFormat = elementFormatMatch[0];
          delete currentState.expectingElementFormat;
          return KEYWORD;
        }
      }
    } 
    // Reading next match case, key and block
    else if(currentState.elementFormat.match(/select/)) {
      var parsedSelectToken = choicesTokenizer(identifierRegexp,stream,state);
      if(parsedSelectToken !== undefined) 
        return parsedSelectToken;
    } 
    else if(currentState.elementFormat.match(/plural|selectordinal/)) {
      var parsedPluralToken = choicesTokenizer(/^=[0-9]+|zero|one|few|many|other/,stream, state);
      if(parsedPluralToken !== undefined) 
        return parsedPluralToken;
    } 
    // ???
    else {
      var parsedArgumentToken = argumentToken(stream, state);
      if(parsedArgumentToken !== undefined) 
        return parsedArgumentToken;
    }
    if(stream.eat("{")) {
      state.stack.push({type: ILLEGAL_BLOCK});
      return [ERROR,BRACKET].join(" ");
    }
    if(stream.eat("}")) {
      state.stack.pop();
      return [ERROR,BRACKET].join(" ");
    }
    stream.next();
    return ERROR;
  }

  function choicesTokenizer(keyRegExp, stream, state) {
    var currentState = state.stack[state.stack.length - 1];
    if(!currentState.isAcceptingChoices) {
      if(stream.eat(",")) {
        currentState.isAcceptingChoices = true;
        currentState.currentKey = null;
        currentState.keys = [];
        return null;
      }
    }
    else {
      if(currentState.currentKey === null) {
        if(stream.match(keyRegExp)) {
          currentState.currentKey = stream.current();
          return KEYWORD;
        }
        if(currentState.keys.length > 0 && stream.eat("}")) {
          state.stack.pop();
          return BRACKET;
        }
      } 
      else {
        if(stream.eat("{")) {
          currentState.keys.push(currentState.currentKey);
          currentState.currentKey = null;
          state.stack.push({type: LITERAL});
          return BRACKET;
        }
      }
    }
  }

  

  function argumentToken(stream, state) {
    var currentState = state.stack[state.stack.length - 1];
    if(!currentState.isAcceptingArgument) {
      if(stream.eat(",")) {
        currentState.isAcceptingArgument = true;
        return null;
      }
      if(stream.eat("}")) {
        state.stack.pop();
        return BRACKET;
      }
    } else {
      if(stream.match(identifierRegexp)) {
        currentState.isAcceptingArgument = false;
        return KEYWORD;
      }
    }
  }

  return {
    startState: function() {
      return {
        stack: [],
        bold: false,
        italics: false
      };
    },

    token: function(stream, state) {
      var stack = state.stack;
      var currentState = stack.length > 0 ? stack[stack.length-1].type : LITERAL;
      if(currentState === LITERAL) {
        return literalModeToken(stream, state);
      } 
      else if(currentState === CODE_BLOCK) {
        return codeBlock(stream, state);
      } 
      else if(currentState === ILLEGAL_BLOCK) {
        return illegalBlockToken(stream, state);
      }
    },
    fold: "brace"
  };

});

});
