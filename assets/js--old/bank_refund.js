$('#add_refund_bank_details').click(function(){
	$('#refund_details').toggle();
	});
var null_status = true;
$('#save_bank_details').click(function(){
		$('.Required').each(function(index, element) {
			if(jQuery.trim($(this).val()) == "" || jQuery.trim($(this).val()) == 0)
			{ 
				null_status = false;
				$(this).closest('div').addClass('has-error');
				$('#ifscdetails').show();
				
			}
			else
        	{
              	if($(this).closest('div').hasClass("has-error"))
              	{
                    $(this).closest('div').removeClass('has-error');
                    $('#ifscdetails').hide();
        	  	}
    		}
		})
		/*-------ifsc code validation */
		if(jQuery.trim($('#ifsccode').val()).length != 11)
			{
				$('#ifsccode').closest('div').addClass('has-error');
				$('#ifscdetails').show();
				null_status = false;
				return false;
			}
		else if(!jQuery.trim($('#ifsccode').val()).match(/^([a-zA-Z0-9]+)$/))
			{
				$('#ifsccode').closest('div').addClass('has-error');
				$('#ifscdetails').show();
				null_status = false;
				return false;
			}
			else
			{
				$('#ifscdetails').hide();
				$('#ifsccode').closest('div').removeClass('has-error');
			}
		/*-------end ifsc code validation ----*/
		/*----------for confirm account no ---*/
		var accountno = $('#account_number').val();
		var confirm_account_no = $('#confirm_accountnumber').val();
		if(jQuery.trim(accountno) != jQuery.trim(confirm_account_no))
		{
			$('#confirm_accountnumber').closest('div').addClass('has-error');
			$('#recnfrm').show();
			null_status = false;
			return false;
		}
		else
		{
			$('#confirm_accountnumber').closest('div').removeClass('has-error');
			$('#recnfrm').hide();
		}
		/*------------end confirm account no ----*/
        
	if(null_status == true )
	{
		$('#form_save_bank_details').submit();
	}
	 
});
	
	
	/*-------------for shoe error detail on red mrk ---------*/
	$("#ifscdetails").hover(function() {
        $(this).css('cursor','pointer').attr('title', 'Enter 11 digits valid IFSC code.');
    }, function() {
        $(this).css('cursor','auto');
    });
	
	$("#recnfrm").hover(function() {
        $(this).css('cursor','pointer').attr('title', 'Enter same account number as entered above.');
    }, function() {
        $(this).css('cursor','auto');
    });
    
$(document).on('click', '.delete_account', function () {
    $.ajax({
       url: 'delete-bank-account',
       type: 'POST',
       data: {bank_id:$(this).attr('id')},
       dataType: 'json',
       success: function(json)
       {
            if(json['status'] == 1)
            {
                location.reload();
            }                
       },
       error: function(data)
       {           
           console.log(data);
       }
    });
});