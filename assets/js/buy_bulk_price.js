
$(document).ready(function(){
    var category_id = window.location.href.split('/')[3];
    var sub_category_id = window.location.href.split('/')[4];
    var subtosub_category_id = window.location.href.split('/')[5];
    var product_url = window.location.href.split('/')[7]; // Local 7 Live 6
    var product_id = window.location.href.split('/')[8];  // Local 8 Live 7
    get_product_details(category_id,sub_category_id,subtosub_category_id,product_url,product_id);
});


function get_product_details(category_id,sub_category_id,subtosub_category_id,product_url,product_id)
{
    $.ajax({
       url: 'Product_details/get_product_details',
       type: 'POST',
       data: {cat_id:category_id,sub_cat_id:sub_category_id,subtosub_cat_id:subtosub_category_id,pro_url:product_url,pro_id:product_id},
       dataType: 'JSON',
       success: function(json)
       {
        	if(json)
        	{
        		/*$('.product_gallery').html(json.image_html);*/
                $(".product_detail").html(json.product_details);
                setTimeout(function(){ $(".similar_pro").html(json.similar_html); lazy_image_with_owl_carosal(); }, 1000);
                setTimeout(function(){ $(".recent_pro").html(json.recent_html); lazy_image_with_owl_carosal(); }, 1000);
        		
        	}
       },
       error: function(data)
       {
         console.log(data);
       }
    });
}

function lazy_image_with_owl_carosal()
{
    $('.fancybox').fancybox();
	$("img.lazy").lazyload({event : "sporty"});
	$(window).bind("load", function() {setTimeout(function() { $("img.lazy").trigger("sporty") }, 1000);});  
	$('.owl-carousel').owlCarousel({
	    nav:true,
		navText: ['<i class="material-icons">navigate_before</i>','<i class="material-icons">navigate_next</i>'],
		slideBy : 1,
		loop : false,
	    autoplay: true,
	    autoPlaySpeed: 1000,
	    autoPlayTimeout: 1000,
	    responsive:{ 0:{items:1},600:{items:3},1000:{items:4},1366:{items:4},1500:{items:6}
	    }
	})
}

$('body').on('click','#pro_size_name',function(){
	$("#pro_size_name.active").removeClass("active");
	$(this).addClass('active');	
	$('.add_to_cart').attr('sid',$(this).attr('value'));
	$('.buy_now').attr('sid',$(this).attr('value'));
});

$('body').on('click','.add_to_cart',function(){
	var pid = $(this).attr('pid');
	var sid = $(this).attr('sid');
	
	$.ajax({
		url:'add-to-cart',
		type: 'post',
		data: {product_id:pid,size_id:sid},
		dataType: 'json',
		success: function(json)
		{
			window.location.href = 'cart';
		},
		error: function(data)
		{
			console.log(data);
		}
	});
});


/*$('#bulk_price_btn').click(function(){
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

 });*/