<table id="<?php if(isset($div_name)){ echo $div_name; }else{ echo 'example'; } ?>" cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered" style="background:#CCC;">
	<thead>
		<tr class="colhead">
			<th>No</th>
			<th>MFL Code</th>
			<th>Name</th>
			<th>County</th>
			<?php if(isset($subcounty)) {?> <th>Sub-County</th> <?php } ?>
			<th>Received Samples at Lab</th>
			<th>Rejected Samples (on receipt at lab)</th>
			<th>All Test (plus reruns) Done at Lab</th>
			<th>Redraw (after testing)</th>
			<th>Routine VL-Tests</th>
			<th>Routine VL Tests &gt; 1000</th>
			<th>Baseline VL-Tests</th>
			<th>Baseline VL Tests &gt; 1000</th>
			<th>Confirmatory Repeat-Tests</th>
			<th>Confirmatory Repeat &gt; 1000</th>
			<th>Total Tests with Valid Outcomes-Tests</th>
			<th>Total Tests with Valid Outcomes &gt; 1000</th>
			<th>Female-Tests</th>
			<th>Female &gt; 1000</th>
			<th>Male-Tests</th>
			<th>Male &gt; 1000</th>
			<th>No Data-Tests</th>
			<th>No Data &gt; 1000</th>
			<th>Less 2 Yrs-Tests</th>
			<th>Less 2 Yrs &gt; 1000</th>
			<th>2 - 9 Yrs-Tests</th>
			<th>2 - 9 Yrs &gt; 1000</th>
			<th>10 - 14 yrs-Tests</th>
			<th>10 - 14 yrs &gt; 1000</th>
			<th>15 - 19 yrs-Tests</th>
			<th>15 - 19 yrs &gt; 1000</th>
			<th>20- 24 yrs-Tests</th>
			<th>20- 24 yrs &gt; 1000</th>
			<th>Above 25 yrs-Tests</th>
			<th>Above 25 yrs &gt; 1000</th>
		</tr>
		
	</thead>
	<tbody>
		<?php echo $outcomes;?>
	</tbody>
</table>
<div class="row" id="excels"  style="display: none;">
	<div class="col-md-6">
		<!-- <center><button class="btn btn-primary" style="background-color: #009688;color: white;">List of all supported sites</button></center> -->
	</div>
	<div class="col-md-6">
		<center><a href="<?php  echo $link; ?>"><button id="download_link" class="btn btn-primary" style="background-color: #009688;color: white;">Export To Excel</button></a></center>
	</div>
</div>
<script type="text/javascript" charset="utf-8">
  $(document).ready(function() {

  	$('#example').DataTable({
  		dom: '<"btn btn-primary"B>lTfgtip',
		responsive: false,
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

    // $("table").tablecloth({
    //   theme: "paper",
    //   striped: true,
    //   sortable: true,
    //   condensed: true
    // });
  });
</script>