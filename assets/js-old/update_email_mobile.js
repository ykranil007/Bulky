// JavaScript Document

$( function() {
$('#updateUserEmailMobile').attr("disabled", "disabled");


$('.edit_email_field_btn').on('click',function(){
	$(this).hide();
	$('.email_add_cancel_btn').show();
	$('#email_span_val').hide();
	$('#UserUpdateEmail').show();
	
	});
	$('.edit_mob_field_btn').on('click',function(){
		$(this).hide();
		$('.cancel_mob_add_btn').show();
		$('#userupdateMobile').show();
		$('#mob_span_val').hide();
	});
	$('#email_cancel_btn').on('click',function(){
		$('.edit_email_field_btn').show();
		$('.email_add_cancel_btn').hide();
		$('#UserUpdateEmail').hide();
		$('#email_span_val').show();
		
		//-----hide all error msg -------
		$('#email_add_error').hide();
		$('#error_reporter').hide();
		$('#email_pass_error').hide();
		//-------end---------------------
	});
	$('#mob_cancel_btn').on('click',function(){
		$('.cancel_mob_add_btn').hide();
		$('.edit_mob_field_btn').show();
		$('#userupdateMobile').hide();
		$('#mob_span_val').show();
		$('#user_mobile_otp').hide();
		$('#user_mobile_pass').hide();
		//------hide all error msg---
		$('#mob_add_error').hide();
		$('#error_reporter').hide();
		$('#pass_error').hide();
		
		//------end------------------
	})
	
	//$('#mob_add_btn').on('click',function(){
		//====on keyup check mobno.==========
	$('#userupdateMobile').keyup(function(){
		var add_mob_val = $('#userupdateMobile').val();
		var add_mob_status = true;
		var mob_saved_val = $('#mob_span_val').text();
		
		if(add_mob_val.length != 10)
		{
			add_mob_status = false;
			$(this).closest('div').addClass(' has-error');
			$('#mob_add_error').text('Enter 10 digits valid mobile no.').show();
		}
		else if(!$.isNumeric(add_mob_val))
		{
			add_mob_status = false;
			$(this).closest('div').addClass(' has-error');
			$('#mob_add_error').text('Enter only 10 digits numeric value.').show();
		}
		else if(add_mob_val == mob_saved_val)
		{
			add_mob_status = false;
			$(this).closest('div').addClass(' has-error');
			$('#error_reporter').text('Entered mobile number is same as registered mobile no.').show();
			$('#mob_add_error').text('').hide();
		}
		if(add_mob_status == true)
		{	
			$('#mob_add_error').text('').hide();
			$('#mob_add_error').hide();
			$.ajax({
					url:'user-exist',
					type:'post',
					data:{user_mob:add_mob_val},
					dataType:'json',
					success: function(json)
					{
						if(json['mob_exist'] == 1)
						{
							mob_status =false;
							$('#mob_add_error').text('Entered mobile number is registered with another user.').show();
						}
						else if(json['mob_exist'] == 0)
						{
							$('#mob_add_error').text('').hide();
						}
					},
					error: function(data)
					{
						console.log(data);
					}
				});
		}
	});
//====ENd on keyup check mobno.======================
//========for add mobno.===============
	
	$('#mob_add_btn').on('click',function(){
		var mob_status	= true;
		$('#user_mobile_pass').attr("disabled", "disabled");
		//====for check not null value============
		var add_mob_val = $('#userupdateMobile').val();
		if(jQuery.trim(add_mob_val) == "" || jQuery.trim(add_mob_val) == 0)
    	{ 
		  mob_status = false;
		  $('#userupdateMobile').closest('div').addClass(' has-error');
		  $('#mob_add_error').text('Mobile number can not  be blank.').show();
    	}
		//======end===============================
		if($('#userupdateMobile').val() == $('#mob_span_val').text())
		{
			mob_status = false;
			$('#mob_add_error').text('Mobile number is same as registered mobile no.').show();
		}
		if(mob_status == true )
		{
			if($('#userupdateMobile').closest('div').hasClass("has-error"))
		  	{
			 	$('#userupdateMobile').closest('div').removeClass('has-error');
		  	}
			var mob_no = $('#userupdateMobile').val();
			$('#user_mobile_otp').show();
			$('#user_mobile_pass').show();
			$.ajax({
				url:'send-otp-mobupdate',
				type:'post',
				data:{mobileno:mob_no},
				dataType:'json',
				success: function(json)
				{
					$('#success_reporter').text('Verification Code is send to - ' +  mob_no ).show();
				},
				error: function(data)
				{
					console.log(data);
				}
				});
		}
	});
	$('#user_mobile_otp').keyup(function(){
		
		var otp_enter_from_user = $('#user_mobile_otp').val();
		$.ajax({
				url:'get-userotp',
				type:'post',
				data:{user_otp:otp_enter_from_user},
				dataType:'json',
				success: function(json)
				{
					if(json['otp'] != otp_enter_from_user)
					{
						$('#otp_error').hide();
						$('#otp_error').show();
						$('#otp_error').text('Enter valid Verification code.').show();
					}
					if(json['otp'] == otp_enter_from_user)
					{
						$('#user_mobile_pass').removeAttr("disabled", "disabled");
						$('#otp_error').hide();
					}
				},
				error: function(data)
				{
					console.log(data);
				}
			});
		});
		//=====make variable for update mobile=====
		var updateMobileStatus  = false;
		$('#user_mobile_pass').keyup(function(){
				var pass_enter_from_user = $('#user_mobile_pass').val(); 
				$.ajax({
				url:'get-userpass',
				type:'post',
				data:{user_pass:pass_enter_from_user},
				dataType:'json',
				success: function(json)
				{
					if(json['pass'] == 1)
					{
						$('#pass_error').hide();
						$('#updateUserEmailMobile').removeAttr("disabled", "disabled");
						updateMobileStatus = true;
					}
					if(json['pass'] == 0)
					{
						$('#pass_error').text('Please Enter valid password.').show();
						$('#updateUserEmailMobile').attr("disabled", "disabled");
					}
				},
				error: function(data)
				{
					console.log(data);
				}
				});
			});
	/*if(updateMobileStatus == true) //===== update mobile
	{
	$('#updateUserEmailMobile').on('click',function(){	
		var user_entered_mobno	= $('#userupdateMobile').val();
			$.ajax({
					url:'update-usermobile',
					type:'post',
					data:{mobno:user_entered_mobno},
					dataType:'json',
					success: function(json)
					{
						if(json['update_success'] == 1)
						{
							$('#success_reporter').text('Thanks Your mobile is updated successfully.').show();
							
						}
						if(json['update_success'] == 0)
						{
							$('#error_reporter').text('Sorry somthing went wrong. Please retry.').show();
						}
					},
					error: function(data)
					{
						console.log(data);
					}
					
				});
		});
	}*/
//========end add mobno.================
//==========start for EMAIL changes==============
	$('#UserUpdateEmail').keyup(function(){
		
		var entered_email	= $('#UserUpdateEmail').val();
		var saved_email		= $('#email_span_val').text();
			if(jQuery.trim(entered_email) == jQuery.trim(saved_email))
			{
				$('#email_add_error').text('Entered Email is same as registered email.').show();
				email_status = false;
			}
			else
			{
				$('#email_add_error').hide();
				email_status = true;
			}
		});
	$('#email_add_btn').on('click',function(){
			var email_status = true;
			$('#user_email_pass').attr("disabled", "disabled");
			
			var entered_email	= $('#UserUpdateEmail').val();
			var saved_email		= $('#email_span_val').text();
			if(jQuery.trim(entered_email) == "" || jQuery.trim(entered_email) == 0)
			{
				$('#email_add_error').text('Email can not be blank.').show();
				$('#UserUpdateEmail').closest('div').addClass(' has-error');
				email_status = false;
			}
			if(jQuery.trim(entered_email) == jQuery.trim(saved_email))
			{
				$('#email_add_error').text('Entered Email is same as registered email.').show();
				email_status = false;
			}
			if(email_status == true)
			{
			if($('#UserUpdateEmail').closest('div').hasClass("has-error"))
			{
				$('#UserUpdateEmail').closest('div').removeClass('has-error');
			}
			var email = $('#UserUpdateEmail').val();
			$.ajax({
					url:'get-existingemail',
					type:'post',
					data:{email_id:email},
					dataType:'json',
					success: function(json)
					{
						
						if(json['email_exist'] == 1)
						{
							$('#email_add_error').text('Entered Email is registered with another user.').show();
							email_status = false;
						}
						if(json['email_exist'] == 0)
						{
							$('#success_reporter').text('Verification Code is send to - ' +' '+  email ).show();
							$('#email_add_error').hide();
							$('#user_email_otp').show();
							$('#user_email_pass').show();
						}
					},
					error: function(data)
					{
						console.log(data);
					}
				});
			}
			//----------------------
			});
		$('#user_email_otp').keyup(function(){
				var otp_from_user = $('#user_email_otp').val();
				$.ajax({
				url:'get-emailotp',
				type:'post',
				data:{user_email_otp:otp_from_user},
				dataType:'json',
				success: function(json)
				{
					if(json['otp'] != otp_from_user)
					{
						$('#email_otp_error').text('Enter valid Verification code.').show();
					}
					if(json['otp'] == otp_from_user)
					{
						$('#user_email_pass').removeAttr("disabled", "disabled");
						$('#email_otp_error').hide();
					}
				},
				error: function(data)
				{
					console.log(data);
				}
			});
			});
			
			//======make variable for update email
			var updateEmailStatus	= false;
		$('#user_email_pass').keyup(function(){
			var pass_from_user = $('#user_email_pass').val(); 
				$.ajax({
				url:'get-emailpass',
				type:'post',
				data:{useremail_pass:pass_from_user},
				dataType:'json',
				success: function(json)
				{
					if(json['pass'] == 1)
					{
						$('#email_pass_error').hide();
						$('#updateUserEmailMobile').removeAttr("disabled", "disabled");
						updateEmailStatus = true;
					}
					if(json['pass'] == 0)
					{
						$('#email_pass_error').text('Please Enter valid password.').show();
						$('#updateUserEmailMobile').attr("disabled", "disabled");
					}
				},
				error: function(data)
				{
					console.log(data);
				}
				});
			});
		
		$('#updateUserEmailMobile').on('click',function(){	
		
			if(updateEmailStatus == true)//=====update email.
			{
				var user_entered_email	= $('#UserUpdateEmail').val();
				$.ajax({
					url:'update-useremail',
					type:'post',
					data:{emailId:user_entered_email},
					dataType:'json',
					success: function(json)
					{
						if(json['update_success'] == 1)
						{
							$('#success_reporter').text('Thanks Your email is updated successfully.').show();
							window.location.reload();
						}
						if(json['update_success'] == 0)
						{
							$('#error_reporter').text('Sorry somthing went wrong. Please retry.').show();
						}
					},
					error: function(data)
					{
						console.log(data);
					}
					
				});
			}
			if(updateMobileStatus == true)//=======update mobile.
			{
			var user_entered_mobno	= $('#userupdateMobile').val();
			$.ajax({
					url:'update-usermobile',
					type:'post',
					data:{mobno:user_entered_mobno},
					dataType:'json',
					success: function(json)
					{
						if(json['update_success'] == 1)
						{
							$('#success_reporter').text('Thanks Your mobile is updated successfully.').show();
							window.location.reload();
						}
						if(json['update_success'] == 0)
						{
							$('#error_reporter').text('Sorry somthing went wrong. Please retry.').show();
						}
					},
					error: function(data)
					{
						console.log(data);
					}
					
				});
			}
			
		});
	
//==========End  for EMAIL changes==============	
	});//=====close document.ready function.