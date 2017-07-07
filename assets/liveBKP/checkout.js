// For login
$(document).ready(function() {
  	$('#wallet_radio_first').trigger('click');
});
$('#checkout_login_btn').click(function()
{	
	var login_mobile = $('#login_email').val();	
	if(!$.isNumeric(login_mobile))
	{
		if(login_mobile == '' || !isValidEmailAddress(login_mobile))
		{
			$("#login_email_error").fadeOut('slow');
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
	$.ajax(
	{
		url	    : "validate-checkout-login",
		type	: 'post',
		data	: {value:$('#login_email').val()},
		dataType: 'json',
		beforeSend : function() 
		{
			$("#checkout_login_btn").html("<img src='assets/images/load.gif' width='18' style='margin-top:12px; margin-right:4px;' align='left' /> Logging in...");
		},
		success : function(json) 
		{ 
			$("#checkout_login_btn").text("Continue");
			if(json['user_exist'])
			{			
				$('#login_failure').hide();
				$('#login_success').hide();
				$('#checkout_email').hide();
				$('#checkout_login_btn').hide();
				$('#checkout_password').fadeIn('slow');
				$('#checkout_signin_btn').fadeIn('slow');
			}
			else if(json['mobile_no_exist'])
			{
				if(json['success_msg'])
				{
					$('#login_failure').hide();
					$('#login_success').html(json['success_msg']).fadeIn(500);
					$('#checkout_email').hide();
					$('#checkout_login_btn').hide();
					$('#checkout_user_otp').fadeIn('slow');
					$('#checkout_verify_otp_btn').fadeIn('slow');
				}
				else
				{

					$('#login_success').hide();
					$('#login_failure').html(json['error_msg']).fadeIn(500);
				}
				
			}
			else if(json['email_no_exist'])
			{
				$('#login_failure').hide();
				$('#login_success').hide();
				$('#checkout_email').hide();
				$('#checkout_login_btn').hide();
				$('#checkout_user_mobile').fadeIn('slow');
				$('#checkout_otp_btn').fadeIn('slow');
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

$('#checkout_signin_btn').click(function(){
	
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
		url	    : "checkout-login",
		type	: 'post',
		data	: {value:$('#login_email').val(),password:$('#login_password').val()},
		dataType: 'json',
		beforeSend : function() 
		{
			$("#checkout_signin_btn").html("<img src='assets/images/load.gif' width='18' style='margin-top:12px; margin-right:4px;' align='left' /> Logging...");
		},
		success : function(json) 
		{ 
			$("#checkout_signin_btn").text("Sign In");
			if(json['success_msg'])
			{			
				$('#login_failure').hide();
				$('#login_success').fadeIn('slow');
				$('#login_success').text(json['success_msg']);
				setTimeout(function() {
					window.location.href = 'checkout';
				}, 700);		    							   ;
			}
			else
			{
				$('#login_success').hide();
				$('#login_failure').fadeIn('slow');
				$('#login_failure').text(json['error_msg']);
			}
		},
		error: function(data)
		{			
			console.log(data);
		}
	});
	return false;
});


$('#checkout_otp_btn').click(function(){
	
	var x = $('#login_mobile').val().startsWith(7);
	var y = $('#login_mobile').val().startsWith(8);
	var z = $('#login_mobile').val().startsWith(9);
	if($('#login_mobile').val() == '')
	{
		$("#login_mobile_error").fadeIn('slow');		
		return false;		
	}
	else
	{
		$("#login_mobile_error").fadeOut('slow');
	}
	if($('#login_mobile').val().length < 10)
	{
		$("#login_mobile_error").text('Mobile Must Be 10 Digit.').fadeIn('slow');
		return false;		
	}
	else
	{
		$("#login_mobile_error").fadeOut('slow');
	}
	if(x == false && y == false && z == false)
	{
		$("#login_mobile_error").text('Please Enter Valid Mobile.').fadeIn('slow');
		return false;		
	}

	$.ajax(
	{
		url	    : "register-checkout-user",
		type	: 'post',
		data	: {email:$('#login_email').val(),mobile:$('#login_mobile').val(),is_bulk_user:$('#is_bulk_user:checked').val()},
		dataType: 'json',
		beforeSend : function() 
		{
			$("#checkout_otp_btn").html("<img src='assets/images/load.gif' width='18' style='margin-top:12px; margin-right:4px;' align='left' /> Sending...");
		},
		success : function(json) 
		{ 
			$("#checkout_otp_btn").text("Send OTP");
			if(json['success_msg'])
			{			
				$('#login_failure').hide();
				$('#login_success').fadeIn('slow');
				$('#login_success').text(json['success_msg']);
				$('#checkout_user_mobile').hide();
				$('#checkout_otp_btn').hide();
				$('#checkout_user_otp').fadeIn('slow');
				$('#checkout_verify_otp_btn').fadeIn('slow');		    							   ;
			}
			else
			{
				$('#login_success').hide();
				$('#login_failure').fadeIn('slow');
				$('#login_failure').text(json['error_msg']);
			}
		},
		error: function(data)
		{			
			console.log(data);
		}
	});
	return false;
});

$('#checkout_verify_otp_btn').click(function(){
	
	if($('#login_otp').val() == '')
	{
		$("#login_otp_error").fadeIn('slow');		
		return false;		
	}
	else
	{
		$("#login_otp_error").fadeOut('slow');
	}
	if($('#login_otp').val().length < 4)
	{
		$("#login_otp_error").text('OTP Must Be 4 Digit.').fadeIn('slow');
		return false;		
	}
	else
	{
		$("#login_otp_error").fadeOut('slow');
	}

	$.ajax(
	{
		url	    : "verify-checkout-user",
		type	: 'post',
		data	: {otp:$('#login_otp').val()},
		dataType: 'json',
		beforeSend : function() 
		{
			$("#checkout_verify_otp_btn").html("<img src='assets/images/load.gif' width='18' style='margin-top:12px; margin-right:4px;' align='left' /> Verifying...");
		},
		success : function(json) 
		{ 
			$("#checkout_verify_otp_btn").text("Verify");
			if(json['success_msg'])
			{			
				$('#login_failure').hide();
				$('#login_success').fadeIn('slow');
				$('#login_success').text(json['success_msg']);
				$('#checkout_user_otp').hide();
				$('#checkout_verify_otp_btn').hide();
				$('#checkout_new_password_form').fadeIn('slow');
				$('#checkout_cnfm_password').fadeIn('slow');
				$('#checkout_register_btn').fadeIn('slow');
			}
			else
			{
				$('#login_success').hide();
				$('#login_failure').fadeIn('slow');
				$('#login_failure').text(json['error_msg']);
			}
		},
		error: function(data)
		{			
			console.log(data);
		}
	});
	return false;
});

$('#checkout_register_btn').click(function(){
	
	if($('#checkout_new_password').val() == '')
	{
		$('#login_success').hide();
		$("#checkout_new_password_error").fadeIn('slow');
		return false;	
	}
	else
	{
		$('#login_success').hide();
		$("#checkout_new_password_error").fadeOut('slow');
	}
	if($('#checkout_confirm_password').val() == '')
	{
		$('#login_success').hide();
		$("#checkout_cnfm_password_error").fadeIn('slow');
		return false;			
	}
	else
	{
		$('#login_success').hide();
		$("#checkout_cnfm_password_error").fadeOut('slow');
	}
	if($('#checkout_confirm_password').val() != $('#checkout_new_password').val())
	{
		$('#login_success').hide();
		$("#checkout_cnfm_password_error").text('Confirm password do not match.').fadeIn('slow');
		return false;				
	}
	else
	{
		$('#login_success').hide();
		$("#checkout_cnfm_password_error").fadeOut('slow');
	}
	$.ajax(
	{
		url	    : "update-checkout-user-password",
		type	: 'post',
		data	: {password:$('#checkout_new_password').val()},
		dataType: 'json',
		beforeSend : function() 
		{
			$("#checkout_register_btn").html("<img src='assets/images/load.gif' width='18' style='margin-top:12px; margin-right:4px;' align='left' /> Updating...");
		},
		success : function(json) 
		{ 
			$("#checkout_register_btn").text("Register");
			if(json['success_msg'])
			{			
				$('#login_failure').hide();
				$('#login_success').fadeIn('slow');
				$('#login_success').text(json['success_msg']);
				setTimeout(function() {
					window.location.href = 'checkout';
				}, 500);
			}
			else
			{
				$('#login_success').hide();
				$('#login_failure').fadeIn('slow');
				$('#login_failure').text(json['error_msg']);
			}
		},
		error: function(data)
		{			
			console.log(data);
		}
	});
	return false;
});


var delvry_address_id  = ''
$('.delvry_address_id').click(function()
{
    //$(this).siblings().removeClass('delvry_address')
    $('[class^="delvry_address_id"]').removeClass('delvry_address');
    $(this).addClass('delvry_address');
	delvry_address_id  = $(this).attr('id');
	$('#delivery_id').val(delvry_address_id);
	$('#cod_delivery_id').val(delvry_address_id);
	$.ajax(
	{
		url		: "delvry_ads",
		type	: 'post',
		data	: {'delvry_address_id':delvry_address_id},
		dataType: 'json',
		beforeSend : function() 
		{
			$("#delvry_ads_btn").html("<img src='assets/images/load.gif' width='18' style='margin-top:12px; margin-right:4px;' align='left' /> Logging in...");
		},
		success : function(json) 
		{ 
			$("#delvry_ads_btn").text("Continue");
			if(json['pincode_status'])
			{
				
				$('#'+delvry_address_id).css("background-color","#D50000");
				
				$('#parent_'+delvry_address_id).css({
                                 'border': ' 1px solid red',        
                                  });
				setTimeout(function(){ 
					$('#'+delvry_address_id).css("background-color","#e0e0e0");
					$('#parent_'+delvry_address_id).css({
                                 'border': '',        
                                  });
					$('.alert').hide('slow');
                                   }, 4000);

				$('.alert').show(1000);
			}
			else
			{
				tag = '<i class="material-icons">done</i> Delivery Address - &nbsp<h5>'+json['name']+'</h5>&nbsp<span>'+json['mobile']+'</span><p>'+json['address']+',&nbsp'+json['city']+',&nbsp'+json['state']+' - '+json['pincode']+'</p>';
				$('#dlvry_ads').hide(500);
				$('#addrs_id').fadeIn(500);
				$('#change_address_btn').fadeIn(700);
				$('#checkoutaddrsid').html(tag);
				$('#checkout_address_data').hide(700);										
				$('#checkout_order_data').fadeIn(700);
				$('#checkout_mobile').val(json['mobile']).prop('readonly',true);
				$('#value_capping').val(json['cod_restrict']);
				value_capping(json['cod_restrict']);
			}							
		},
		error: function(data)
		{			
			console.log(data);
		}
	});
	return false;
});
$('#change_login_btn').click(function(){
		$('#checkout_login_data').hide(500);
		$('#checkout_address_data').hide(500);
		$('#checkout_order_data').hide(500);
		$('#payment_data').hide(500);
		$('#change_address_btn').hide(500);
		$('#review_order_btn').hide(500);
		$('#checkout_logged_in_data').show(500);	
});
$('#logged_in_btn').click(function(){
	$('#checkout_logged_in_data').hide(500);
	$('#change_address_btn').hide(500);			
	$('#checkout_address_data').show(500);
});
$('#change_ads_btn').click(function(){
	$('#checkout_login_data').hide(500);
	$('#checkout_logged_in_data').hide(500);
	$('#review_order_btn').fadeOut(300);
	$('#checkout_order_data').hide(500);
	$('#payment_data').hide(500);
	$('#change_address_btn').fadeOut(500);		
	$('#checkout_address_data').show(500);		
});
$('#checkout_order_btn').click(function(){
	
    $('#codtab').show();
	$('#onpaytab').show();
	$('#onpaytab').trigger('click');
	$('#checkout_order_data').hide(500);	
	$('#change_btn').fadeIn(500);
	$('#payment_data').fadeIn(500);
	$('#undone_summary').fadeOut(500);
	$('#review_order_btn').fadeIn(500);
	$('#done_summary').fadeIn(500);    
});
$('#review_order_btn').click(function(){
	$('#checkout_order_data').show(500);
	$('#change_btn').fadeOut(500);
	$('#payment_data').hide(500);
	$('#undone_summary').fadeIn(500);
	$('#done_summary').hide(500);
});
$('#checkout_mobile_btn').click(function()
{	
	//alert();
	/*var x = $('#checkout_mobile').val().startsWith(7);
	var y = $('#checkout_mobile').val().startsWith(8);
	var z = $('#checkout_mobile').val().startsWith(9);
	if($('#checkout_mobile').val() == '')
	{
		$("#checkout_mobile_error").text('Please Enter Mobile No.').fadeIn('slow');		
		return false;		
	}
	else
	{
		$("#checkout_mobile_error").fadeOut('slow');
	}
	if($('#checkout_mobile').val().length < 10)
	{
		$("#checkout_mobile_error").text('Mobile Must Be 10 Digit.').fadeIn('slow');
		return false;		
	}
	else
	{
		$("#checkout_mobile_error").fadeOut('slow');
	}
	if(x == false && y == false && z == false)
	{
		$("#checkout_mobile_error").text('Please Enter Valid Mobile.').fadeIn('slow');
		return false;		
	}*/
	/*if($('#bulk_user_pan').val() == '')
	{
		$('.verify_pan_text').hide(1000);
		$('.verify_pan_text').css({ 'color':'red'});
		$('.verify_pan_text').show(1000);
		return false;
	}
	if($('#bulk_user_pan').val() != '')
	{
		var ObjVal = $('#bulk_user_pan').val();
		var pancardPattern = /^([a-zA-Z]{5})(\d{4})([a-zA-Z]{1})$/;
		var patternArray = ObjVal.match(pancardPattern);
		if (patternArray == null) 
		{
			$('.verify_pan_text').hide(1000);
			$('.verify_pan_text').css({ 'color':'red'});
			$('.verify_pan_text').text('Ohh Shit! You Entered In-Valid Pan Card No.').show(1000);
			return false;
		}
		$('.verify_pan_text').hide(500);
	}*/
	$.ajax(
	{
		url		: "validate-order",
		type	: 'post',
		data	: $('#order_otp').serialize(),
		dataType: 'json',
		beforeSend : function() 
		{
			$("#checkout_mobile_btn").html("<img src='assets/images/load.gif' width='18' style='margin-top:12px; margin-right:4px;' align='left' /> Sending...");
		},
		success : function(json) 
		{ 
			$("#checkout_mobile_btn").text("Send OTP");			 			
			if(json['otpsuccess'])
			{
				$('#register_success').text(json['otpsuccess']);
				$('.order_otp').hide(1000);								
				$('.verify_order_otp').fadeIn(1000);
			}
			else
			{
				$('#register_error').text(json['otpfailed']);
			}						
		},
		error: function(data)
		{			
			console.log(data);
		}
	});
	return false;
});
$('#verify_order_btn').click(function()
{	
	if($('#order_otp_number').val() == '')
	{
		$('#register_success').hide(1000);
		$('#register_error').hide(1000);
		$("#checkout_otp_error").text('Please Enter OTP No.').fadeIn('slow');
		return false;		
	}
	else
	{
		$("#checkout_otp_error").fadeOut('slow');
	}
	if($('#order_otp_number').val().length < 4 )
	{
		$('#register_error').hide(1000);
		$("#checkout_otp_error").text('OTP No Must be 4 Digit.').fadeIn('slow');
		return false;		
	}
	$.ajax(
	{
		url		: "verify-order",
		type	: 'post',
		data	: $('#verify_order_otp').serialize(),
		dataType: 'json',
		beforeSend : function() 
		{
			$("#confirm_order_btn").html("<img src='assets/images/load.gif' width='18' style='margin-top:12px; margin-right:4px;' align='left' /> Please Wait...");
		},
		success : function(json) 
		{ 
			$("#confirm_order_btn").text("Confirm Order");			 			
			if(json['success'])
			{
				$('#register_error').hide(1000);
				$('#register_success').show(1000);
				$('#register_success').text(json['success']);
				$('.verify_order_otp').hide(1000);								
				$('#place_order').fadeIn(1000);
			}
			if(json['failed'])
			{
				$('#register_success').hide(1000);
				$("#checkout_otp_error").hide();
				$('#register_error').show(1000);
				$('#register_error').text(json['failed']);
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
    $("#checkout_mobile,#delivery_mobile,#order_otp_number,#login_mobile,#login_otp,#wallet_order_otp_number").keydown(function (e) {
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
$("#login_email").keyup(function(event){
        if(event.keyCode == 13){
           $("#checkout_login_btn").trigger("click");
           }
    });
$("#login_password").keyup(function(event){
        if(event.keyCode == 13){
           $("#checkout_signin_btn").trigger("click");
           }
    });
$("#login_mobile").keyup(function(event){
        if(event.keyCode == 13){
           $("#checkout_otp_btn").trigger("click");
           }
    });
$("#login_otp").keyup(function(event){
        if(event.keyCode == 13){
           $("#checkout_verify_otp_btn").trigger("click");
           }
    });
$("#checkout_confirm_password").keyup(function(event){
        if(event.keyCode == 13){
           $("#checkout_register_btn").trigger("click");
           }
    });
$('#pincodee').focusout(function() {
	//alert(this.value);
	$.ajax(
	{
		url		: "pincode-details",
		type	: 'get',
		data 	: {'pincode':this.value},
		success : function(json)
		{
			var jsonobj = jQuery.parseJSON(json);
			$("#state_name").val(jsonobj.obj.state)
            $("#city_name").val(jsonobj.obj.city)
		},
		error: function(data)
		{			
			console.log(data);
		}
	});
});

$('.wallet_radio').click(function(){
	var wlt_price = parseInt($('#wallet_price').val());
	var tot_price = parseInt($('#total_price').val());
    var delivery_charge = parseInt($('#delivery_charge').val());
    var fnl_price = (tot_price + delivery_charge);
    var save_price = (fnl_price - wlt_price);
	if(this.value == 'no')
	{
		$('#cnfrm_order_btn').hide();
		$('#wallet_pay_btn').show();
		$('#wallet_pay_btn').html('Pay '+fnl_price);
		$('#pro_final_price').html(fnl_price +'/-');
		$('#codtab').show();
		$('#onpaytab').show();
	}
	else if(this.value == 'yes')
	{
		if(save_price > 0)
		{			
			$('#wallet_pay_btn').html('Pay '+save_price);
			$('#pro_final_price').html(save_price +'/-');
			$('#codtab').hide();
			$('#onpaytab').hide();
				
		}
		else
		{
			$('#wallet_radio_status').val($('input[name=radio]:checked').val());
			$('#wallet_pay_btn').html('Confirm Order');
			$('#pro_final_price').html(' 0 /-');
			$('#codtab').hide();
			$('#onpaytab').hide();
		}		
	}
});

$( "#rzp-button1,#wallet_pay_btn" ).click(function() {
	var is_wallet = '';  
	if($('input[name=radio]:checked').is(':checked'))
	{
		is_wallet =  $('input[name=radio]:checked').val();    
	}
	else
	{
		is_wallet = 'no';    
	}
	/*if($('#bulk_user_pan').val() == '')
	{
		$('.verify_pan_text').hide(1000);
		$('.verify_pan_text').css({ 'color':'red'});
		$('.verify_pan_text').show(1000);
		return false;
	}
	if($('#bulk_user_pan').val() != '')
	{
		var ObjVal = $('#bulk_user_pan').val();
		var pancardPattern = /^([a-zA-Z]{5})(\d{4})([a-zA-Z]{1})$/;
		var patternArray = ObjVal.match(pancardPattern);
		if (patternArray == null) 
		{
			$('.verify_pan_text').hide(1000);
			$('.verify_pan_text').css({ 'color':'red'});
			$('.verify_pan_text').text('Ohh Shit! You Entered In-Valid Pan Card No.').show(1000);
			return false;
		}
		$('.verify_pan_text').hide(500);
	}*/
  $.ajax({

  		url: 'Checkout/getRazorKey',
  		type: 'POST',
  		data: {user_id:$('#user_id').val(),is_wallet:is_wallet,payment_mode:'prepaid',delivery_id:$('#delivery_id').val()},
  		dataType: 'json',
  		success: function(json)
  		{
  			if(json.amount > 0){
  				razorpay_api(json.razor_key,json.user_info,json.amount);
  			}
  			else{
  				verify_order_with_wallet(json.user_mobile);
  			}
  		},
  		error: function(data)
		{			
			console.log(data);
		}

  });
 
});

function razorpay_api(key,user_info,amount)
{
	  var wallet_status = '';  
	  if($('input[name=radio]:checked').is(':checked'))
	  {
	    wallet_status =  $('input[name=radio]:checked').val();
	  }
	  else
	  {
	    wallet_status = 'no';
	  }
	  var delivery_id = $('.delvry_address').attr('id');
	  var options = {
	    "key": key,
	    "amount": amount*100, // 2000 paise = INR 20
	    "name": 'BulknMore',//user_info.first_name,
	    "description": " ",
	    //"image": "vk.jpg",
	    "handler": function (response){
	        //console.log(response.razorpay_payment_id);
	        //alert(delivery_id);
	        $.ajax(
			{
				url		: "Checkout/online_banking",
				type	: 'POST',
				data	: {razorpay_payment_id:response.razorpay_payment_id,delivery_id:delivery_id,wallet_status:wallet_status},
				dataType: 'json',
				beforeSend : function() 
				{
					$("#confirm_order_btn").html("<img src='assets/images/load.gif' width='18' style='margin-top:12px; margin-right:4px;' align='left' /> Please Wait...");
				},
				success : function(json) 
				{ 
                    window.location.href = json.url; 			 									
				},
				error: function(data)
				{			
					console.log(data);
				}
			});
	        
	    },
        "modal": { 
            "ondismiss": function(response){
                $.ajax(
                		{
                			url		: "Checkout/online_banking_faliure",
                			type	: 'POST',
                			data	: {response:response, amount:(amount*100)},
                			dataType: 'json',
                			success : function(json) 
                			{
                			    console.log(json);
                	            window.location.reload(); 			 									
                			},
                			error: function(data)
                			{			
                				console.log(data);
                			}
                		}
                  );
            }
        },
	    "prefill": {
	        "name": user_info.first_name,
	        "contact": user_info.mobile,
	        "email": user_info.email
	    },
	    "notes": {
	        "address": ""
	    },
	    "theme": {
	        "color": "#ff5722"
	    }
	};
	var rzp1 = new Razorpay(options);
    rzp1.open();
}

function verify_order_with_wallet(user_info)
{
	/*$('#wallet_pay_btn').click(function(){*/
		$.ajax(
		{
			url		: "validate-order",
			type	: 'post',
			data	: {checkout_mobile:user_info},
			dataType: 'json',
			beforeSend : function() 
			{
				$("#checkout_mobile_btn").html("<img src='assets/images/load.gif' width='18' style='margin-top:4px; margin-right:4px;' align='left' /> Sending...");
			},
			success : function(json) 
			{ 
				$("#checkout_mobile_btn").text("Send OTP");			 			
				if(json['otpsuccess'])
				{
					$('#otp_success').text(json['otpsuccess']);
					$('.bulk_wallet').hide(1000);								
					$('.verify_wallet_otp').fadeIn(1000);
				}
				else
				{
					$('#otp_error').text(json['otpfailed']);
				}						
			},
			error: function(data)
			{			
				console.log(data);
			}
		});
	/*});*/
}

$('#verify_wallet_otp_btn').click(function()
{	
	if($('#wallet_order_otp_number').val() == '')
	{
		$('#otp_success').hide(1000);
		$('#otp_error').hide(1000);
		$("#wallet_otp_error").text('Please Enter OTP No.').fadeIn('slow');
		return false;		
	}
	else
	{
		$("#wallet_otp_error").fadeOut('slow');
	}
	if($('#wallet_order_otp_number').val().length < 4 )
	{
		$('#otp_error').hide(1000);
		$("#wallet_otp_error").text('OTP No Must be 4 Digit.').fadeIn('slow');
		return false;		
	}
	$.ajax(
	{
		url		: "verify-order",
		type	: 'post',
		data	: $('#verify_wallet_otp').serialize(),
		dataType: 'json',
		beforeSend : function() 
		{
			$("#verify_wallet_otp_btn").html("<img src='assets/images/load.gif' width='18' style='margin-top:12px; margin-right:4px;' align='left' /> Please Wait...");
		},
		success : function(json) 
		{ 
			$("#verify_wallet_otp_btn").text("Confirm Order");			 			
			if(json['success'])
			{
				$('#otp_error').hide(1000);
				$('#otp_success').show(1000);
				$('#otp_success').text(json['success']);
				$('.verify_wallet_otp').hide(1000);								
				$('#place_wallet_order').fadeIn(1000);
			}
			if(json['failed'])
			{
				$('#otp_success').hide(1000);
				$("#wallet_otp_error").hide();
				$('#otp_error').hide(1000);
				$('#otp_error').show(1000);
				$('#otp_error').text(json['failed']);
			}						
		},
		error: function(data)
		{			
			console.log(data);
		}
	});
	return false;
});

$("#wallet_order_otp_number").keyup(function(event){
        if(event.keyCode == 13){
           $("#verify_wallet_otp_btn").trigger("click");
           }
    });

$("#checkout_mobile").keyup(function(event){
        if(event.keyCode == 13){
           $("#checkout_mobile_btn").trigger("click");
           }
    });
$("#order_otp_number").keyup(function(event){
        if(event.keyCode == 13){
           $("#verify_order_btn").trigger("click");
           }
    });

/*$('#onpaytab').click(function(){	
	get_logistic_charge($('#total_price').val(),'prepaid',$('#delivery_id').val());
});

$('#codtab').click(function(){	
	get_logistic_charge($('#total_price').val(),'cod',$('#delivery_id').val());
});

function get_logistic_charge(cart_total,payment_mode,delivery_id)
{
	$.ajax({
		url : 'Checkout/get_logistic_charge',
		type : 'post',
		data	: {cart_total:cart_total,payment_mode:payment_mode,delivery_id:delivery_id},
		dataType: 'json',
		success : function(json) 
		{
			$('#cod_charge').hide();
			$('#total_cod_charge').hide();
			$('#total_price').val(json['total_price']);			
			$('#pro_total_price').html('&#8377; '+ json['total_price']);
			$('#shipping_charge').html('&#8377; '+ json['shipping_charge']);
			$('#delivery_charge').val(json['shipping_charge']);
			$('#pro_final_price').html('&#8377; '+ json['total_payable']);
			if(json['cod_charge'])
			{
				$('#cod_charge').show();
				$('#total_cod_charge').show();
				$('#total_cod_charge').html('&#8377; '+ json['cod_charge']);
				$('#pro_final_price').html('&#8377; '+ (parseInt(json['total_payable']) + parseInt(json['cod_charge'])));
			}
		},
		error: function(data)
		{			
			console.log(data);
		}

	});
}*/

function value_capping(amount) // For Checking Restricted Pincode Amount > 5000 By Delivery Logistic 
{
	if(amount == 5000)
	{
		$('#codtab').hide();
		$('#cod_alert').show();
	}	
}

/*function bulk_user_pan(total_amount) // For Checking Bulk User Amount > 50000 Asking Pan No 
{
	if(total_amount >= 50000)
	{
		$('.verify_form').show();
	}
}*/

$("#resend_checkout_otp").click(function() {
		$.ajax({
			url: "resend-otp",
			dataType : 'json',		
			success : function(json) {
				// Checking Required fields ----------------------------------------------------------------------			
				if(json['success'])
				{
					$("#login_failure").hide();
					$("#login_success").hide();
					$("#login_success").fadeIn('slow');
					$("#login_success").html(json['success']);
				}
				if(json['failed']) 
				{ 
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

$(document).on('click', '.remove', function ()
    {
         $.ajax(
            {
                url     : "ProductCart/removeCartData",
                type    : 'post',
                dataType: 'json',
                data    : {product_id: $(this).attr('id') },
                success : function(json) 
                {
                    if(json['success'])
                    {
                        setTimeout(function(){
                           window.location.href = "cart";
                        }, 500);                   
                    }
                    if(json['have_success'])
                    {
                        setTimeout(function(){
                           window.location.href = "checkout";
                        }, 500);
                    }
                },
                error: function(data)
                {
                    console.log(data);
                }
            });  
    });