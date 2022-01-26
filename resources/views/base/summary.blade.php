@extends('layouts.master')

@section('content')

<div class="row">
	<div class="col-md-12" id="nattatdiv">
		<div class="col-md-4">
			<div class="col-md-4 title-name" id="title">
				<center>VL Uptake (%) </center>
			</div>
			<div class="col-md-8">
				<div id="coverage"><div style="padding-top: 2em;">Loading...</div></div>
			</div>
		</div>
		<div class="col-md-5">
			<div class="col-md-4 title-name" id="title">
				<center>National TAT <l style="color:red;">(Days)</l></center>
			</div>
			<div class="col-md-8">
				<div id="nattat"></div>
			</div>
			<div id="row">
			    <center>
			        <strong>
			            <p style="font-size: 12px">TAT calculation is based on working days excluding weekends and public holidays</p>
			        </strong>
			    </center>
			</div>
		</div>
		<div class="col-md-3">
			<div class="title-name">Key</div>
			<div class="row">
				<div class="col-md-6">
					<div class="key cr" style="background-color: rgba(255,0,0,0.5);"><center>Collection to Receipt (C-R)</center></div>
					<div class="key rp" style="background-color: rgba(255,255,0,0.5);"><center>Receipt to Processing (R-P)</center></div>
				</div>
				<div class="col-md-6">
					<div class="key pd" style="background-color: rgba(0,255,0,0.5);"><center>Processing to Dispatch (P-D)</center></div>
					<div class="key"><center><div class="cd"></div>Collection to Dispatch (C-D)</center></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-heading" style="min-height: 4em;">
		  	<div class="col-sm-3">
		  		<div id="samples_heading">Testing Trends for Routine VL</div> <div class="display_date"></div>
		  	</div>
		    <div class="col-sm-3">
		    	<input type="submit" class="btn btn-primary" id="switchButton" onclick="switch_source()" value="Click to Switch to Valid Tests">
		    </div>
		  </div>
		  <div class="panel-body" id="samples">
		    <center><div class="loader"></div></center>
		  </div>
		</div>
	</div>
</div>
<div class="row">
	<!-- Map of the country -->
	<div class="col-md-7 col-sm-3 col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-heading">
		  	VL Outcomes <div class="display_date" ></div>
		  </div>
		  <div class="panel-body" id="vlOutcomes">
		  	<center><div class="loader"></div></center>
		  </div>
		  
		</div>
	</div>
	<div class="col-md-5">
		<div class="panel panel-default">
		  <div class="panel-heading">
		    Routine VLs Outcomes by Gender <div class="display_date"></div>
		  </div>
		  <div class="panel-body" id="gender" style="height:650px;padding-bottom:0px;">
		    <center><div class="loader"></div></center>
		  </div>
		</div>
	</div>
	
	<div class="col-md-6">
		<div class="panel panel-default">
		  <div class="panel-heading">
		    Routine VLs Outcomes by Age <div class="display_date"></div>
		  </div>
		  <div class="panel-body" id="ageGroups">
		    <center><div class="loader"></div></center>
		  </div>
		  <div>
		  	<!-- <center><button class="btn btn-default" onclick="ageModal();" style="background-color: #1BA39C;color: white; margin-top: 1em;margin-bottom: 1em;">Click here for breakdown</button></center> -->
		  </div>
		</div>
	</div>
	<!-- Map of the country -->
	<div class="col-md-6 col-sm-4 col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-heading">
			  Justification for tests <div class="display_date"></div>
		  </div>
		  <div class="panel-body" id="justification" style="height:500px;">
		    <center><div class="loader"></div></center>
		  </div>
		  <!-- <div>
		  	<center><button class="btn btn-default" onclick="justificationModal();" style="background-color: #1BA39C;color: white;margin-bottom: 1em;">Click here for breakdown</button></center>
		  </div> -->
		</div>
	</div>
	
	
</div>
<div class="row">
	<!-- Map of the country -->
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-heading" id="heading">
		  	County Outcomes <div class="display_date"></div>
		  </div>
		  <div class="panel-body" id="county">
		    <center><div class="loader"></div></center>
		  </div>
		</div>
	</div>
</div>
<div class="row">
	<!-- Map of the country -->
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-heading" id="heading">
		  	PMTCT Outcomes <div class="display_date"></div>
		  </div>
		  <div class="panel-body" id="pmtct">
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
    	$("#nattat").html("<center><div class='loader'></div></center>");
    	$("#samples").html("<center><div class='loader'></div></center>");
    	$("#vlOutcomes").html("<center><div class='loader'></div></center>");
    	$("#justification").html("<center><div class='loader'></div></center>");
    	$("#ageGroups").html("<center><div class='loader'></div></center>");
    	$("#gender").html("<center><div class='loader'></div></center>");
    	$("#county").html("<center><div class='loader'></div></center>");
    	$("#coverage").html("<center><div class='loader'></div></center>");
    	$("#pmtct").html("<center><div class='loader'></div></center>");

    	let all_tests = localStorage.getItem("all_tests");
		$("#nattat").load("{{ url('summary/turnaroundtime') }}");
		$("#samples").load("{{ url('summary/sample_types/') }}/" + all_tests);
		$("#vlOutcomes").load("{{ url('summary/vl_outcomes') }}");
		$("#justification").load("{{ url('summary/justification') }}"); 
		$("#ageGroups").load("{{ url('summary/age') }}"); 
		$("#gender").load("{{ url('summary/gender') }}");
		$("#county").load("{{ url('summary/outcomes') }}");
		$("#coverage").load("{{ url('summary/vl_coverage') }}"); 
		$("#pmtct").load("{{ url('pmtct/outcomes') }}"); 
		
	}


	$().ready(function(){
		localStorage.setItem("all_tests", 1);
		// $("#filter_agency").val(1).change();
		// $(".display_date").html("{{ $display_date }}");

		date_filter('yearly', "{{ date('Y') }}");
		// reload_page();
	});

	let switch_source = () => {
		let all_tests_state = localStorage.getItem("all_tests");
		if (all_tests_state == 1) {
			localStorage.setItem("all_tests", 0);
			$("#switchButton").val('Click to Switch To All Tests');
		} else {
			localStorage.setItem("all_tests", 1);
			$("#switchButton").val('Click to Switch To Valid Tests');
		}
		all_tests_state = localStorage.getItem("all_tests");
		$("#samples").load("{{ url('summary/sample_types/') }}/" + all_tests_state);
	}

</script>

@endsection
