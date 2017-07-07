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
					$('.login_success').html('Login Successfull! Redirecting You.....').fadeIn();
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


function isValidEmailAddress(emailAddress)
 {
    var pattern = new RegExp(/^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i);
    return pattern.test(emailAddress);
};

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