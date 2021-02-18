<!-- <div class="list-group" style="height: 362px;"> -->
<div style="height: 362px;">
	<!-- <ul> -->
		@if(isset($listings))
			{!! $listings !!}
		@else
			@foreach($rows as $key => $row)
				@break($key == 16)
				<li class="list-group-item">
					{{ ($key+1) }} &nbsp; {{ $row['name'] }} &nbsp; {{ round($row['pecentage'],1) }} &nbsp; {{ number_format($row['pos']) }}
				</li>
			@endforeach
		@endif
	<!-- </ul> -->
</div>

<button class="btn btn-primary"  onclick="{{ $modal }}();" style="background-color: #1BA39C;color: white; margin-top: 1em;margin-bottom: 1em;">
	View Full Listing
</button>


<div class="modal fade" tabindex="-1" role="dialog" id="{{ $modal }}">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Full Listing</h4>
			</div>
			<div class="modal-body">
				<table id="{{ $div }}" cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered" style="max-width: 100%;">
					<thead>
						{!! $table_head ?? '' !!}
					    @empty($table_head)
					      <tr>
					        <th>#</th>
					        <th>Name</th>
					        <th>Tests</th>
					        <th>Suppressed</th>
					        <th>Non - Suppressed</th>
					        <th>Suppression</th>
					        @isset($is_counties)
					          <th>Male Test </th>
					          <th>Male > 1000 </th>
					          <th>Female Test </th>
					          <th>Female > 1000 </th>
					        @endisset
					      </tr>
					    @endempty
					</thead>
					<tbody>
						@if(!isset($table_rows))
							@foreach($rows as $key => $row)
								<tr>
									<td> {{ ($key+1) }} </td>
									<td> {{ $row['name'] }} </td>
									<td> {{ round($row['pecentage'],1) }} </td>
									<td> {{ number_format($row['pos']) }} </td>
									<td> {{ number_format($row['neg']) }} </td>
								</tr>
							@endforeach
						@else
							{!! $table_rows ?? '' !!}
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#{{ $div }}').DataTable({
			dom: '<"btn btn-primary"B>lTfgtip',
			responsive: true,
			buttons : [
				{
				text:  'Export to CSV',
				extend: 'csvHtml5',
				title: 'Download'
				},
				{
				text:  'Export to Excel',
				extend: 'excelHtml5',
				title: 'Download'
				}
			]
		});
	});

	function {{ $modal }}()
	{
		$('#{{ $modal }}').modal('show');
	}
</script>