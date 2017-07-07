$(document).ready(function () {
/* sticky header */
$(function(){
 var shrinkHeader = 24;
  $(window).scroll(function() {
    var scroll = getCurrentScroll();
      if ( scroll >= shrinkHeader ) {
           $('#header').addClass('sticky');
        }
        else {
            $('#header').removeClass('sticky');
        }
  });
function getCurrentScroll() {
    return window.pageYOffset || document.documentElement.scrollTop;
    }
});

/* mobile nav */

$('.nav_trigger').click(function () {
        //$('.overlay').addClass('show-menu');
        $('body').addClass('show-menu');
        $('.offcanvas_nav').addClass('in');
    });
	
  
    $('.offcanvas_nav .close_menu').click(function () {
        $('body').removeClass('show-menu');
		$('.offcanvas_nav').removeClass('in');
        //$('.overlay').removeClass('show-menu');
       
    });
	
	$('.login_signup_popup').click(function () {
		$('.offcanvas_nav').removeClass('in');
		$("#hidden").trigger("click");
	});
	
/* accordion main menu mobile  */

$('.acc_menu li a').click(function(ev) { 
$('.acc_menu li a').removeClass('active');
	if($(this).closest('li').hasClass('level-1')){
		$(this).closest('li').children().find('.sub_menu').slideUp();
	}
	if($(this).closest('li').closest('ul').hasClass('sub_menu')){
	$(this).closest('li').closest('ul').parentsUntil('ul[class="acc_menu"]').each(function(index, element) {
		$(element).children().find('li > a').first().addClass('active');
	});
	$(this).closest('li').closest('ul').find('li > a').removeClass('active');
	$(this).closest('li[class="level-1"]').find('a:first').addClass('active');
	}
	$(this).addClass('active');

	$('.offcanvas_nav .sub_menu').not($(this).parents('.sub_menu')).slideUp();
	$(this).next('.sub_menu').slideToggle();
	ev.stopPropagation(); 
});

/* less & more */

var expander = $('.expander')
    expanderContracted = document.querySelector('.expander-contracted');
    expanderContracted = $('.expander-contracted');

// check if there's overflowed text inside the expander
if( (expanderContracted.prop('offsetHeight') < expanderContracted.prop('scrollHeight')) || (expanderContracted.prop('offsetWidth') < expanderContracted.prop('scrollWidth'))) {
    expander.find('a.expander-expand-link').on('click', function (e) {
        e.preventDefault();
        this.expand = !this.expand;
        $(this).html(this.expand?"Less <i class='fa fa-chevron-up' aria-hidden='true'></i>":"More <i class='fa fa-chevron-down' aria-hidden='true'></i>");
        $(this).closest('.expander').find('.expander-contracted, .expander-expanded').toggleClass('expander-contracted expander-expanded');
    });
}
else {
    expander.find('a.expander-expand-link').hide(); // remove more link
}

/* filter expand on click */
$('.filter_btn').click(function(){
	$(".filter_outer").addClass("open");
});
$(document).on('click','.filter_outer > h3 .close_btn',function(){
	$(".filter_outer").removeClass("open");
});
/* my account sidebar nav in mobile */
function checkWidth() 
{
	var windowWidth = $(window).width(); 
    if (windowWidth <= 999) {
        $(".navouter").hide();
		$('.ac_navbar > h4').removeClass('active');
    } 
	else {
        $(".navouter").show();
		$('.ac_navbar > h4').removeClass('active');
    }
 }
 checkWidth();
$(window).resize(checkWidth); 
$('.ac_navbar > h4').click(function(){
	var that = $(this);
	var windowWidth = $(window).width();
	 if (windowWidth <= 999) {
		 //$(this).addClass('active');
		 $(".navouter").slideToggle('fast', function(){ 
        		that.toggleClass('active');
		 });
	 }else{
		 $(this).removeClass('active');
	 }
})
var _auto_play = 1 == 1;
/* owl-carousel */
$('.owl-carousel').owlCarousel({
    nav:true,
	navText: ['<i class="material-icons">navigate_before</i>','<i class="material-icons">navigate_next</i>'],
	slideBy : 1,
	loop : false,
    navSpeed : 5000,
    autoplay: _auto_play,
    autoPlaySpeed: 5000,
    slideSpeed : 200,
    paginationSpeed : 800,
    rewindSpeed : 5000, 
    autoplayHoverPause: true,
    autoPlayTimeout: 5000,
    /*responsiveBaseElement: $_this,
    responsiveRefreshRate: 1000,*/
    responsive:{ 0:{items:1},600:{items:3},1000:{items:4},1366:{items:4},1500:{items:6}
    }
})
if($(".owl-carousel").length < 3)
    $('.owl-prev,.owl-next').hide();    

 var slider = $('.main_slider');
var amountHeaderImages = slider.find('img').length;
 slider.owlCarousel({
    
    animateOut: 'fadeOut',
    animateIn: 'fadeIn',
    items:1,
	loop : (amountHeaderImages > 1)?true:false,
	nav:'true',
	autoplay:'true',
	autoplayTimeout:4000,
    singleItem: true
});

/* fancybox popup */
if($(".fancybox").length) {
$('.fancybox').fancybox();
}

$(".logsign").fancybox({
	padding : 0,
	minHeight :'auto'
	//closeBtn : false

});

$('.financial_signup').click(function(){
    $('.get_start').show();
    $('#user_section').hide();
    //$(this).fancybox({padding : 0,minHeight :'auto'});
});

$(".financial_signup").fancybox({
	padding : 0,
	minHeight :'auto'
	//closeBtn : false

});
/* accordion */
function close_accordion_section() {
		$('.accordion .accordion-section-title').removeClass('active');
		$('.accordion .accordion-section-content').slideUp(300).removeClass('open');
	}
	$('.accordion-section-title').click(function(e) {
		// Grab current anchor value
		var currentAttrValue = jQuery(this).attr('href');
		if($(e.target).is('.active')) {
			close_accordion_section();
		}else {
			close_accordion_section();
			// Add active class to section title
			$(this).addClass('active');
			// Open up the hidden content panel
			$('.accordion ' + currentAttrValue).slideDown(300).addClass('open'); 
		}
		e.preventDefault();
	});
$(".payinfo").not(":first").hide(); 
$(".pay_tabs li:first").addClass("active").show();  
$(".pay_tabs li").click(function() {
	$(".pay_tabs li.active").removeClass("active"); 
	$(this).addClass("active"); 
	$(".payinfo").hide();		
	$(jQuery('a',this).attr("href")).fadeIn('slow'); 
return false;
});
$(".cardinfo").not(":first").hide(); 
$(".cards_tab li:first").addClass("active").show();  
$(".cards_tab li").click(function() {
	$(".cards_tab li.active").removeClass("active"); 
	$(this).addClass("active"); 
	$(".cardinfo").hide();		
	$(jQuery('a',this).attr("href")).fadeIn('slow'); 
return false;
});
$(".debit_cardinfo").not(":first").hide(); 
$(".debit_cards_tab li:first").addClass("active").show();  
$(".debit_cards_tab li").click(function() {
	$(".debit_cards_tab li.active").removeClass("active"); 
	$(this).addClass("active"); 
	$(".debit_cardinfo").hide();		
	$(jQuery('a',this).attr("href")).fadeIn('slow'); 
return false;
});
$( ".edit_btn" ).on( "click", function() {
	$( this ).parents( ".form-group" ).addClass( "editing" );
});
$( ".cancel_btn" ).on( "click", function() {
	$( this ).parents( ".form-group" ).removeClass( "editing" );
});
/* range slider */
if($("#slider-snap").length) {
var min_price = $('.range_slider').attr('starts');
var max_price = $('.range_slider').attr('ends');
//alert(max_price);      
var handlesSlider = document.getElementById('slider-snap');
noUiSlider.create(handlesSlider, {
	start: [ parseInt(min_price), parseInt(max_price) ],
    connect: true,
	range: {
		'min': [ parseInt(min_price)],
		'max': [ parseInt(max_price) ]
	}
});

var snapValues = [
	document.getElementById('slider-snap-value-lower'),
	document.getElementById('slider-snap-value-upper')
];

handlesSlider.noUiSlider.on('update', function( values, handle ) {
    //alert(values[handle]);
	snapValues[handle].innerHTML = addCommas(parseInt(values[handle]));
});
}

/* cloudzoom */
if($('.cloudzoom').length>0){
CloudZoom.quickStart();
$('#detail-zoom').bind('click',function(){            // Bind a click event to a Cloud Zoom instance.
	var cloudZoom = $(this).data('CloudZoom');  // On click, get the Cloud Zoom object,
		cloudZoom.closeZoom();
		$.fancybox.open(cloudZoom.getGalleryList());// and pass Cloud Zoom's image list to Fancy Box.
		return false;
	});
}

function addCommas(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return '&#x20B9; '+x1 + x2;
} 
});
$("#userUpdateButton").click(function(){
		$.ajax({
			url: 'dashboard/Dashboard/editDashboardPersonalinfo',
			type: 'post',
			data:  $('#userPersonalInfo').serialize(),
			dataType: 'json',
			success: function(json)
			{
				if(json['success_msg'])
				{
					$("#SuccessMsg").show();					
					$("#SuccessMsg").text(json['success_msg']);
					window.location.reload();
				}
				else
				{
					$("#SuccessMsg").show();
					$("#SuccessMsg").text(json['success_msg']);
				}
			}
		});
});
$("#changePasswordButton").click(function(){ 
	//var oldpass = $("#oldPassword").val();
	$.ajax({
		url: 'dashboard/Dashboard/updateDashboardUserPassword',
		type: 'post',
		data: $('#changePasswordForm').serialize(),
		dataType: 'json',
		success:function(json)
		{
			//alert(json['msg']);
			if(json['pass_error'])
			{
				$("#pass_error_msg").fadeOut();
				$("#pass_error_msg").fadeIn();
				$("#pass_error_msg").text(json['pass_error']);
			}
			if(json['new_pass_error'])
			{	
				$("#pass_error_msg").fadeOut();
				$("#pass_error_msg").fadeIn();
				$("#pass_error_msg").text(json['new_pass_error']);
			}
			if(json['invalid_pss_msg'])
			{
				$("#pass_error_msg").fadeOut();
				$("#pass_error_msg").fadeIn();
				$("#pass_error_msg").text(json['invalid_pss_msg']);
			}
			if(json['pass_success_msg'])
			{
				$("#pass_error_msg").fadeOut();
				$("#pass_error_msg").fadeIn();
				$("#pass_error_msg").removeClass('password_error');
				$("#pass_error_msg").addClass('password_success');
				$("#pass_error_msg").text(json['pass_success_msg']);
				setTimeout(function(){
		               window.location.reload();
		            }, 1500);
			}
		},
		error: function(data)
      	{
        console.log(data); 
      	}
	});
});
$("#userAddressButton").click(function(){
	//var name = $("#userName").val();
	//alert(name);
	$.ajax({
	url: 'dashboard/Dashboard/addUserAddress/$1',
	type: 'post',
	data: $('#addressForm').serialize(),
	dataType:'json',
	success:function(json)
	{
		//alert(json['name_error']);
		if(json['name_error'])
		{
		$("#userNameError").text(json['name_error']);
		}
		if(json['pincode_error'])
		{
			$("#userPincodeError").text(json['pincode_error']);
		}
		if(json['address_error'])
		{
			$("#userAddressError").text(json['address_error']);
		}
		if(json['city_error'])
		{
			$("#userCityError").text(json['city_error']);
		}
		if(json['phone_error'])
		{
			$("#userPhoneError").text(json['phone_error']);
		}
		var mob = $("#userPhone").val();
		if(json['state_error'])
		{
			$("#userStateError").text(json['state_error']);
		}
		if(json['success_msg'])
		{
			$("#saveAddressSuccessMsg").show();
			$("#saveAddressSuccessMsg").text(json['success_msg']);
			setTimeout(function(){
				$("#saveAddressSuccessMsg").hide();
		            }, 2500);			
			$('.address_list').html(json['html']);
			$('#submit_new_address').hide(700);
        	$('form[id="addressForm"]')[0].reset();
		}
		if(json['success_error_msg'])
		{
			$("#add_error_msg").show();
			$("#add_error_msg").text(json['success_error_msg']);
			setTimeout(function(){
		               window.location.reload();
		            }, 1500);
		}
	},
	error: function(data)
	{
		alert('Error');
		console.log(data);
	}
	});
});

$(document).on('click', '.default_radio', function ()
    {
        $.ajax(
            {
                url     : "dashboard/Dashboard/makeDefaultAddress",
                type    : 'post',
                dataType: 'json',
                data    : {del_id: $(this).attr('id') },
                success : function(json) 
                {
                    if(json['success'])
                    {
                        $('.address_list').html(json['html']);
                        $('#submit_new_address').hide(700);
        				$('form[id="addressForm"]')[0].reset();
                    }
                },
                error: function(data)
                {
                    console.log(data);
                }
            });

    });

$(document).on('click', '.delete_del', function ()
    {
        $.ajax(
            {
                url     : "dashboard/Dashboard/deleteDeliveryAddress",
                type    : 'post',
                dataType: 'json',
                data    : {del_id: $(this).attr('id') },
                success : function(json) 
                {
                    if(json['success'])
                    {
                    	$("#add_error_msg").show();
						$("#add_error_msg").text(json['success_error_msg']);
						setTimeout(function(){
						$("#add_error_msg").hide();
				            }, 2500);
                        $('.address_list').html(json['html']);
                        $('#submit_new_address').hide(700);
        				$('form[id="addressForm"]')[0].reset();
                    }
                },
                error: function(data)
                {
                    console.log(data);
                }
            });

    });


$("#updateEmailMobile").click(function(){
	//alert('hiiiiii');
	var email = $("#userUpdateEmail").val();
	var mobile = $("#userupdateMobile").val();
	//alert(email);
	$.ajax({
		url: 'dashboard/Dashboard/updateUserEmailMobile',
		type: 'post',
		dataType: 'json',
		data: {emailData: email ,mobileData: mobile},
		success: function(json)
		{		
			//alert(json['email']);
			if(json['email_error'])
			{
				$("#userUpdateEmailError").text(json['email_error']);
			}
			if(json['mobile_error'])
			{
				$("#userUpdateMobileError").text(json['mobile_error']);
			}
		},
		error: function(data) 
		{
			alert('ERROR');
			console.log(data);
		}
	});
});
//========== Account Deactivation ====================
$("#deactivateAccountButton").click(function(){
	var per_password		=	$("#deactivate_account_password").val();
	var pass_status			= true;
	if(jQuery.trim(per_password) == "" || per_password == 0)
	{
		pass_status = false;
		//alert(pass_status);
     	$("#deactivate_account_password").closest('div').addClass('has-error');	
	}
	else
	{
		if($("#deactivate_account_password").closest('div').hasClass("has-error"))
      	{
         	$("#deactivate_account_password").closest('div').removeClass('has-error');
			//alert(pass_status);
	  	}
	}
//=======================for deactivate ===================
if(pass_status == true)
{
	$.ajax({
		url: 'dashboard/Dashboard/deactivateAccount',
		type: 'post',
		dataType: 'json',
		data:{prePassword:per_password},
		success: function(json)
		{
			//alert(json['pass']);
			if(json['success_msg'])
			{
				$("#deactivate_error_msg").hide();
				$("#deactivateSuccessMsg").show();
				$("#deactivateSuccessMsg").text(json['success_msg']);
				window.location.replace('home');
			}
			if(json['error_msg'])
			{
				$("#deactivateSuccessMsg").hide();
				$("#deactivate_error_msg").show();
				$("#deactivate_error_msg").text(json['error_msg']);
			}
			//$("#deactivate_error_msg").hide();
			//$("#deactivateSuccessMsg").hide();
		},
		error: function(data)
		{
			//alert('Error');
			console.log(data);
		}
	});
}
});

// Mobile Validation Function-------
	$(document).ready(function() {
    $("#userPincode,#userPhone").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
});

	//The click to hide function
    $(".accordion-wrap > h3").click(function() {
        if ($(this).hasClass("current") && $(this).next().queue().length === 0) {
            $(this).next().slideUp();
            $(this).removeClass("current");
        } else if (!$(this).hasClass("current") && $(this).next().queue().length === 0) {
           if(!$(this).parent().parent().hasClass('pane'))
				$('.accordion-wrap > h3[class="current"]').trigger('click');
		    $(this).next().slideDown();
            $(this).addClass("current");
			
        }
    });
	 $(".accordion-wrap > h3:first").trigger('click');
	 
$('#add_new_address').click(function(){

        $('#submit_new_address').fadeIn(1000);
    });
$('#cancel_button').click(function(){

        $('#submit_new_address').hide(700);
        $('form[id="addressForm"]')[0].reset();
    });