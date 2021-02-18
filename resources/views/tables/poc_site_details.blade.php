

<div class="modal fade" tabindex="-1" role="dialog" id="poc_site_details">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"> POC Site Details </h4>
      </div>
      <div class="modal-body" style="overflow-x: scroll;">
        <table id="poc_site_table_details" cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered" style="max-width: 100%;">
        	<thead>
        		<tr>
        			<th>#</th>
              <th>Name</th>
              <th>MFL Code</th>
              <th>County</th>
              <th>Received Samples at Hub</th>
              <th>Rejected Samples (on receipt at Hub)</th>
              <th>All Test Done at Hub</th>
              <th>Redraw (after testing)</th>
              <th>Routine VL Tests</th>
              <th>Routine VL Tests &gt; 1000</th>
              <th>Baseline VL Tests</th>
              <th>Baseline VL Tests &gt; 1000</th>
              <th>Confirmatory Repeat Tests</th>
              <th>Confirmatory Repeat Tests &gt; 1000</th>
              <th>Total Tests with Valid Outcomes</th>
              <th>Total Tests &gt; 1000 with Valid Outcomes</th>
              <th>Suppressed</th>
              <th>Suppression</th>
              <th>Adults</th>
              <th>Paeds</th>
              <th>Rejected</th>
        		</tr>
        	</thead>
        	<tbody>
        		{!! $rows !!}
        	</tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
	$(document).ready(function() {
		$("#poc_site_table_details").DataTable({
      // dom: 'Bfrtip',
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
    
    $("#poc_site_details").modal('show');

		// $("table").tablecloth({
		// 	theme: "paper",
		// 	striped: true,
		// 	sortable: true,
		// 	condensed: true
		// });
	});

</script>