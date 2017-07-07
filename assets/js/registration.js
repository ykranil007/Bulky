// JavaScript Document

//--- User Registration Section
jQuery(function($) {
	// E-mail validation
	$("#user_registration input[name='email']").bind("blur", function() 
	{ 
		if(!isValidEmailAddress(this.value))
		{
 			$("#email_error").fadeIn('slow');
 			$("#email_error").text("Invalid Email. Please Enter a Valid Email-ID.");
 			return false;		
		}
		$.ajax({
			url: "Register/checkEmail",
			type: 'post',
			data	:	$('#user_registration').serialize(),
			dataType : 'json',
			beforeSend : function() {
 				$("#chkemail").html("<img src='assets/images/load.gif' width='22' style='margin-top:6px; margin-right:4px;' align='left' />");
 				$("#email_msg").hide();
 				$("#email_error").fadeIn('slow');
 				$("#email_error").text("Validating Your Email. Please Wait...");
			},
			success : function(json) { 
				$("#chkemail").text("Check");
				// Checking Required fields ----------------------------------------------------------------------
				if(json['email_error']) {
 					$("#email_error").fadeIn('slow');
 					$("#email_error").text(json['email_error']);
				}
				if(json['success']) {
 					$("#email_error").fadeIn('slow');
 					$("#email_error").html(json['success']);
 					$("#btn_create_user").removeAttr('disabled');
				}
				if(json['failed']) {
 					$("#email_error").fadeIn('slow');
 					$("#email_error").text(json['failed']);
 					$("#btn_create_user").attr('disabled','disabled');
				}				
			},
			error: function(data)
			{
				console.log(data);
			}
		});
	});	
	// Password check Function-------
	$('#password').keyup(function() {
		$('#password_error').html(checkStrength($('#password').val()))
	});
// End of Password check -----------------------------

	$("#btn_create_user").click(function() { 

	 	if($.trim(replace_string($('#firstname').val())) == '') 
		{
			$("#firstname_error").text('Please Enter Your First Name.').fadeIn();
			return false;			
		}
		else
		{
			$("#firstname_error").fadeOut();
		}
		/*if($.trim(replace_string($('#lastname').val())) == '') 
		{
			$("#lastname_error").text('Please Enter Your Last Name.').fadeIn();
			return false;			
		}
		else
		{
			$("#lastname_error").fadeOut();
		}*/
		if($('#email').val() == '') 
		{
			$("#email_error").text('Please Enter Your Email-ID.').fadeIn();
			return false;			
		}
		else
		{
			$("#email_error").fadeOut();
		}
		if($.trim(replace_string($('#password').val())) == '') 
		{
			$("#password_error").text('Please Enter Your Password.').fadeIn();
			return false;			
		}
		else
		{
			$("#password_error").fadeOut();
		}
		if($.trim(replace_string($('#confirmpassword').val())) == '') 
		{
			$("#confirmpassword_error").text('Please Confirm Your Password.').fadeIn();
			return false;			
		}
		else
		{
			$("#confirmpassword_error").fadeOut();
		}
		if($.trim(replace_string($('#password').val())) != $.trim(replace_string($('#confirmpassword').val()))) 
		{
			$("#confirmpassword_error").text('Both Password And Confirm Password Must Be Same.').fadeIn();
			return false;			
		}
		else
		{
			$("#confirmpassword_error").fadeOut();
		}
		if($('#mobile').val() == '') 
		{
			$("#mobile_error").text('Please Enter Your Mobile.').fadeIn();
			return false;			
		}
		else
		{
			$("#mobile_error").fadeOut();
		}
	 	var x = $('#mobile').val().startsWith(7);
		var y = $('#mobile').val().startsWith(8);
		var z = $('#mobile').val().startsWith(9);
		
		if($('#mobile').val().length < 10)
		{
			$("#mobile_error").text('Mobile Must Be 10 Digit.').fadeIn('slow');
			return false;		
		}
		else
		{
			$("#mobile_error").fadeOut('slow');
		}
		if(x == false && y == false && z == false)
		{
			$("#mobile_error").text('Please Enter Valid Mobile.').fadeIn('slow');
			return false;		
		}
		$.ajax({
			url: "validate",
			type: 'post',
			data	:	$('#user_registration').serialize(),
			dataType : 'json',
			beforeSend : function() {  
				$("#btn_create_user").html("<img src='assets/images/load.gif' width='18' style='margin-top:12px; margin-right:4px;' align='left' /> Validating Fields...");
			},
			success : function(json) {
				$("#btn_create_user").text("SIGN UP");
				// Checking Required fields ----------------------------------------------------------------------
				if(json['terms_error']) 
				{ 

					$("#terms_error").text(json['terms_error']).fadeIn('slow');
				}
				// End of Checking Required fields ------------------------------------------------------------------------
				if(json['success']) 
				{  
					$("#register_error").hide();
					$("#register_success").fadeIn('slow');
					$("#register_success").html(json['success']);
					$('html, body').animate({scrollTop : 0},800);
					$('.validation_error').hide();
					$('#user_registration').trigger("reset");
					setTimeout(function(){
		               window.location.href = "thank-you";
		            }, 1000);
					
				}				
				
				if(json['failed']) 
				{ 
					$("#register_success").hide();
					$("#register_error").fadeIn('slow');
					$("#register_error").html(json['failed']);
					$('html, body').animate({scrollTop : 0},800);
					
				}

				if(json['otpsuccess'])
				{
					if(json['is_bulk_user'])
					{
						$("#register_error").hide();
						$("#register_success").fadeIn('slow');
						$("#register_success").html(json['otpsuccess']);
						$('#user_registration').hide();
						$('#otp_verification').fadeIn('slow');
						$('#upload_documents').show();
						$('#bulk_user_document,#view_bulk_user_document,#upload_document').attr('disabled',true);
						$('html, body').animate({scrollTop : 0},800);
					}
					else
					{
						$("#register_error").hide();
						$("#register_success").fadeIn('slow');
						$("#register_success").html(json['otpsuccess']);
						$('#user_registration').hide();
						$('#otp_verification').fadeIn('slow');
						$('html, body').animate({scrollTop : 0},800);
					}
				}

				if(json['otpfailed']) 
				{ 
					$("#register_success").hide();
					$("#register_error").fadeIn('slow');
					$("#register_error").html(json['otpfailed']);
					
				}

				if(json['otpvalue'])
				{
					$("#verification_error").html(json['otpvalue']);
				}
				if(json['exist'])
				{
					$("#mobile_error").fadeIn('slow');
 					$("#mobile_error").text(json['exist']);
				}
			},
			error: function(data)
			{
				console.log(data);
			}
		});	
	});
	
	//OTP Verification
	$("#user_otp_btn").click(function() { 
	 
		$.ajax({
			url: "otp-validate",
			type: 'post',
			data	:	$('#otp_verification').serialize(),
			dataType : 'json',
			beforeSend : function() {  
				$("#user_otp_btn").html("<img src='assets/images/load.gif' width='18' style='margin-top:12px; margin-right:4px;' align='left' /> Validating OTP...");
			},
			success : function(json) {
				$("#user_otp_btn").text("VERIFY");
				// Checking Required fields ----------------------------------------------------------------------
				if(json['verification_error']) 
				{ 
					$("#register_error").hide();
					$("#verification_error").text(json['verification_error']);
				}

				if(json['success']) 
				{  
					$("#register_error").hide();
					$("#register_success").fadeIn('slow');
					$("#register_success").html(json['success']);
					$('html, body').animate({scrollTop : 0},800);
					$('.validation_error').hide();
					$('#user_registration').trigger("reset");
					 setTimeout(function(){
		               window.location.href = "thank-you";
		            }, 1000);

				}
				if(json['is_bulk_user'])
				{
					$("#register_error").hide();
					$("#register_success").fadeIn('slow');
					$("#register_success").html(json['is_bulk_user']);
					$('html, body').animate({scrollTop : 0},800);
					$('.validation_error').hide();
					$('#verification,#user_otp_btn').attr('disabled',true);
					$('.text-center').hide();
					$('#bulk_user_document,#view_bulk_user_document,#upload_document').removeAttr('disabled');
				}
				if(json['failed']) 
				{ 
					$("#register_success").hide();
					$("#register_error").fadeIn('slow');
					$("#register_error").html(json['failed']);
					$('html, body').animate({scrollTop : 0},800);
					
				}				
			},
			error: function(data)
			{
				console.log(data);
			}
		});	
	});
// Checking Password Strength 
	function checkStrength(password)
    { 
		var strength = 0
		if (password.length < 6) { 
			return 'Too short' 
		}
		if (password.length > 7) strength += 1
		if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))  strength += 1
		if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))  strength += 1 
		if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))  strength += 1
		if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
		if (strength < 2 )
		{
			return 'Weak'			
		}
		else if (strength == 2 )
		{
			return '<span style="color:orange">Good</span>'		
		}
		else
		{
			return '<span style="color:green">Strong</span>'
		}
   }
	//--Function to check email is valid or not
	function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
    
};	
	
	
});

//--- Forgot Password Validation Section


/*function checkForgotEmail() {
	
	$("#forgotemail input[name='forgot_email']").bind("blur", function() 
	{ 
		if(!isValidEmailAddress(this.value) && (this.value) != '')
		{
 			$("#forgot_email_error").fadeIn('slow');
 			$("#forgot_email_error").text("Invalid Email. Please Enter a Valid Email-ID.");
 			$('#forgot_pass_btn').attr('disabled','disabled');
 			return false;		
		}
		$.ajax({
			url: "Register/checkEmail",
			type: 'post',
			data	:	$('#forgotemail').serialize(),
			dataType : 'json',
			beforeSend : function() {
 				$("#forgot_email_error").fadeIn('slow');
 				$("#forgot_email_error").text("Please Enter Email-ID.");
			},
			success : function(json) { 
				
				if(json['forgotfailed'])
				{
					$("#forgot_email_error").fadeIn('slow');
					$('#forgot_email_error').html(json['forgotfailed']);
					$('#forgot_pass_btn').attr('disabled','disabled');
				}
				if(json['forgotsuccess'])
				{
					$("#forgot_email_error").fadeIn('slow');
					$('#forgot_email_error').html(json['forgotsuccess']);
					$('#forgot_pass_btn').removeAttr('disabled');
				}			

			},
			error: function(data)
			{
				console.log(data);
			}
		});
	});

};*/


$('#forgot_pass_btn').click(function(){
    if(!isValidEmailAddress($('#forgot_email').val()))
    {
        $("#forgot_email_error").text('Please Enter Valid Email-ID');
        return false;
    }
    	$.ajax(
    	{
    		url: 'Register/ChooseForgotMethod',
    		type: 'post',
    		data: $('#forgotemail').serialize(),
    		dataType: 'json',
    		beforeSend: function()
    		{
    			$('#forgot_pass_btn').html("<img src='assets/images/load.gif' width='18' style='margin-top:12px; margin-right:4px;' align='left' /> Sending OTP...");
    		},
    		success: function(json)
    		{
    			$('#forgot_pass_btn').text('SEND');

    			if(json['email_error']) 
				{
					$("#forgot_email_error").text(json['email_error']);
				}
				if(json['success'])
				{
					$("#register_msg_error").hide();
					$("#register_msg_success").fadeIn('slow');
					$("#register_msg_success").html(json['success']);
					$('#forgotemailid').hide();
					$('#forgotradio').fadeIn('slow');					
					$('html, body').animate({scrollTop : 0},800);
				}				

				if(json['otpsuccess'])
				{
					$('#register_msg_error').hide();
					$('#register_msg_success').fadeIn('slow');
					$('#register_msg_success').text(json['otpsuccess']);
					$('#forgotemail').hide();
					$('#forgotradio').hide();
					$('#forgototpcode').fadeIn('slow');
					$('html, body').animate({scrollTop : 0},800);
				}
				if(json['forgotfailed'])
				{
					$("#forgot_email_error").fadeIn('slow');
					$('#forgot_email_error').html(json['forgotfailed']);
					$('#forgot_pass_btn').attr('disabled','disabled');
				}
    		},
    		error: function(data)
			{
				console.log(data);
			}
    	});
    });

$("#forgot_otp_btn").click(function() { 
	 
		$.ajax({
			url: "forgot-otp-validate",
			type: 'post',
			data	:	$('#forgototpcode').serialize(),
			dataType : 'json',
			beforeSend : function() {  
				$("#forgot_otp_btn").html("<img src='assets/images/load.gif' width='18' style='margin-top:12px; margin-right:4px;' align='left' /> Validating OTP...");
			},
			success : function(json) {
				$("#forgot_otp_btn").text("VERIFY");
				// Checking Required fields ----------------------------------------------------------------------
				if(json['verification_error']) 
				{ 
					$("#forgot_verification_error").text(json['verification_error']);
				}

				if(json['success']) 
				{  
					$("#register_msg_error").hide();
					$("#register_msg_success").fadeIn('slow');
					$("#register_msg_success").html(json['success']);				
					$('#forgototpcode').hide();
					$('#forgotnewpassword').fadeIn('slow');
					tag = ' <input type="hidden" name="user_code" value="'+json["usercode"]+'">';
					$('#forgotnewpassword input[type="password"]').html(tag);
								

				}

				if(json['failed']) 
				{ 
					$("#register_msg_success").hide();
					$("#register_msg_error").fadeIn('slow');
					$("#register_msg_error").html(json['failed']);
					$('html, body').animate({scrollTop : 0},800);
					
				}				
			},
			error: function(data)
			{
				console.log(data);
			}
		});	
	});

$('#forgot_newpass_btn').click(function(){

			$.ajax({
				url: 'change-password',
				type: 'post',
				data: $('#forgotnewpassword').serialize(),
				dataType: 'json',
				beforeSend: function()
				{
					$("#forgot_newpass_btn").html("<img src='assets/images/load.gif' width='18' style='margin-top:12px; margin-right:4px;' align='left' /> Updating...");
			
				},
				success: function(json)
				{
					$("#forgot_newpass_btn").text("SUBMIT");

					if(json['create_password_error']) 
					{ 
						$("#create_password_error").text(json['create_password_error']);
					}

					if(json['success'])
					{
						$('#register_msg_error').hide();
						$("#register_msg_success").fadeIn('slow');
						$("#register_msg_success").html(json['success']);
						$('#forgotnewpassword').hide();
						$('#forgotnewpassword').trigger("reset");
						window.setTimeout(function(){
							window.location.href = json.url;
							
							}, 3000);
					}
				},
				error: function(data)
				{
					console.log(data);
				}
			});
		});

// Mobile Validation Function-------
	$(document).ready(function() {
    $("#mobile,#verification").keydown(function (e) {
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

$("#verification").keyup(function(event)
{
    if(event.keyCode == 13)
    {
       $("#user_otp_btn").trigger("click");           
    }
});

$("#forgot_email").keyup(function(event)
{
    if(event.keyCode == 13)
    {
       $("#forgot_pass_btn").trigger("click");           
    }
});

$("#verification_otp").keyup(function(event)
{
    if(event.keyCode == 13)
    {
       $("#forgot_otp_btn").trigger("click");           
    }
});
$("#newpassword").keyup(function(event)
{
    if(event.keyCode == 13)
    {
       $("#forgot_newpass_btn").trigger("click");           
    }
});

$("#resend_otp").click(function() {    
		$.ajax({
			url: "resend-otp",
			dataType : 'json',		
			success : function(json) {
							
				if(json['success'])
				{
					$("#register_error").hide();
					$("#register_success").hide();
					$("#register_success").fadeIn('slow');
					$("#register_success").html(json['success']);
					$("#login_failure").hide();
					$("#login_success").hide();
					$("#login_success").fadeIn('slow');
					$("#login_success").html(json['success']);
				}
				if(json['failed']) 
				{ 
					$("#register_success").hide();
					$("#register_error").fadeIn('slow');
					$("#register_error").html(json['failed']);
					$("#login_success").hide();
					$("#login_failure").fadeIn('slow');
					$("#login_failure").html(json['failed']);
				}				
			},
			error: function(data)
			{
				console.log(data);
			}
		});	
	});

$('#change_mobile_number').click(function(){
	$("#register_success").hide();
	$("#register_error").hide();
	$('#otp_verification').hide();
	$('#change_mobile').fadeIn(1000);
});

//Send OTP on New Mobile
	$("#send_new_otp_btn").click(function() { 
		$.ajax({
			url: "new-otp-validate",
			type: 'post',
			data	:	$('#new_otp_verify').serialize(),
			dataType : 'json',
			beforeSend : function() {  
				$("#user_otp_btn").html("<img src='assets/images/load.gif' width='18' style='margin-top:4px; margin-right:4px;' align='left' /> Validating OTP...");
			},
			success : function(json) {
				$("#user_otp_btn").text("VERIFY");
				// Checking Required fields ----------------------------------------------------------------------
				if(json['new_mobile_error']) 
				{					
					$("#new_mobile_error").text(json['new_mobile_error']);
				}
				if(json['otpsuccess'])
				{
					if(json['is_bulk_user'])
					{
						$("#register_error").hide();
						$("#register_success").fadeIn('slow');
						$("#register_success").html(json['otpsuccess']);
						$('#change_mobile').hide();
						$('#otp_verification').fadeIn('slow');
						$('#upload_documents').attr('disabled','disabled');
						$('html, body').animate({scrollTop : 0},800);
					}
					else
					{
						$("#register_error").hide();
						$("#register_success").fadeIn('slow');
						$("#register_success").html(json['otpsuccess']);
						$('#change_mobile').hide();
						$('#otp_verification').fadeIn('slow');
						$('html, body').animate({scrollTop : 0},800);
					}					
				}
				if(json['otpfailed']) 
				{ 
					$("#register_success").hide();
					$("#register_error").fadeIn('slow');
					$("#register_error").html(json['otpfailed']);
				}				
			},
			error: function(data)
			{
				console.log(data);
			}
		});	
	});

$('#bulk_documnents').on('submit',function(e){
	e.preventDefault();
	if($('#bulk_user_document').val() == '')
 	{
 		$('#bulk_user_document_error').fadeOut();
 		$('#bulk_user_document_error').fadeIn();
 		$('#bulk_user_document_error').text('Please Upload Documents');
 		return false;
 	}
 	else
 	{
 		$('#bulk_user_document_error').fadeOut();
 	}
	$.ajax({  
                url: "upload-bulk-user-documents",  
                type: "POST",  
				dataType:"JSON",
                data: new FormData(this),  
                contentType: false,  
                processData:false,  
                success: function(json)  
                {  
                	if(json.status == 1)
                	{
                		$("#register_error").hide();
                		$("#register_success").hide();
						$("#register_success").fadeIn('slow');
						$("#register_success").text('Successfully Uploaded!');
						window.setTimeout(function(){
							window.location.href = 'thank-you';							
							}, 2000);
                	}
                	
                },
				error: function(data)
				{
					//console.log(data);
				}  
           });

});

function replace_string(string) 
{
    return string.trim().replace(/["~!@#$%^&*'\(\)`{}\[\]\|\\:;'<>,.\/?"\t\r\n]+/g, '');
}