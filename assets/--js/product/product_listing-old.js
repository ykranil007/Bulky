// JavaScript Document
//For pagination only =================================================================================== Start
$(document).ready(function()
{ 
	$('.fancybox').fancybox({
		helpers   : {
						overlay : {closeClick: false} // prevents closing when clicking OUTSIDE fancybox 
		  			}
	});
	var count_record = $('#hd_count_record').val();
	var per_page = 10;
	var html = create_pagination(count_record,per_page,1);
	$('.pagination_container').html(html);

	function start_loader()
	{
		//$('div[name="product_loader"]').fadeIn('fast');
		$('#dv_loader').show();
	}
	function stop_loader(records_count,records_per_page,requested_page_no)
	{ 
		//$('div[name="product_loader"]').fadeOut('fast');
		$('#dv_loader').hide();
		$('.pagination_container').html(create_pagination(records_count,records_per_page,requested_page_no));
	}
	$('body').on('click','.pagination_container > ul > li > a', function()
	{ 
		var page_no		= $(this).attr('page-value'); 
		//var sort_by	= $('#dd_sort_live_porduct').val();
		getProductListing(page_no);
	});
	
	function getProductListing(page_no=1)
	{ 
		$.ajax({
			url			: "dashboard/order/listing",
			type 		: 'post',
			data		: {page:page_no},
			dataType 	: 'JSON',
			beforeSend	: function()
			{
				$('#dummy').html('');
				start_loader();
			},
			success	: function(response) 
			{
				// creating pagination button
				$('#hd_count_record').val(response.count);
				var html = create_pagination(response.count,per_page,1);
				$('.pagination_container').html(html);
				
				if(response.count > 0)
				{ 
					$('#dummy').html(response.html);
					$('#dummy').append(' <div class="pagination_container pagination text-right"></div>');
					$('.pagination_container').html(create_pagination(response.count,per_page,page_no));
				}
				else
				{
				  $('#dummy').html(' <tr class="text-center text-danger"><td colspan="4">No Record Found</td></tr>');
				}

				stop_loader(response.count,per_page,page_no);
			},
			error: function(json)
			{
				console.log(json);
			}
		});
		return false;
	}

	
//For pagination only =============================================================================================== End
	//getProductListing();

});
