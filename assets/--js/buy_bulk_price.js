$('#bulk_price_btn').click(function(){
	$('.bulk_price_view').slideToggle(1000);	
});

$(document).on('click','.price_radio',function(){
	var encrpt_bulk_range = $(this).val();
	var encrpt_single_price = $(this).closest('label').find('input[name="single_price"]').val();
	$.ajax({
		url: 'bulk-price',
		type: 'post',
		data	: {bulk_range:encrpt_bulk_range,single_price:encrpt_single_price},
		dataType : 'json',
		success: function(json)
		{
			$('.select_qty').show(1000);
			$('#bulk_qty').attr('min', json['min_range']);
			$('#bulk_qty').attr('max', json['max_range']);
			$('#bulk_qty').val(json['min_range']);
			$('#final_price').fadeIn('slow');
			$('#final_price').html('<span> &#8377; '+ json['total_price']+'</span>');
			
		},
		error: function(data)
		{
			console.log(data);
		}

	});
});

 $('body').on('change','#bulk_qty',function(){
 	var bulk_qty = $(this).val();
 	var encrpt_bulk_range = $('input[name="price_radio"]:checked').val();
 	var encrpt_single_price = $('input[name="price_radio"]:checked').closest('label').find('input[name="single_price"]').val();

 	$.ajax({
		url: 'update-bulk-price',
		type: 'post',
		data	: {bulk_qty:bulk_qty,single_price:encrpt_single_price,bulk_range:encrpt_bulk_range},
		dataType : 'json',
		success: function(json)
		{
			if(json['total_price'])
			{
				$('#final_price').fadeIn('slow');
				$('#final_price').html('<span> &#8377; '+ json['total_price']+'</span>');
			}
			else
			{
				alert('please enter correct range value');
			}
			
		},
		error: function(data)
		{
			console.log(data);
		}

	});

 });