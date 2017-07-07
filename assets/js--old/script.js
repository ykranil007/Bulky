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
                            url      : "Home/get_keywords_list",
                            dataType : "json",
                            data	 : {keywords: req.term},
                            success  : function(json)
                            {
                                response($.grep(json, function(item){return item;})); 
                            },
                        });
                     },
                    minLength:3,
                    select : function(event, ui ) {create_url(ui.item.url);}
    });
    $("#keywords").keyup(function(event){if(event.keyCode == 13 && $.trim($(this).val()).length >0){create_url($(this).val().replace(/&/,'and').trim());}});
    $('#btn_keywords').click(function(){create_url($('#keywords').val());});
    function replacer(string) 
    {
        return string.trim().replace(/["~!@#$%^&*'\(\)`{}\[\]\|\\:;'<>,.\/?"\t\r\n]+/g, '').toLowerCase();
    }
    function create_url(url_value)
    {
        var url_value = replacer(url_value);
        if(url_value!='' && url_value!='undefined' && (/^[a-zA-Z0-9- ]*$/.test(url_value) == true))
        {
            //alert(url_value);
            window.location.href = 'products?keywords='+replacer(url_value)+'&userQuery=true';
        }
        else
            return false;
    }
    
    $("img.lazy").lazyload({event : "sporty"});
    $(window).bind("load", function() {setTimeout(function() { $("img.lazy").trigger("sporty") }, 1500);});   
    
    
	//========for download app.=========
$('#download_app').on('click',function(){
	$.fancybox([
            { href : '#download_app_btn',
				helpers   : { 
   				overlay : {closeClick: true} // prevents closing when clicking OUTSIDE fancybox 
  				}
			}
        ]);
	});
    
$('#send_app_link').on('click',function(){
	var form_status  = true;
	var mobile	= $('#mobile_no').val();
	//var numericReg = /^\d*[0-9](|.\d*[0-9]|,\d*[0-9])?$/; 
	if(jQuery.trim(mobile) == '' || jQuery.trim(mobile) == 0)
	{
		form_status  = false;
		$('#mobile_no').closest('div').addClass(' has-error');
		$('#app_mobile_error').text('Mobile number can\'t be empty.').show();
	}
	else if(mobile.length != 10) 
	{
		form_status  = false;
		$('#mobile_no').closest('div').addClass(' has-error');
        $('#app_mobile_error').text('Enter valid 10 digits mobile no.').show();
    }
	else if(!$.isNumeric(mobile))
	{
		form_status  = false;
		$('#mobile_no').closest('div').addClass(' has-error');
		$('#app_mobile_error').text('Enter only 10 digits numeric value.').show();
	}
	if(form_status == true)
	{
		if($('#mobile_no').closest('div').hasClass("has-error"))
      	{
         	$('#mobile_no').closest('div').removeClass('has-error');
	  	}
		$('#app_mobile_error').hide();
	$.ajax({
			url:'send-applink',
			type:'post',
			data:{mob_no:mobile},
			dataType:'json',
			success: function(json)
			{
				$('#send_success').show();
				$('#send_success').text('App download link send to '+ json['mobileNo']);
				$.fancybox.close();
				$('#mobile_no').val('');
				$('#send_success').text('');
			},
			error: function(data)
			{
				console.log(data);
			}
			});
	}
	});
//======end download app==============	
  });