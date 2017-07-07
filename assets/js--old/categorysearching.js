 
 $(function() {
    $('.checkbox').on("change", ":checkbox", function () {
        if (this.checked) 
        {
        var value = this.value;        
        
        $.ajax({

	        	type: 'post',
	        	url: 'brand-filter',	        	
	        	data: {"value":value},
	        	dataType: 'json',
	        	success : function(data) {

	        		setTimeout(function(){
		               window.location.href = "womens-clothing";
		            }, 500);
	        	},
	        	error: function(data)
				{
					console.log(data);
				}

	        });
	        
    	}

    });
});

 
/*$.ajax({

	        	type: 'post',
	        	url: 'brand-filter',	        	
	        	data: {"brand":value},
	        	dataType: 'json',
	        	success : function(brand) {
	        		alert("sadsa");
	        	},
	        	error: function(data)
				{
					console.log(data);
				}

	        });*/