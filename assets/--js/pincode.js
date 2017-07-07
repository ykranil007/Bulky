// Pincode Availability Checking 
$('#btn_pincode_availability').click(function(){

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




    