@extends('layouts.master')

@section('content')


<div id="first">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				  Facilities Outcomes <div class="display_date"></div>
				</div>
				<div class="panel-body" id="siteOutcomes">
					<center><div class="loader"></div></center>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="second">
	<div class="row">
		<div class="col-md-6 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    Trends <div class="display_range"></div>
			  </div>
			  <div class="panel-body" id="tsttrends">
			    <center><div class="loader"></div></center>
			  </div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    Outcomes <div class="display_range"></div>
			  </div>
			  <div class="panel-body" id="stoutcomes">
			    <center><div class="loader"></div></center>
			  </div>
			</div>
		</div>
	</div>
	<div class="row">
		<!-- Map of the country -->
		<div class="col-md-6 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  	VL Outcomes <div class="display_date" ></div>
			  </div>
			  <div id="vlOutcomes">
			  	<center><div class="loader"></div></center>
			  </div>
			  
			</div>
		</div>
		
		<div class="col-md-3">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    Routine VLs Outcomes by Age <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="ageGroups">
			    <center><div class="loader"></div></center>
			  </div>
			  <!-- <div>
			  	<button class="btn btn-default" onclick="ageModal();">Click here for breakdown</button>
			  </div> -->
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    Routine VLs Outcomes by Gender <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="gender" style="padding-bottom:0px;">
			    <center><div class="loader"></div></center>
			  </div>
			</div>
		</div>
	</div>
		
		<!-- Map of the country -->
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-heading">
				  Justification for tests <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="justification" >
			    <center><div class="loader"></div></center>
			  </div>
			  <div>
			  	<center><button class="btn btn-default" onclick="justificationModal();" style="background-color: #1BA39C;color: white;margin-bottom: 1em;">Click here for breakdown</button></center>
			  </div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-heading">
				  Suppression Rate <div class="display_current_range"></div>
			  </div>
			  <div class="panel-body" id="current_sup" >
			    <center><div class="loader"></div></center>
			  </div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-heading">
				  Tests done by unique patients <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="long_tracking" >
			    <center><div class="loader"></div></center>
			  </div>
			</div>
		</div>
		
		<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-heading">
				  Current Suppression Rate <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="current_sup_dynamic" >
			    <center><div class="loader"></div></center>
			  </div>
			</div>
		</div>

	</div>

</div>
	<div class="row" style="display: none;">
		<div class="col-md-6 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    Longitudinal Patient Tracking Statistics <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="pat_stats">
			    <center><div class="loader"></div></center>
			  </div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12 col-xs-12">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="panel panel-default">
					  <div class="panel-heading">
					    Patients Outcomes <div class="display_date"></div>
					  </div>
					  <div class="panel-body" id="pat_out">
					    <center><div class="loader"></div></center>
					  </div>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="panel panel-default">
					  <div class="panel-heading">
					    Patients Graphs <div class="display_date"></div>
					  </div>
					  <div class="panel-body" id="pat_graph">
					    <center><div class="loader"></div></center>
					  </div>
					</div>
				</div>
			</div>
		</div>	
	</div>

</div>

<div class="modal fade" tabindex="-1" role="dialog" id="agemodal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Age Category Breakdown</h4>
      </div>
      <div class="modal-body" id="CatAge">
        <center><div class="loader"></div></center>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="justificationmodal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Pregnant and Lactating Mothers</h4>
      </div>
      <div class="modal-body" id="CatJust">
        <center><div class="loader"></div></center>
      </div>
    </div>
  </div>
</div>

@endsection


@section('scripts')

<script type="text/javascript">

	function reload_page()
	{
		var filter_value = $("#{{ $filter_name }}").val();
		console.log(filter_value);

    	$("#siteOutcomes").html("<center><div class='loader'></div></center>");
    	$("#tsttrends").html("<center><div class='loader'></div></center>");
    	$("#stoutcomes").html("<center><div class='loader'></div></center>");
    	$("#vlOutcomes").html("<center><div class='loader'></div></center>");
    	$("#ageGroups").html("<center><div class='loader'></div></center>");
    	$("#gender").html("<center><div class='loader'></div></center>");
    	$("#justification").html("<center><div class='loader'></div></center>");
    	$("#long_tracking").html("<center><div class='loader'></div></center>");
    	$("#current_sup_dynamic").html("<center><div class='loader'></div></center>");
    	$("#current_sup").html("<center><div class='loader'></div></center>");


		if(filter_value && filter_value != 'null'){
			$("#tsttrends").load("{{ url('trends/monthly_trends/4') }}");
			$("#stoutcomes").load("{{ url('trends/monthly_sample_types/4') }}");
			$("#vlOutcomes").load("{{ url('summary/vl_outcomes') }}");
			$("#ageGroups").load("{{ url('summary/age') }}");
			$("#gender").load("{{ url('summary/gender') }}");
			$("#justification").load("{{ url('summary/justification') }}");

			$("#long_tracking").load("{{ url('summary/get_patients') }}");
			$("#current_sup_dynamic").load("{{ url('summary/get_current_suppresion') }}");
			$("#current_sup").load("{{ url('summary/current_suppression') }}");
		}else{
    		$("#siteOutcomes").load("{{ url('summary/outcomes/4') }}");

		}
	}

	$().ready(function(){

			$("#first").show();
			$("#second").hide();

		$("select").change(function(){			
			var filter_value = $(this).val();

			if(filter_value == 'null'){
				$("#first").show();
				$("#second").hide();
			}else{	 
				$("#first").hide();
				$("#second").show();
			}
		});

	    date_filter('yearly', "{{ date('Y') }}");
	    // reload_page();
	});

</script>

@endsection