
var track_page = 1;

$(document).ready(function(){ 
    var category_id = window.location.href.split('/')[4];
    if($('.no_more').attr('data-type') == 'false'){
      get_products_by_ajax(category_id,track_page,false);  
    }    
});

$(window).scroll(function() { //detect page scroll
    //var loading  = false; //prevents multiple loads
    if($(window).scrollTop() + $(window).height() >= $(document).height()) { //if user scrolled to bottom of the page
        track_page++; //page number increment
        var category_id = window.location.href.split('/')[4];        
        if($('.no_more').attr('data-type') == 'false'){
          get_products_by_ajax(category_id,track_page,false); //load content   
        }
    }
});

function get_products_by_ajax(category_id,track_page,loading)
{
  if(loading == false){
    loading = true;  //set loading flag on
    $.ajax({
           url: 'get-all-products',
           type: 'POST',
           data: {category_id:category_id,page_no:track_page},
           dataType: 'JSON',
           success: function(json)
           {  
              if(json['html'].length == 49)
              {
                $('.no_more').attr('data-type','true');
                $('.loader_slider').show();
                setTimeout(function(){$('.no_more').append(json['html']); $('.loader_slider').hide(); },200);                    
              }
              else
              {
                $('.loader_slider').hide();
                setTimeout(function(){$('.product_list').append(json['html']); $('#product_title').html(json['title']);  $('.loader_slider').hide(); },200);
              }
                       
           },
           error: function(data)
           {
                console.log(data);
           }            
      });
  }
}