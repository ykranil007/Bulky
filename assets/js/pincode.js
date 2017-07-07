
// Pincode Availability Checking
$(document).on('click', '#btn_pincode_availability', function (){
		var val = $('#pincode_availability').val();		
			$.ajax({
				url: 'Product_details/checkPincode',
				type: 'post',
				data: { pincode: val },
				dataType: 'json',
				beforeSend: function()
				{
					$("#btn_pincode_availability").html("<img src='assets/images/load.gif' width='18' style='margin-top:4px; margin-right:4px;' align='left' /> ");			
				},
				success: function(json)
				{
					

					if(json['pincode_error']) 
					{
						window.setTimeout(function(){
							$("#btn_pincode_availability").text("Check");
							$("#pincode_error").fadeOut();						
							$("#pincode_error").fadeIn();
							$('#pincode_error').removeClass('validation_success');
							$('#pincode_error').addClass('validation_error');
							$("#pincode_error").text(json['pincode_error']);
							}, 500);						
					}
					if(json['pincode_success'])
					{
						window.setTimeout(function(){
							$("#btn_pincode_availability").text("Check");
							$("#pincode_error").fadeOut();						
							$("#pincode_error").fadeIn();
							$('#pincode_error').removeClass('validation_error');
							$('#pincode_error').addClass('validation_success');
							$("#pincode_error").text(json['pincode_success']);
							}, 500);
					}

					if(json['success'])
					{
						$('#availability_pincode_error').hide();
						$("#availability_pincode_error").fadeIn('slow');
					}
				},
				error: function(data)
				{
					console.log(data);
				}
			});
		});


// Pincode Validation Function-------
	$(document).ready(function() {
    $("#pincode_availability").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
});

$(document).on('click', '#send_product_enquiry', function (){
   
    var x = $('#query_mobile').val().startsWith(7);
    var y = $('#query_mobile').val().startsWith(8);
    var z = $('#query_mobile').val().startsWith(9);
    
    if($('#query_name').val() == '')
    {
        $('#query_name_error').text('Name must be required.').fadeIn('slow');
        return false;
    }
    else
    {
        $('#query_name_error').fadeOut('slow');
    }
    // Mobile Validation
    if($('#query_mobile').val() == '')
    {
        $('#query_mobile_error').text('Mobile must be required.').fadeIn('slow');
        return false;
    }    
    else if(x == false && y == false && z == false)
    {
        $("#query_mobile_error").text('Please Enter Valid Mobile.').fadeIn('slow');
        return false;
    }
    else if($('#query_mobile').val().length < 10)
    {
        $('#query_mobile_error').text('Mobile must be 10 digit.').fadeIn('slow');
        return false;
    }
    else
    {
        $('#query_mobile_error').fadeOut('slow');
    }
    // Email Validation
    if($('#query_email').val() == '')
    {
        $('#query_email_error').text('Email must be required.');
        return false;
    }
    else if(!isValidEmailAddress($('#query_email').val()))
    {
        $('#query_email_error').text('Please enter valid email.');
        return false;
    }
    else
    {
        $('#query_email_error').fadeOut('slow');
    }
    
    if($('#query_message').val() == '')
    {
        $('#query_message_error').text('Message must be required.');
        return false;
    }
    else if($('#query_message').val().length < 10)
    {
        $('#query_message_error').text('Message must be greater than 10 words.');
        return false;
    }
    else
    {
        $('#query_message_error').fadeOut('slow');
    }
    
    $.ajax({
        url: 'Product_details/save_product_enquiry',
		type: 'post',
		data: { product_id: $('#p').val(),seller_id: $('#s').val(), name: $('#query_name').val(), mobile: $('#query_mobile').val(), email: $('#query_email').val(), message: $('#query_message').val() },
		dataType: 'json',
        success: function(json)
        {
            if(json['success'])
            {
                $('#show_msg').text(json['success']);
                $('#send_product_enquiry').attr('disabled','disabled');
                setTimeout(function(){ $.fn.fancybox.close(); }, 1500);
            }
            if(json['failed'])
            {
                $('#show_msg').text(json['failed']);
                setTimeout(function(){ $.fn.fancybox.close(); }, 3000);
            }
        },
        error: function(data)
        {
            console.log(data);
        }
    });
    
});

function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
    
};



    