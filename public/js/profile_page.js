
// Datatable
function format ( d, data ) {

	return '<div class="col-md-5 col-xs-12  space"><p class="show-read-more" >'+ data.description +' </p></div><div class="col-md-5 col-xs-12"><div class="col-md-5 col-xs-8 customli"><li class="pull-left">Deadlines		</li><li class="pull-right">'+ data.ratedeadlines.toFixed(1)+'</li></div><div class="col-md-5 col-xs-8 customli"><li class="pull-left">Availability	</li><li class="pull-right">'+ data.rateavailability.toFixed(1) +'</li></div><div class="col-md-5 col-xs-8 customli"><li class="pull-left">Communication</li><li class="pull-right">'+ data.rateCommunication.toFixed(1) +'</li></div><div class="col-md-5 col-xs-8 customli"><li class="pull-left">Quality	</li><li class="pull-right">'+ data.ratequality.toFixed(1) +'</li></div><div class="col-md-5 col-xs-8 customli"><li class="pull-left">Skills</li><li class="pull-right">'+data.rateskills.toFixed(1) +'</li></div><div class="col-md-5 col-xs-8 customli"><li class="pull-left">Cooperation</li><li class="pull-right">'+data.ratecooperation.toFixed(1) +'</li></div><div class="col-md-5 col-xs-8 customli"><li class="pull-left"> </li><li class="pull-right"></li></div><div class="col-md-5 col-xs-8 customli"><li class="pull-left"></li><li class="pull-right"></li></div></div></div><div claass="col-md-12 col-xs-12 text-right"><span class="project-detail"><a href="'+ routeUrl +'projects/'+data.projectid+'" >Project Details </a></span></div>';
}

 var table = $('#profile_page_list').DataTable({
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


$('.dataTable tbody').on('click', '.odd,.even', function () {
	var tr = $(this);
	var row = table.row(tr);
	var reviewid=$(this).attr('alt');
	$.ajax({
		type:'POST',
		url: routeUrl+'getreviewdetail',
		data:{reviewid:reviewid},
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
				row.child( format(row.data() ,data.reviewdetails) ).show();
				row.child().addClass('details')
				tr.addClass('shown');
			}

			var maxLength = 185;
			$(".show-read-more").each(function(){
				var myStr = $(this).text();
				if($.trim(myStr).length > maxLength){
					var newStr = myStr.substring(0, maxLength);
					var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
					$(this).empty().html(newStr);
					$(this).append('<span  class="read-moreproftext"> read more ...</span>  ');
					$(this).append('<span class="more-text">' + removedStr + '</span>');
				}
			});
			
			$(".read-moreproftext").click(function(){
				$(this).siblings(".more-text").contents().unwrap();
				$(this).remove();
			});
		}
	 });


});


