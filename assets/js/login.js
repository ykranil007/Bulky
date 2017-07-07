// For login
$('#user_login_btn').click(function()
{
	var login_mobile = $('#login_email').val();	
	if(!$.isNumeric(login_mobile))
	{
		if(login_mobile == '' || !isValidEmailAddress(login_mobile))
		{
			$("#login_email_error").fadeIn('slow');
			return false;		
		}
		else
		{
			$("#login_email_error").fadeOut('slow');
		}
	}
	else 
	{
		var x = login_mobile.startsWith(7);
		var y = login_mobile.startsWith(8);
		var z = login_mobile.startsWith(9);
		if(login_mobile == '' || login_mobile.length != 10 || (x == false && y == false && z == false))
		{
			$("#login_email_error").fadeOut('slow');
			$("#login_email_error").fadeIn('slow');
			$("#login_email_error").text('Please Enter Valid Mobile!');
			return false;
		}
		else
		{
			$("#login_email_error").fadeOut('slow');
		}			
	}
	/*if($('#login_email').val() == '' || !isValidEmailAddress($('#login_email').val()))
	{
		$("#login_email_error").fadeIn('slow');
		return false;		
	}
	else
	{
		$("#login_email_error").fadeOut('slow');
	}*/
	if($('#login_password').val() == '')
	{
		$("#login_password_error").fadeIn('slow');
		return false;		
	}
	else
	{
		$("#login_password_error").fadeOut('slow');
	}
	
	$.ajax(
	{
		url	: "validate-login",
		type	: 'post',
		data	: $('#user_login').serialize(),
		dataType: 'json',
		beforeSend : function() 
		{
			$("#user_login_btn").html("<img src='assets/images/load.gif' width='18' style='margin-top:12px; margin-right:4px;' align='left' /> Logging in...");
		},
		success : function(json) 
		{ 
			$("#user_login_btn").text("LOGIN");

			 if(json.accountstatus == 'activated')
				{
					$('#login_failure').hide();
					$('.login_success').html('Login Successfully! Redirecting You.....').fadeIn();
					if(json.type == 2)
				    {	
				    	 setTimeout(function(){window.location.href = json.url;}, 1000);				   
					}			
				}			
				else if((json.accountstatus == 'deactivated') && (json.type == 2))
				{ 	
					
					$('#register_error').html('Oops! Please Try Again.').fadeIn();
					$('#user_registration').hide();
					$('#otp_verification').fadeIn();			
				}
				else if(json.accountstatus == 'social_log_in')
				{
					setTimeout(function(){window.location.href = 'upgrade/form';}, 1000);
				}
				else
				{
					$('#login_failure').html('Oops! Email-ID or Password Incorrect! Try Again.').fadeIn();
				}
		},
		error: function(data)
		{			
			console.log(data);
		}
	});
	return false;
});

$("#login_password").keyup(function(event){
        if(event.keyCode == 13){
           $("#user_login_btn").trigger("click");
           }
    });

$('.not_user_btn').click(function(){
	$('.login').hide();
	$('.signup').show();
});
$('.already_user_btn').click(function(){
	$('.signup').hide();
	$('.login').show();
	
});

$('.otp_login_btn').click(function(){
   $('#user_login').hide();
   $('#login_email').val('');
   $('#login_password').val('');
   $("#otp_login_email_error").hide();
   $('.otp_login').show(); 
});

$('.login_email_or_mobile').click(function(){
   $('#user_login').show();
   $('.otp_login').hide();
   $('#otp_login_email').val('');
   $("#login_email_error").hide();
});

$('#send_otp_login_btn').click(function()
{
	var login_mobile = $('#otp_login_email').val();	
    
	if(!$.isNumeric(login_mobile))
	{
		if(login_mobile == '' || !isValidEmailAddress(login_mobile))
		{
			$("#otp_login_email_error").fadeIn('slow');
            $("#otp_login_email_error").text('Please Enter Valid Email.');
			return false;		
		}
		else
		{
			$("#otp_login_email_error").fadeOut('slow');
		}
	}
	else 
	{
		var x = login_mobile.startsWith(7);
		var y = login_mobile.startsWith(8);
		var z = login_mobile.startsWith(9);
		if(login_mobile == '' || (x == false && y == false && z == false))
		{
			$("#otp_login_email_error").fadeOut('slow');
			$("#otp_login_email_error").fadeIn('slow');
			$("#otp_login_email_error").text('Please Enter Valid Mobile!');
			return false;
		}
        else if(login_mobile.length != 10)
        {
            $("#otp_login_email_error").fadeOut('slow');
			$("#otp_login_email_error").fadeIn('slow');
			$("#otp_login_email_error").text('Please Enter 10 Digit Mobile!');
			return false;
        }
		else
		{
			$("#otp_login_email_error").fadeOut('slow');
		}			
	}
	
	$.ajax(
	{
		url	: "validate-otp-login",
		type	: 'post',
		data	: {login_field:login_mobile},
		dataType: 'json',
		beforeSend : function() 
		{
			$("#send_otp_login_btn").html("<img src='assets/images/load.gif' width='18' style='margin-top:12px; margin-right:4px;' align='left' /> Logging in...");
		},
		success : function(json) 
		{ 
			 if(json.otp_success)
			{
				$('.login_failure').hide();
                $('.otp_login').hide();
                $('.otp_login_verify').show();
				$('.login_success').html(json.otp_success).fadeIn();			
			}			
			else if(json.otp_failed)
			{ 	
				$('.login_success').hide();
                $('.login_failure').html(json.otp_failed).fadeIn();			
			}
		},
		error: function(data)
		{			
			console.log(data);
		}
	});
	return false;
});

$('#validate_otp_login_btn').click(function()
{
	var login_otp = $('#otp_login_otp').val();	
    
	if(login_otp == '' && login_otp.length != 4)
	{
		$("#otp_login_otp_error").text('Please Enter Valid OTP.').fadeIn('slow');
		return false;		
	}
	else
	{
		$("#otp_login_otp_error").fadeOut('slow');
	}
	
	$.ajax(
	{
		url	: "validate-otp-login-otp",
		type	: 'post',
		data	: {login_otp:login_otp},
		dataType: 'json',
		beforeSend : function() 
		{
			$("#validate_otp_login_btn").html("<img src='assets/images/load.gif' width='18' style='margin-top:12px; margin-right:4px;' align='left' /> Validating...");
		},
		success : function(json) 
		{ 
		    $("#validate_otp_login_btn").text("Validate OTP");
			if(json.accountstatus == 'activated')
			{
				$('.login_failure').hide();
				$('.login_success').html('Successfully Login! Redirecting You.....').fadeIn();	
   	            setTimeout(function(){window.location.href = json.url;}, 1000);				   
							
			}			
			else if(json.accountstatus == 'deactivated')
			{ 	
				$('.login_success').hide();
                $('.login_failure').html('Oops! You Entered Wrong OTP.').fadeIn();			
			}
		},
		error: function(data)
		{			
			console.log(data);
		}
	});
	return false;
});


// Mobile Validation Function-------
	$(document).ready(function() {
    $("#otp_login_otp").keydown(function (e) {
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

function isValidEmailAddress(emailAddress)
{
    var pattern = new RegExp(/^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i);
    return pattern.test(emailAddress);
};

$('.otp_login_resend_otp').click(function(){
   $.ajax({
        url: 'resend-login-otp',
        dataType: 'json',
        success: function(json)
        {
            if(json.s_message)
            {
                $('.login_failure').hide();
                $('.login_success').hide();
                $('.login_success').text(json.s_message).fadeIn();   
            }
            else if(json.f_message)
            {
                $('.login_success').hide();
                $('.login_failure').text(json.f_message).fadeIn();
            }
          
        },
        error: function(data)
        {
            console.log(data);
        }
   }); 
});
 
$("#otp_login_email").keyup(function(event){
    if(event.keyCode == 13){
       $("#send_otp_login_btn").trigger("click");
       }
});

$("#otp_login_otp").keyup(function(event){
    if(event.keyCode == 13){
       $("#validate_otp_login_btn").trigger("click");
       }
});