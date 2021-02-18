@extends('layouts.master')

@section('content')
<div id="first">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    Subcounties Outcomes <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="subcounty_outcomes">
			    <center><div class="loader"></div></center>
			  </div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				Subcounties <div class="display_date"></div>
				</div>
			  	<div class="panel-body" id="subcounty_summary">
			  		<center><div class="loader"></div></center>
			  	</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				Sub-counties Outcome by Age and Gender <div class="display_date"></div>
				</div>
			  	<div class="panel-body" id="subcounty_outcome_age_gender">
			  		<center><div class="loader"></div></center>
			  	</div>
			</div>
		</div>
	</div>
</div>

<div id="second">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading" onclick="switch_source()">
			    <div id="samples_heading">Testing Trends for Routine VL</div> (Click to switch)<div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="samples">
			    <center><div class="loader"></div></center>
			  </div>
			</div>
		</div>
		<div class="col-md-6 col-sm-3 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  	VL Outcomes <div class="display_date" ></div>
			  </div>
			  <div class="panel-body" id="vlOutcomes">
			  	<center><div class="loader"></div></center>
			  </div>
			  
			</div>
		</div>
		<div class="col-md-3 col-sm-3 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  	Routine VLs Outcomes by Gender <div class="display_date" ></div>
			  </div>
			  <div class="panel-body" id="gender">
			  	<center><div class="loader"></div></center>
			  </div>
			  
			</div>
		</div>
		<div class="col-md-3 col-sm-3 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  	Routine VLs Outcomes by Age <div class="display_date" ></div>
			  </div>
			  <div class="panel-body" id="age">
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
	
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				  Sub-County Sites <div class="display_date"></div>
				</div>
			  	<div class="panel-body" id="sub_counties">
			  		<center><div class="loader"></div></center>
			  	</div>
			</div>
		</div>
	</div>
	

</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<center>
	        <strong>
	            <p style="font-size: 12px">TAT calculation is based on working days excluding weekends and public holidays</p>
	        </strong>
	    </center>
	</div>
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-heading" id="heading">
		  	Sub-County TAT Outcomes <div class="display_date"></div>
		  </div>
		  <div class="panel-body" id="subcounty_tat_outcomes">
		    <center><div class="loader"></div></center>
		  </div>
		</div>
	</div>
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-heading" id="heading">
		  	Sub-County TAT Details <div class="display_date"></div>
		  </div>
		  <div class="panel-body" id="subcounty_tat_details">
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
		// console.log(filter_value);
    	$("#subcounty_tat_outcomes").html("<center><div class='loader'></div></center>");
    	$("#subcounty_tat_details").html("<center><div class='loader'></div></center>");

    	$("#subcounty_outcomes").html("<center><div class='loader'></div></center>");
    	$("#subcounty_summary").html("<center><div class='loader'></div></center>");
    	$("#subcounty_outcome_age_gender").html("<center><div class='loader'></div></center>");
    	$("#vlOutcomes").html("<center><div class='loader'></div></center>");
    	$("#gender").html("<center><div class='loader'></div></center>");
    	$("#age").html("<center><div class='loader'></div></center>");
    	$("#samples").html("<center><div class='loader'></div></center>");
    	$("#sub_counties").html("<center><div class='loader'></div></center>");
    	$("#long_tracking").html("<center><div class='loader'></div></center>");
    	$("#current_sup_dynamic").html("<center><div class='loader'></div></center>");


		$("#subcounty_tat_outcomes").load("{{ url('tat/outcomes/2') }}");
		$("#subcounty_tat_details").load("{{ url('tat/details/2') }}");


		if(filter_value && filter_value != 'null'){
			$("#vlOutcomes").load("{{ url('summary/vl_outcomes') }}");
			$("#gender").load("{{ url('summary/gender') }}");
			$("#age").load("{{ url('summary/age') }}"); 
			$("#samples").load("{{ url('summary/sample_types') }}");
			$("#sub_counties").load("{{ url('county/division_table/2/4') }}");

			$("#long_tracking").load("{{ url('summary/get_patients') }}");
			$("#current_sup_dynamic").load("{{ url('summary/get_current_suppresion') }}");
		}
		else{
			$("#subcounty_outcomes").load("{{ url('county/subcounty_outcomes') }}");
			$("#subcounty_summary").load("{{ url('county/division_table/2') }}");
			$("#subcounty_outcome_age_gender").load("{{ url('county/county_outcome_table/1') }}");
		}
	}

	$().ready(function(){
		// $("#filter_agency").val(1).change();
		// $(".display_date").html("{{ $display_date }}");


		$("#second").hide();


		$("select").change(function(){			
			var filter_value = $(this).val();

			if(filter_value == 'null'){
        		$("#second").hide();
        		$("#first").show();
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