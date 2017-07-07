
var track_page = 1; //track user scroll as page number, right now page number is 1
//var loading  = false; //prevents multiple loads

$(document).ready(function(){

	if($('.no_more').attr('data-type') == "true")
            load_contents(track_page,false); //initital load content   

});

$(window).scroll(function() { //detect page scroll
    //var loading  = false; //prevents multiple loads
    if($(window).scrollTop() + $(window).height() >= $(document).height()) { //if user scrolled to bottom of the page
        track_page++; //page number increment
        if($('.no_more').attr('data-type') == "true")
            load_contents(track_page,false); //load content   
    }
});
     
//Ajax load function
function load_contents(track_page,loading){	
    if(loading == false){
        loading = true;  //set loading flag on
        //$('.loading-info').show(); //show loading animation
        $.ajax({
	    	url: 'orders',
	    	type: 'post',
	    	data: {page:track_page},
	    	dataType: 'json',
	    	success: function(json)
	    	{
	    		$('.left_sidebar').show();
	    		$('.loading-info').show();
                if(json['html'].length == "126")                
                    $('.no_more').attr('data-type','false');                    
                
                setTimeout(function(){$(".order_main").append(json['html']); $('.loading-info').hide(); if($(".fancybox").length) { $('.fancybox').fancybox();} },500);
	    	},
	    	error: function(data)
	    	{
	    		console.log(data);
	    	}
    	});
    }
}


$('body').on('click','.order_detail',function(){
		
        var id = $(this).attr('id');
        window.location.href = 'order-details?'+id;
    }); 