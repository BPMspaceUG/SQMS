var module = angular.module('SQMSApp', ['ngSanitize', 'xeditable', 'ui.bootstrap', 'ui.tinymce', 'ui.select'])

// Needed for inline editing
module.run(function(editableOptions) {
  editableOptions.theme = 'bs3'; // needed for inline editing
});

module.directive('stringToNumber', function() {
  return {
    require: 'ngModel',
    link: function(scope, element, attrs, ngModel) {
      ngModel.$parsers.push(function(value) {
        return '' + value;
      });
      ngModel.$formatters.push(function(value) {
        return parseFloat(value);
      });
    }
  };
});

module.filter('propsFilter', function() {
  return function(items, props) {
    var out = [];

    if (angular.isArray(items)) {
      var keys = Object.keys(props);

      items.forEach(function(item) {
        var itemMatches = false;

        for (var i = 0; i < keys.length; i++) {
          var prop = keys[i];
          var text = props[prop].toLowerCase();
          if (item[prop].toString().toLowerCase().indexOf(text) !== -1) {
            itemMatches = true;
            break;
          }
        }

        if (itemMatches) {
          out.push(item);
        }
      });
    } else {
      // Let the output be the input untouched
      out = items;
    }

    return out;
  };
  
});
// Filter which filters array after Models selected in the <select> above
module.filter('statefilter', function(){
  return function(items, stateModel) {
    var filtered = [];

    if (!stateModel){
      // return filtered then nothing is shown until a state is chosen.
      return items;
    }
    for(var i = 0; i< items.length; i++){
      var item = items[i];
      if ((item.state.name) == (stateModel.name)){
        filtered.push(item);
      }
    }
    return filtered;
  };
});


//Used to select and copy the Json result. TODO: Transform to angular. 
// TODO: Add something like $window.alert if there are unfinished or wrong Questions that where transformed to Json.
function selectJson(nfield){
  var e = document.getElementsByTagName('FIELDSET')[nfield]; // Watch out: If you create a second <fieldset> tag you have to increment [0].
  var r = document.createRange();
  r.selectNodeContents(e);
  var s = window.getSelection();
  s.removeAllRanges();
  s.addRange(r);
  document.execCommand("copy");  
} 

