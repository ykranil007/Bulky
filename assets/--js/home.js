$(document).ready(function(){ 
    var category_id = window.location.href.split('/')[4];
    get_products_by_ajax(category_id);
});


function get_products_by_ajax(category_id)
{
    $.ajax({
           url: 'get-all-products',
           type: 'POST',
           data: {category_id:category_id},
           dataType: 'JSON',
           success: function(json)
           {
                $('.products').html(json.html);            
           },
           error: function(data)
           {
                console.log(data);
           }
            
        });
}