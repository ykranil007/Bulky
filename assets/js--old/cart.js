$(document).ready(function(){
	var link = "http://localhost/Bulk/Womens/productDetails/";
	$("div.wbox form").submit(function() {
			return false; // Stop the browser of loading the page defined in the form "action" parameter.
	});
});
$(document).on('click', '.buy_now', function (){    
     var pid = $(this).attr('id').substring(8);
     var url = "ProductCart/Products/"+pid;
     $.ajax(
        {
            url     : url,
            type    : 'post',
            dataType: 'json',
            data : {pid:pid},
            success : function(json) 
            {
                if(json['status'])
                {
                    window.location.href = "checkout";
                }
            },
            error: function(data)
            {
                console.log(data);
            }
        });    
    });
// Add To Wishlist
$(document).on('click', '.wishlist', function ()
{
     $.ajax(
        {
            url     : "ProductCart/Add_Wishlist",
            type    : 'post',
            dataType: 'json',
            data    : {product_id: $(this).attr('id').substring(13),row_id: $(this).attr('id').substring(15) },
            success : function(json) 
            {
                if(json['success'])
                {
                    setTimeout(function(){
                       window.location.href = "cart";
                    }, 500);                   
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
                if(json['cart_success'])
                {
                    setTimeout(function(){
                       window.location.href = "cart";
                    }, 300);                   
                }
            },
            error: function(data)
            {
                console.log(data);
            }
        });  
});
        //var pre_chaneg_item_quantity = $("#cart_saved_item_quantity").val();
        $('body').on('change','#cart_saved_item_quantity',function(){
            var after_chaneg_item_quantity  = $(this).val();
            var product_id                  = $(this).attr('data-cart_saved_item');
            $.ajax({
                    url: "update-quantity",
                    type: 'post',
                    dataType:'json',
                    data:{productid:product_id,quantity:after_chaneg_item_quantity},
                    success: function(json)
                    {
                        if(json['unvalid_qty'])
                        {
                            alert(json['unvalid_qty']);
                            $("#update_error_msg").show();
                            $("#update_error_msg").html(json['unvalid_qty']);
                            $("#dv_loader").hide();
                        }
                        if(json['out_stock'])
                        {
                            $('.out_of_stock_popup').show(1000);
                            setTimeout(function(){ 
                                $('.out_of_stock_popup').hide(1000);
                            },3000);
                            $("#dv_loader").hide();
                        }
                        if(json['success'])
                        {
                            location.reload();
                            $("#dv_loader").hide();
                        }
                        $("#dv_loader").hide();
                    },
                    error: function(data)
                    {
                        console.log(data);
                        //alert('hello');
                        $("#dv_loader").hide();
                    }
            });
        });
        $('body').on('change','input[name="session_cart"]',function(){
            $.ajax({
                url: 'ProductCart/update',
                type: 'post',
                dataType: 'json',
                data: {quantity:$(this).val(),rowid:$(this).attr('id')},
                success: function(json)
                {
                    if(json['out_stock'])
                    {
                        $('.out_of_stock_popup').show(1000);
                        setTimeout(function(){ 
                            $('.out_of_stock_popup').hide(1000);
                        },3000);
                    }
                    else
                    {
                        setTimeout(function(){
                            window.location.href = "cart";
                        }, 100);
                    }
                    
                },
                error: function(data)
                {
                    console.log(data);
                }
            });
        });
$(document).on('click', '.wish_link', function ()
{
     $.ajax(
        {
            url     : "ProductCart/Add_Wishlist",
            type    : 'post',
            dataType: 'json',
            data    : {product_id: $(this).attr('id').substring(13),row_id: $(this).attr('id').substring(15) },
            success : function(json) 
            {
                if(json['success'])
                {
                    $('.wishlist_path').show(500);
                    setTimeout(function(){
                        $('.wishlist_path').hide(500);
                    },3000);                 
                }
                if(json['login_url'])
                {
                    setTimeout(function(){
                       window.location.href = json['login_url'];
                    }, 100);
                }
            },
            error: function(data)
            {
                console.log(data);
            }
        });  
});