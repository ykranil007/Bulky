$('#product_search_btn').click(function(){
	$.ajax({
		url: 'search',
		type: 'post',
		data: $('#search_form').serialize(),
		dataType: 'json',
		success: function(json)
		{
		},
		error: function(data)
		{			
			console.log(data);
		}
	});
});