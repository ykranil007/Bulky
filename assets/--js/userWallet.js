

$('#add_gift_card').click(function(){
    $('.collapsible').show();
});

$('#cancel_gift_card').click(function(){
    $('.collapsible').hide();
});

$('#confirm_gift_card').click(function(){

	if($('#voucher_code').val() == '')
	{
		$('#voucher_id_error').text('Please Enter Voucher ID');
		return false;
	}
	else if($('#voucher_code').val().length < 15)
	{
		$('#voucher_id_error').text('Please Enter Valid Voucher ID');
		return false;
	}
	else
	{
		$('#voucher_id_error').text('');
	}

	if($('#voucher_pin').val() == '')
	{
		$('#voucher_pin_error').text('Please Enter Voucher Pin');
		return false;
	}
	else if($('#voucher_pin').val().length < 10)
	{
		$('#voucher_pin_error').text('PIN Must be 10 Digit!');
		return false;
	}
	else
	{
		$('#voucher_pin_error').text('');
	}

	$.ajax(
	{
		url		: "add-wallet-money",
		type	: 'post',
		data	: $('#load_wallet').serialize(),
		dataType: 'json',
		beforeSend : function() 
		{
			$("#confirm_gift_card").html("<img src='assets/images/load.gif' width='18' style='margin-top:8px; margin-right:4px;' align='left' /> Loading...");
		},
		success : function(json) 
		{ 
			$("#confirm_gift_card").text("CONFIRM");
			if(json['success'])
			{
				$('#voucher_pin_error').removeClass('validation_error');
				$('#voucher_pin_error').addClass('green_text');
				$('#voucher_pin_error').text(json['success']).show();				
				setTimeout(function(){
		               window.location.reload();
		            }, 1000);
			}
			if(json['expired'])
			{
				$('#voucher_pin_error').hide();
				$('#voucher_pin_error').text(json['expired']).show();
				setTimeout(function(){
		               window.location.reload();
		            }, 1000);
			}

			if(json['invalid'])
			{
				$('#voucher_pin_error').hide();
				$('#voucher_pin_error').text(json['invalid']).show();
				setTimeout(function(){
		               window.location.reload();
		            }, 1500);
			}

			 
		},
		error: function(data)
		{			
			console.log(data);
		}
	});
});