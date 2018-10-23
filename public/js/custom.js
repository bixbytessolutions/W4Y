// Datatable
function format (d , description,id) {
	
	

	if(description.length > 0) descriptiontrim = description.substring(0,185);
	// Ajax call to get the description of selected row
	return '<div class="col-md-6 projectDescription"><div class="showFunctionalIcon"> 	<i class="fa fa-envelope-o picon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Message"></i><i class="fa fa-mobile picon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Mobile"></i>	<i class="fa fa-cogs picon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Phone"></i>	<i class="fa fa-shopping-cart picon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Cart"></i></div>'+descriptiontrim+' </div> <div class="col-md-12 bidProject text-right"><a href="'+ routeUrl +'projects/'+id+'" >Bid on this Project </a></div>';
}
 var table = $("table[id^='TABLE']").DataTable({
	"bPaginate": true,
	"searching": false,
	"showNEntries" : false,
	"order": [],
	"aoColumns": [
			{ "bSortable": false },
			{ "bSortable": false },
			{ "bSortable": true },
			{ "bSortable": true },
			{ "bSortable": true }
		],
		language: {
		    paginate: {
		      next: '<i class="fa fa-angle-right"></i>',
		      previous: '<i class="fa fa-angle-left"></i>'  
		    }
		}
});

$('.dataTable tbody').on('click', '.odd,.even', function (event) {
    if(event.target.nodeName.toLowerCase()!="td") return false;
	var tr = $(this);
	var projid=$(this).attr('alt');

	$.ajax({
		type:'POST',
		url: routeUrl+'getprojdetail',
		data:{projid:projid},
		success:function(data){
			var row = table.row(tr);
			if(tr.hasClass('shown')){
		
				$('.dataTable').find('.shown').removeClass('shown')
				$('.dataTable').find('.details').hide()
				
			}else{
				if($('.dataTable').find('tr').hasClass('shown')){
					$('.dataTable').find('.shown').removeClass('shown')
					$('.dataTable').find('.details').hide()
				}
				row.child( format(row.data() ,data.projectDescription,projid) ).show();
				row.child().addClass('details')
				tr.addClass('shown');
			}
		}
	 });
});



