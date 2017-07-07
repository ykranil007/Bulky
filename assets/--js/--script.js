$( function() {
    $.widget( "custom.catcomplete", $.ui.autocomplete, {
      _create: function() {
        this._super();
        this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
      },
      _renderMenu: function( ul, items ) {
        var that = this,
          currentCategory = "";
        $.each( items, function( index, item ) {
          var li;
          if ( item.category != currentCategory ) {
            ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
            currentCategory = item.category;
          }
          li = that._renderItemData( ul, item );
          if ( item.category ) {
            li.attr( "aria-label", item.category + " : " + item.label );
          }
          
        });
      }
    });
    
    $( "#keywords" ).catcomplete({
      delay: 10,
      source: function(req, response) {
                            $.ajax({    
                            url      : "index.php/Home/get_keywords_list",
                            dataType : "json",
                            data	 : {keywords: req.term},
                            success  : function(json)
                            {
                                response($.grep(json, function(item){return item;})); 
                            },
                        });
                     },
                    minLength:2,
                    select : function(event, ui ) {create_url(ui.item.value);}
    });
    
    $("#keywords").keyup(function(event){
        if(event.keyCode == 13 && $(this).val().length >0){
            create_url($(this).val());
        }
    });
    
    $('#btn_keywords').click(function(){create_url($('#keywords').val());});
    function replacer(string) {
        return string.trim().replace(/["~!@#$%^&*\(\)`{}\[\]\|\\:;'<>,.\/?"\- \t\r\n]+/g, '-').toLowerCase();
    }
    function create_url(url_value)
    {
        if(url_value!='' && url_value!='undefined')
        {
            window.location.href = 'products?keywords='+replacer(url_value)+'&userQuery=true';
        }
        else
            return false;
    }
  });