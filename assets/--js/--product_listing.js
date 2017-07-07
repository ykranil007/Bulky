jQuery(function($) {    
    function create_client_side_pagination(record_count, per_page, current_page)
    {
    	record_count 		= parseInt(record_count);
    	per_page	 		= parseInt(per_page);
    	current_page 		= parseInt(current_page);
    	var pagination_html = '';
    	var no_of_pages		= Math.ceil(record_count/per_page);
    	var page_end		= (current_page+5 > no_of_pages)?no_of_pages:Math.max((current_page+5), 10);
    		page_end		= (page_end > no_of_pages)?no_of_pages:page_end;
    	var page_start		= (page_end-9 <= 0)?1:(page_end-9);
    
    	if(record_count > per_page)
    	{
    		pagination_html = pagination_html='<ul class="pagination pull-right">';
    		if(current_page != 1)
    			pagination_html = pagination_html+'<li><a href="javascript:void(0);" page-value="'+(current_page-1)+'" class="page"><i class="fa fa-angle-double-left" aria-hidden="true"></i>Previous</a></li>';
    
    		for(var i=page_start;i<=page_end;i++)
    		{
    			if(i != current_page)
    				pagination_html = pagination_html+'<li><a href="javascript:void(0);" page-value="'+i+'" class="page">'+i+'</a></li>';
    			else
    				pagination_html = pagination_html+'<li class="active"><a href="javascript:void(0);" page-value="'+current_page+'" class="page">'+i+'</a></li>';
    		}
    
    		if(current_page != no_of_pages)
    			pagination_html = pagination_html+'<li><a href="javascript:void(0);" page-value="'+(current_page+1)+'" class="page">Next<i class="fa fa-angle-double-right" aria-hidden="true"></i></a></li>';
    		pagination_html = pagination_html+'</ul>';
    	}
    	return pagination_html;
    }
    function stop_loader(records_count,records_per_page,requested_page_no)
	{
		$('.pagination_container').html(create_client_side_pagination(records_count,records_per_page,requested_page_no));
	}

    function create_user_request()
    {
        var category           = [];
        var sub_category       = [];
        var sub_tosub_category = [];
        var brands             = [];
        var product_key_word   = '';
        var price              = [];
        var color              = [];
        var size               = [];
        var discount           = [];
        var sort_by            = '';
        
        $(".categories_nav > .selected > a").each(function (){category.push($(this).attr('id'));});
        $(".categories_nav > .selected > ul > li").each(function (){
            if($(this).hasClass( "active" ))
            {
                $( this ).children( 'a' ).attr('url');
                sub_category.push($( this ).children( 'a' ).attr('url'));
                return false;
            }
        });
        
        $('input[id^="subtosub-"]:checked').each(function (){sub_tosub_category.push($(this).attr('url'));});
        if (sub_tosub_category.length === 0 && $('#sub_tosub_category').val()!='') {sub_tosub_category.push($('#sub_tosub_category').val());}
            
        $('input[id^="brand-"]:checked').each(function (){ brands.push($(this).attr('url'));});
        $('input[id^="color-"]:checked').each(function (){ color.push($(this).attr('url'));});
        $('input[id^="size-"]:checked').each(function (){ size.push($(this).attr('id').substring(5));});
        $('input[id^="discount-"]:checked').each(function (){
            discount.push($(this).attr('url'));
            });
        
        if(window.location.search)
        {
            var query_string = window.location.search;
            if(get_parameter_by_name('search', query_string)!='' || get_parameter_by_name('search', query_string)!= null)
            {
                var keywords = get_parameter_by_name('keywords', query_string);
                $('#keywords').val(keywords.replace(/-/g , ' '));
                product_key_word = keywords;
            }
        }
        //sorting listing according to price and new product
        sort_by = $('#sort_by').val(); 
        var min_price = ($.trim($('#slider-snap-value-lower').text().replace(/,/g , ', '))== '')? $('#slider-snap-value-lower').attr('lower'):$.trim($('#slider-snap-value-lower').text().substr(1).replace(/,/g , ', '));
        var mix_price = ($.trim($('#slider-snap-value-upper').text().replace(/,/g , ', '))== '')? $('#slider-snap-value-upper').attr('upper'):$.trim($('#slider-snap-value-upper').text().substr(1).replace(/,/g , ', '));
        price.push((min_price));
        price.push((mix_price));
        
        var page_no	= $('.pagination > .active > a').attr('page-value');
        if(typeof page_no === "undefined"){page_no = 1;}       
        get_product_list(category, sub_category, sub_tosub_category, brands, product_key_word, price, color, size, discount, page_no, sort_by);
    }
    
    function get_product_list(category = '', sub_category = '', sub_tosub_category = '', brands = '', product_key_word = '', price = '', color = '', size = '', discount = '',page_no,sort_by) 
    {
         $.ajax({
            url:  'index.php/Product_listing/get_product_list_by_ajax',
            type: 'GET',
            dataType: "JSON",
            data: {
                category:category,
                sub_category:sub_category,
                sub_tosub_category:sub_tosub_category,
                brands:brands,
                product_key_word:product_key_word,
                price:price,
                color:color,
                size:size,
                discount:discount,
                page_no:page_no,
                sort_by:sort_by
            },
            beforeSend: function() { $('.product_list').html('<div class="loader"></div>');},
            success: function(json) 
            {            
                if (json.status == true) 
                { 
                   var total_products = (json.total_product > 1)?json.total_product+' Items':json.total_product+' Item';
                   if(json.product_key_word != '')
                   {
                      $('#type').html(json.product_key_word+' <span> '+total_products+'</span>');
                   }
                   else
                   {
                      $('#type').html(json.category+' '+json.sub_tosub_category+' <span> '+total_products+'</span>');
                   }
                     
                   $('.product_list').html(json.products_listing);
                   $('.brand_info').html(json.brand_info);
                   $('.color_info').html(json.color_info);
                   $('.size_info').html(json.size_info);
                   $('.discount_info').html(json.discount_info);
                   $('.sub_tosub_categorylist').html(json.sub_tosub_category_info);
                   
                   if(json.total_product > 0)
                   {
                      stop_loader(json.total_product,30,page_no);
                      $("html, body").animate({ scrollTop: 80 }, "slow");
                   }
                   else
                   {
                     $('.scroll_content').fadeOut('slow');                       
                   }          
                }
            },
            error: function(data) { console.log(data);}
        });
    }   
    
    var handlesSlider = document.getElementById('slider-snap');
    handlesSlider.noUiSlider.on('change', function(){create_default_page();create_user_request()});//----price slider function
    $('.sub_tosub_categorylist').click(function(){$("label > input[type='checkbox']").click(function (){create_default_page();create_user_request();});});
    $('.brand_info').click(function(){$(".checkbox > label > input[type='checkbox']").click(function (){create_default_page();create_user_request();});});
    $('.color_info').click(function(){$(".checkbox > label > input[type='checkbox']").click(function (){create_default_page();create_user_request();});});
    $('.size_info').click(function(){$(".checkbox > label > input[type='checkbox']").click(function (){create_default_page();create_user_request();});});
    $('.discount_info').click(function(){$(".checkbox > label > input[type='checkbox']").click(function (){create_default_page();create_user_request();});});
    /*$('.discount_info > .checkbox > label > input[type="checkbox"]').click(function(){
        //create_default_page();create_user_request();
        alert('call');
        
     });*/
    $('#sort_by').change(function(){create_default_page();create_user_request();})
    
    function get_parameter_by_name(name, url) 
    {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));    
    }
    
    function create_default_page()
    {
        $('.pagination').find('.active').removeClass();
        $('.pagination > li:first').addClass('active');
    }
    
    create_user_request();
     
    $(document).on('click','.quick-view',function(){
        var product_id = $( this ).parent().attr('style_id');
        $.get("Product_listing/create_quickview?product_id="+product_id,function(content)
        {
            $('#quick-demo').html(content);
            setTimeout(function(){ $.fancybox({'type': 'inline','href': '#quick-demo'}); }, 90);
        });
    });
    $(".material-icons").click(function(){$(this).parent().closest('div').find('.data_fliter_content').slideToggle();});      
});