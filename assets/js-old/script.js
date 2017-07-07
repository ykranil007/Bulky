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
    $("#keywords").keyup(function(event){if(event.keyCode == 13 && $.trim($(this).val()).length >=3){create_url($(this).val().trim());}});
    $('#btn_keywords').click(function(){
        if($.trim($('#keywords').val()).length >=3)
        create_url($('#keywords').val());
    });
    function replacer(string) 
    {
        return string.trim().replace(/["~!@#$%^&*'\(\)`{}\[\]\|\\:;'<>,.\/?"\t\r\n]+/g, '');
    }
    function create_url(url_value)
    {
        if(url_value!='' /*&& url_value!='undefined' && (/^[a-zA-Z0-9- ]*$/.test(url_value) == true)*/)
		{
            window.location.href = 'products?keywords='+encodeURIComponent(url_value)+'&userQuery=true';
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
  				},
                'afterClose': function() {
                    
                    $('#mobile_no,#app_mobile_error').val('');
                    $('.has_error').removeClass('has_error');
                },
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
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};

//========for register franchise.=========
$('#reg_franchise,#mob_reg_franchise').on('click',function(){
	$.fancybox([
            { href : '#franchise_reg_btn',
				helpers   : { 
   				overlay : {closeClick: true} // prevents closing when clicking OUTSIDE fancybox 
  				},
                'afterClose': function() {                    
                    $('#reg_name,#reg_email,#reg_mobile').val('');
                    $('.has-error').removeClass('has-error');
                },
			}
        ]);
	});
	$('#submit-franchise-btn').click(function(){
		var form_status  = true;
		$('#required').each(function(index, element) {
         if(trim($(this).val()) == '' || trim($(this).val()) == 0)
		 {
			 $(this).closest('div').addClass('has-error');
			 form_status = false;
		 }  
		 else if($(this).closest('div').hasClass("has-error"))
		 {
			 $(this).closest('div').removelass('haa-error');
			 $form_status = true;
		 }
        });
		if(!isValidEmailAddress($('#reg_email').val()))
		{
			$('#reg_email').closest('div').addClass('has-error');
			form_status = false;
		}
		else if(!jQuery.trim($('#reg_mobile').val()).match(/^[789]\d{9}$/))
		{
			$('#reg_mobile').closest('div').addClass('has-error');
			form_status = false;
		}
		
	if(form_status == true){
	 var Name		= $("#reg_name").val();
	 var Email 		= $('#reg_email').val();
	 var Mobile 	= $('#reg_mobile').val();
	  $.ajax({
				url:'save-franchise-data',
				type:'POST',
				dataType:"json",
				data:{name:Name,email:Email,mobile:Mobile},
				success: function(json)
				{ 
				if(json.success == 1)
				{	
				$('#success_msg_franchise').text('Thanks! Your data saved successfully');
					 setTimeout( function() {$.fancybox.close(); },3000);
					 $("#reg_name").val('');
					 $('#reg_email').val('');
					 $('#reg_mobile').val('');
					 
				}	
				},
				error: function(data)
				{
					console.log(data);
				}
			});
	}
	});
//====end franchise=========
  });