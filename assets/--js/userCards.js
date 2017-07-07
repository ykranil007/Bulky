// JavaScript Document
$(document).ready(function(){
	$("#add_card_btn").click(function(){
		$("#add_card_btn").hide();
		$("#add_cards_div").slideDown('100')
	});
	
	//======for cancel button=============
	$("#cancel_add_card_btn").click(function(){
		$("#add_cards_div").slideUp('fast');
		$("#add_card_btn").show();
	});
	//=======End cancel button==============
//=========VALIDATE ADD CARED FIELDS =======================================
	$("#save_cards_details").on('click',function(){
		var form_status = true;
		
		$('.Required').each(function(index, element) {
    	if(jQuery.trim($(this).val()) == "" || jQuery.trim($(this).val()) == 0)
    	{ 
      		form_status = false;
     	 $(this).closest('div').addClass('has-error');
   		}
    	else
    	{
      	if($(this).closest('div').hasClass("has-error"))
      	{
         $(this).closest('div').removeClass('has-error');
	  	}
		}
		});
		if(form_status == true )//if1
		{	
			var card_status = true;
			$('#cardNo').validateCreditCard(function(result) {
			//console.log(result);
			var regex = new RegExp("[a-zA-Z ]+$"); // ^[a-zA-Z\s]+$ [a-zA-Z ]+[a-zA-Z]
			var name_on_card = $("#card_name").val();
			
			var currentYear 	= (new Date).getFullYear();
			var currentMonth 	= (new Date).getMonth() + 1;
			var entered_month	= $("#card_month").val();
			var entered_year	= $("#card_year").val();
			//var card_type_name 	= result.card_type.name;
			var card_valid		= result.valid;
			var card_no_length	= result.length_valid;
			var card_luhn 		= result.luhn_valid; 
			
			var card_no			= $('#cardNo').val();
			var card_label		= $('#card_label').val();
			 
			if(card_valid != true || card_no_length != true || card_luhn != true ) 
			{
				card_status = false;
				$('#cardNo').closest('div').addClass('has-error');
				$('#error_msg').show();
				$('#error_msg').text('Oops! You seem to have entered an incorrect card number.');
			}
			else if(!regex.test(name_on_card))
			{
				card_status = false;
				$('#card_name').closest('div').addClass('has-error');
				$('#error_msg').show();
				$('#error_msg').text('Name should contain only alphabets.');
			}
			else if(entered_month < currentMonth || entered_year < currentYear )
			{
				card_status = false;
				if(entered_month < currentMonth)
				{ $('#card_month').closest('div').addClass('has-error'); }
				else {$('#card_year').closest('div').addClass('has-error');} 
				$('#error_msg').show();
				$('#error_msg').text('Oops! Your card seems to have expired.');
			}
			else
			{
				$('#error_msg').hide();
			}
				//=============save card details by ajax====================
				if(card_status == true)
				{
					var card_type_name 	= result.card_type.name;
					$.ajax({
							url:'save-carddetails',
							type:'POST',
							dataType:"json",
							data: {card_no:card_no,card_holder_name:name_on_card,exp_month:entered_month,exp_year:entered_year,card_labels:card_label,card_type:card_type_name},
							success:function(json) 
							{
								$("#add_cards_div").hide();
								location.reload();
								//$("#subtosubcategory").html(json.html);
							},
							error:function(data)
							{
								console.log(data); 
							}
					});
				}
				//============end of save card details======================
        	});
		}//end if1
		
	});
	//=========End validate add card field=======
  //==================== start for remove card===============
  /*$("#romove_card_btn").on('click',function(){
	  alert('click');
	  //var card_id			= $("#romove_card_btn").attr('data-cardId');
	  //alert(card_id);
	});*/
	$('body').on('click','#romove_card_btn',function(){
		var card_id 		= $(this).attr('data-cardId');
		$.ajax({
				url:'delete-carddetails',
				type:'POST',
				dataType:"json",
				data:{cardId:card_id},
				success: function(json)
				{
					location.reload();
				},
				error: function(data)
				{
					console.log(data);
				}
		});
	});
  //===================End of reomove cards==================
	
	
	
});