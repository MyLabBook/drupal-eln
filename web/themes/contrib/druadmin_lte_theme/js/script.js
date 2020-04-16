$ = jQuery;

$(function(){

	$('ol.breadcrumb li').each(function(index, value){
		if( $.trim($(value).html()).length == 0 )
			$(value).remove();
	});

	
})