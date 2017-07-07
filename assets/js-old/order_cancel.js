
$(document).on('click', 'a[name="cancel_order_button"]', function() {
    var order_id = $(this).closest('div').find('input[name="cancelled_order_id"]').val();    
    get_order_products(order_id);

});

$(document).on('click', 'a[name="return_order_button"]', function() {
    var order_id = $(this).closest('div').find('input[name="return_order_id"]').val();
    get_order_products(order_id);

});

function get_order_products(order_id)
{
    
    $.ajax({
        url: 'get-cancel-order-list',
        type: 'post',
        data: {order_id:order_id},
        dataType: 'json',
        success: function(json)
        {
            if(json['html'])
            {
                $('.request_cancel').removeAttr('id');
                $('.request_cancel').attr('id', 'cancel_order_request');
                $('.request_cancel').html(json.html);
                $('#cancellation_popup').trigger('click');
            }
        },
        error: function(data)
        {           
            console.log(data);
        }
    });
}

$(document).on('click', 'a[name="confirm_cancellation"]', function () {

    var reason_id = $(this).closest('form').find('select[name="cancel_reason"] option:selected').val();
    var comment_txt = $(this).closest('form').find('textarea[name="cancel_comment"]').val();
    var product_id = $('input:checkbox[name=product_checkbox]').map(function() 
    {    
        if($(this).is(':checked'))
            return $(this).val();
    }).get();
    
    var master_order_id = $(this).closest('form').find('input[name="item_order_id"]').val();
    if(product_id == '')
    {
        //$('input:checkbox[name="product_checkbox"]').css('outline-color', 'red');
        alert('Select Product for Cancel');
        return false;
    }
    if(reason_id == '')
    {
        $('.reason_dropdown').css({'border':'1px solid red'});
        setTimeout(function(){ $('.reason_dropdown').css({'border':'1px solid black'}); }, 3000);
        return false;
    }
    $.ajax({
        url     : 'cancel-product',
        type    : 'post',
        data    : {reason:reason_id,comment:comment_txt,product_id:product_id,master_order_id:master_order_id},
        dataType: 'json',
        success : function(json)
        {
            if(json['cancel_success'])
            {
                $('#popup_confirm_cancellation').trigger("click");
                setTimeout(function(){ location.reload(); }, 1000);
            }
        },
        error: function(data)
        {           
            console.log(data);
        }
    });
});

$(document).on('click', 'a[name="confirm_return"]', function () {
    
    var reason_id = $(this).closest('form').find('select[name="return_reason"] option:selected').val();
    var comment_txt = $(this).closest('form').find('textarea[name="return_comment"]').val();
    var product_id = $('input:checkbox[name=product_checkbox]').map(function() 
    {    
        if($(this).is(':checked'))
            return $(this).val();
    }).get();
    var order_id = $(this).closest('form').find('input[name="item_order_id"]').val();
    if(product_id == '')
    {
        //$('input:checkbox[name="product_checkbox"]').css('outline-color', 'red');
        alert('Select Product for Return');
        return false;
    }
    if(reason_id == '')
    {
        $('.reason_dropdown').css({'border':'1px solid red'});
        setTimeout(function(){ $('.reason_dropdown').css({'border':'1px solid black'}); }, 3000);
        return false;
    }
    $.ajax({
        url     : 'return-product',
        type    : 'post',
        data    : {reason:reason_id,comment:comment_txt,product_id:product_id,order_id:order_id},
        dataType: 'json',
        success : function(json)
        {
            if(json['return_success'])
            {
                $('#popup_confirm_cancellation').trigger("click");
                setTimeout(function(){ location.reload(); }, 1000);
            }
        },
        error: function(data)
        {           
            console.log(data);
        }
    });
});