$(document).ready(function(){
    get_products_by_ajax(1);
});

track_page = 1;
$(window).scroll(function() { //detect page scroll
    
    if($(window).scrollTop() == $(document).height() - $(window).height()){
       track_page ++;
       get_products_by_ajax(track_page)
    }
});


function get_products_by_ajax(value)
{
    $.ajax({
           url: 'Home/get_products',
           dataType: 'JSON',
           success: function(json)
           {
              if(value == 1)
              {
                $('.top_tranding_data').html(json.offer_html);
                $('.mens').html(json.mens_html);
                lazy_image_with_owl_carosal();
              }else if(value == 2)
              {
                $('.womens').html(json.womens_html);
                lazy_image_with_owl_carosal();
              }else if(value == 3)
              {
                $('.kids').html(json.kids_html);
                lazy_image_with_owl_carosal();
              }else if(value == 4)
              {
                $('.homedecor').html(json.home_html);
                lazy_image_with_owl_carosal();
              }else if(value == 5)
              {
                $('.recent').html(json.recent_html);
                lazy_image_with_owl_carosal();
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
  $("img.lazy").lazyload({event : "sporty"});
  $(window).bind("load", function() {setTimeout(function() { $("img.lazy").trigger("sporty") }, 1000);});  
  $('.owl-carousel').owlCarousel({
      nav:true,
    navText: ['<i class="material-icons">navigate_before</i>','<i class="material-icons">navigate_next</i>'],
    slideBy : 'page',
    loop : false,
      autoplay: true,
      autoPlaySpeed: 1000,
      autoPlayTimeout: 1000,
      responsive:{ 0:{items:1},600:{items:3},1000:{items:4},1366:{items:4},1500:{items:6}
      }
  })
}