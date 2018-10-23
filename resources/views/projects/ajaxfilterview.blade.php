<table id="TABLE_3" class="display dataTable" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th class="no-sort">Project Title</th>
			<th>Functions</th>
			<th>Bids</th>
			<th class="nosort">Deadline</th>
			<th class="none">Budget</th>
		</tr>
	</thead>
	<tbody >
	@foreach ($projects as $project)  
		<tr alt="{{  $project->id}}" >
			<td>{{ $project->title}}</td>
			
			<td>
			<i class="fa fa-envelope-o picon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Mail" ></i>
				<i class="fa fa-mobile picon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Mobile"></i>
				<i class="fa fa-cogs picon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Share"></i>
				<i class="fa fa-shopping-cart picon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Cart"></i>
		
			</td>
			
			<td>{{ $project->bidcount }}</td>
			<td>Until {{ date('d.m.Y', strtotime($project->deadline_date )) }}</td>
			<td>{{ $project->budget }} $</td>
			
		</tr>
		@endforeach
	</tbody>
</table>
<script src="{!! asset('js/custom.js') !!}"></script>
<script>
	$('[data-toggle="tooltip"]').tooltip()
</script>