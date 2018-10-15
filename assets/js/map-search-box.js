function setAutoComplete(inp) {

  var limitdrop = 5;

  var currentFocus;

  var delay = (function(){
    var timer = 0;
    return function(callback, ms){
      clearTimeout (timer);
      timer = setTimeout(callback, ms);
    };
  })();

  inp.focusin(function(event) {
    delay(function(){

      var a, b, val = inp.val();

      closeAllLists();

      if (!val) { return false;}
      currentFocus = -1;

      a = $('<div id=' + inp.attr('id') + 'autocomplete-list class=autocomplete-items ></div>');
      
      $(this).parent().append(a);

      $.getJSON('https://nominatim.openstreetmap.org/search',{
        format: 'json',
        q : inp.val()
      } ,function(response) {
        createAutocompleteList(a, response);
      });

      inp.parent().append(a);
    }, 500 );
  });

  //=============================================================================
  inp.keyup(function(event) {
    var x = $('#' + inp.attr('id') + 'autocomplete-list');
    if (x) x = x.find('div');
    
    if (event.keyCode == 40) { //key down
      
      currentFocus++;
      
      addActive(x);
    } else if (event.keyCode == 38) { //key up
      
      currentFocus--;
      
      addActive(x);

    } else if (event.keyCode == 13) {// key enter

      event.preventDefault();

      if (currentFocus > -1) {
        if (x) x[currentFocus].click();
      } else {
        $('#search_button').trigger( 'click');
      }
    } else if (event.keyCode != 39 && event.keyCode != 37) {
      // search location names
      delay(function(){

        var a, b, val = inp.val();

        closeAllLists();

        if (!val) { return false;}
        currentFocus = -1;

        a = $('<div id=' + inp.attr('id') + 'autocomplete-list class=autocomplete-items ></div>');
        
        $(this).parent().append(a);

        $.getJSON('https://nominatim.openstreetmap.org/search',{
          format: 'json',
          q : inp.val()
        } ,function(response) {
            createAutocompleteList(a, response);
        });

        inp.parent().append(a);
      }, 500 );
    }
  });

  //=============================================================================
  function createAutocompleteList(a, response) { 
    var b, itemadded = 0;

    for(let element of response) {

      if(itemadded == limitdrop) break;
      
      b = $('<div></div>');
      b.html(element.display_name);

      let bounds = $('<input id=bounds type=hidden>');
      bounds.val(JSON.stringify(element.boundingbox));
      b.append(bounds);

      let name = $('<input id=name type=hidden>');
      name.val(element.display_name);
      b.append(name);

      b.click(function(event) {
        inp.val($(event.target).find('#name').val());
        closeAllLists();
        
        $('#search_button').trigger( 'click', [JSON.parse($(event.target).find('#bounds').val())] );
      });

      a.append(b);
      itemadded++;
    };
  }

  //=============================================================================
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    if (x.length == 0) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }

  //=============================================================================
  function removeActive(x) {
    for(let i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }

  //=============================================================================
  function closeAllLists(element) {
    $('.autocomplete-items').each(function(i, item) {
      if(element != item && $(element).attr('id') != inp.attr('id')) {
        item.remove();
      }
    });
  }
  
  //=============================================================================
  $(document).click(function(event) {
    closeAllLists(event.target);
  });

}

//=============================================================================
//searchbox control
L.Control.SearchBox = L.Control.extend({

    options: {
        position: 'topleft',
    },

    onAdd: function(map) {

        //main div
        let conteiner = L.DomUtil.create('div');
        conteiner.id = 'searchbox-container';

        maindiv = $('<div id=searchbox class=autocomplete></div>');

        maindiv.html('<input id=search_input type=text>');

        L.DomEvent.disableClickPropagation(conteiner);

        $(conteiner).append(maindiv);
        $(conteiner).append('<button id=search_button >teste</button>');

         //set config after created
         setTimeout(function () {
          setAutoComplete($('#search_input'));

          $('#search_button').click(function(event , bounds) {

            if (bounds) {
              map.flyToBounds(
                [[Number(bounds[0]), Number(bounds[2])],
                 [Number(bounds[1]), Number(bounds[3])]]
              );
            } else {
              $.getJSON('https://nominatim.openstreetmap.org/search?format=json&q=brasil',{
                format: 'json',
                q : $('#search_input').val()
              } ,function(response) {

                if(response) {
                  
                  bounds = response[0].boundingbox;

                  map.flyToBounds(
                    [[Number(bounds[0]), Number(bounds[2])],
                     [Number(bounds[1]), Number(bounds[3])]]
                  );
                }
              });
            }
          });
      }, 1);


        return conteiner;
    },

    onRemove: function(map) {}
});

//=============================================================================
L.control.searchbox = function(opts) {
    return new L.Control.SearchBox(opts);
}