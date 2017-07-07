$(document).on('click', '.btn-remore', function (){
    var status = confirm("Are you sure you want to remove this record?");
    if(status==true)
    {
     var clickid = $(this).attr('id');
     $.ajax(
        {
            url     : "dashboard/Dashboard/deleteWishList",
            type    : 'post',
            dataType: 'json',
            data    : {wishlist_id: $(this).attr('id').substring(11)},
            success : function(json) 
            {
                if(json['success'])
                {
                    setTimeout(function(){
                       window.location.href = "user-wishlist";
                    }, 500);                   
                }
            },
            error: function(data)
            {
                console.log(data);
            }
        });
    }
});

$('body').on('click','.add-to-cart',function(){
    var pid = $(this).attr('pid');
    var sid = $(this).attr('sid');
    var wid = $(this).attr('wid');
    $.ajax({
        url:'add-to-cart',
        type: 'post',
        data: {product_id:pid,size_id:sid},
        dataType: 'json',
        success: function(json)
        {
            remove_wishlist_data(wid);
        },
        error: function(data)
        {
            console.log(data);
        }
    });
});

function remove_wishlist_data(wid)
{
    $.ajax({

        url     : "dashboard/Dashboard/deleteWishList",
        type    : 'post',
        dataType: 'json',
        data    : {wishlist_id:wid},
        success : function(json) 
        {
            setTimeout(function(){ window.location.href = 'cart'; }, 500);                               
        },
        error: function(data)
        {
            console.log(data);
        }
    });
}