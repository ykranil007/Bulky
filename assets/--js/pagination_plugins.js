// JavaScript Document

function create_pagination(record_count,per_page,current_page)
{ 
	record_count 		= parseInt(record_count);
	per_page	 		= parseInt(per_page);
	current_page 		= parseInt(current_page);
	var pagination_html = '';
	var no_of_pages		= Math.ceil(record_count/per_page);
	var page_end		= (current_page+5 > no_of_pages)?no_of_pages:Math.max((current_page+5), 10);
		page_end		= (page_end > no_of_pages)?no_of_pages:page_end;
	var page_start		= (page_end-9 <= 0)?1:(page_end-9);

	if(record_count > per_page)
	{
		pagination_html = pagination_html='<ul class="pagination">';

		if(current_page != 1)
			pagination_html = pagination_html+'<li><a href="javascript:void(0);" page-value="'+(current_page-1)+'" class="page"><i class="fa fa-angle-double-left" aria-hidden="true"></i>Previous</a></li>';

		for(var i=page_start;i<=page_end;i++)
		{
			if(i != current_page)
				pagination_html = pagination_html+'<li><a href="javascript:void(0);" page-value="'+i+'" class="page">'+i+'</a></li>';
			else
				pagination_html = pagination_html+'<li class="active"><a href="javascript:void(0);" page-value="'+current_page+'" class="page">'+i+'</a></li>';
		}

		if(current_page != no_of_pages)
			pagination_html = pagination_html+'<li><a href="javascript:void(0);" page-value="'+(current_page+1)+'" class="page">Next<i class="fa fa-angle-double-right" aria-hidden="true"></i></a></li>';
		pagination_html = pagination_html+'</ul>';
	}
	return pagination_html;

}