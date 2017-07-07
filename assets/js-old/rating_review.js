 
$('body').on('mouseover','.ratestar > span',function(){
	
    var start_val = parseInt($(this).attr('star_value'));
    $('.green').hide(100);
    $('.ratestar > span').each(function(index,value){
        //alert(value.attr('star_value'));
        if(parseInt($(this).attr('star_value')) <= start_val )
        {
            $(this).addClass('active');
        }
        else
        {
            $(this).removeClass('active'); 
        }
    });        
});

$('body').on('click','.ratestar > span',function(){
    var start_val = parseInt($(this).attr('star_value'));
    save_product_rating(start_val);
});

function save_product_rating(rate_value)
{
    var pro_id = window.location.href.split('/')[6];
    var usr_id = window.location.href.split('/')[7];
    $('.green').hide(100);
    $.ajax({
        url: 'save_product_rating',
        type: 'post',
        data: {product_id:pro_id,user_id:usr_id,rate_value:rate_value},
        dataType: 'json',
        success: function(json)
        {                
            if(json['success'])
            {
                $('.green').show(100);                    
            }
        },
        error: function(data)
        {
            console.log(data);
        }
    });
}

$('body').on('click','.review_btn',function(){
	var pro_id = window.location.href.split('/')[6];
    var usr_id = window.location.href.split('/')[7];	
	var title = $('#review_title').val();
	var nick = $('#review_nick').val();
	var desc = $('#review_description').val();
	if($.trim(replace_string(nick)) == ''){
		$('#review_nick').addClass('has-error');
		return false;
	}else{
		$('#review_nick').removeClass('has-error');
	}
	if($.trim(replace_string(desc)) == ''){
		$('#review_description').addClass('has-error');
		return false;
	}else{
		$('#review_description').removeClass('has-error');
	}
	$.ajax({
		url:'save_product_review',
		type: 'post',
		data: {product_id:pro_id,user_id:usr_id,title:title,nick_name:nick,desc:desc},
		dataType: 'json',
		success: function(json)
		{
			if(json['success'])
			{
				$('.review_msg').text(json['success']);
				$('#review_title').val('');
				$('#review_nick').val('');
				$('#review_description').val('');
				setTimeout(function(){ window.location.href = 'show-buyer-orders' }, 3000);
			}
		},
		error: function(data)
		{
			console.log(data);
		}
	});
});


function replace_string(string) 
{
    return string.trim().replace(/["~!@#$%^&*'\(\)`{}\[\]\|\\:;'<>,.\/?"\t\r\n]+/g, '');
}