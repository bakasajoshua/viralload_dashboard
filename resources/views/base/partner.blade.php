@extends('layouts.master')

@section('content')

<div class="row" id="third">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-heading" style="min-height: 4em;">
		  	<div class="col-sm-3">
		  		<div id="samples_heading">Testing Trends for Routine VL</div> <div class="display_date"></div>
		  	</div>
		    <div class="col-sm-3">
		    	<input type="submit" class="btn btn-primary" id="switchButton" onclick="switch_source()" value="Click to Switch to All Tests">
		    </div>
		  </div>
		  <div class="panel-body" id="samples">
		    <center><div class="loader"></div></center>
		  </div>
		</div>
	</div>
</div>
<div class="row" id="second">
	<!-- Map of the country -->
	<div class="row">
		<div class="col-md-7 col-sm-7 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  	VL Outcomes <div class="display_date" ></div>
			  </div>
			  <div id="vlOutcomes">
			  	<center><div class="loader"></div></center>
			  </div>
			  
			</div>
		</div>
		<div class="col-md-5">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    Routine VLs Outcomes by Gender <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="gender">
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
					    Routine VLs Outcomes by Age <div class="display_date"></div>
					  </div>
					  <div class="panel-body" id="ageGroups">
					    <center><div class="loader"></div></center>
					  </div>
					  <!-- <div>
					  	<center><button class="btn btn-default" onclick="ageModal();" style="background-color: #1BA39C;color: white; margin-top: 1em;margin-bottom: 1em;">Click here for breakdown</button></center>
					  </div> -->
					</div>
		</div>
		<div class="col-md-6 col-sm-6 col-xs-12">
			<div class="panel panel-default">
			  	<div class="panel-heading">
				  	Justification for tests <div class="display_date"></div>
			  	</div>
				<div class="panel-body" id="justification">
				    <center><div class="loader"></div></center>
				</div>
			  	<!-- <div>
			  		<center><button class="btn btn-default" onclick="justificationModal();" style="background-color: #1BA39C;color: white; margin-top: 1em;margin-bottom: 1em;">Click here for breakdown</button></center>
			  	</div> -->
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4 col-sm-4 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    &nbsp;&nbsp;&nbsp;&nbsp; Tests done by unique patients <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="long_tracking">
			    <center><div class="loader"></div></center>
			  </div>
			</div>
		</div>

		<div class="col-md-4 col-sm-4 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    Current Suppression Rate <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="current_sup_dynamic">
			    <center><div class="loader"></div></center>
			  </div>
			</div>
		</div>

		<div class="col-md-4 col-sm-4 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    Suppression Rate <div class="display_current_range"></div>
			  </div>
			  <div class="panel-body" id="current_sup">
			    <center><div class="loader"></div></center>
			  </div>
			</div>
		</div>
	</div>
	
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
			  Partner County Outcomes <div class="display_date"></div>
			</div>
		  	<div class="panel-body" id="partnerCountyOutcomes">
		  		<center><div class="loader"></div></center>
		  	</div>
		</div>
	</div>

	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
			  Partner Counties <div class="display_date"></div>
			</div>
		  	<div class="panel-body" id="partnerCounties">
		  		<center><div class="loader"></div></center>
		  	</div>
		  	<hr>
		  	<hr>
		</div>
	</div>
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
			  Partner Sites <div class="display_date"></div>
			</div>
		  	<div class="panel-body" id="partnerSites">
		  		<center><div class="loader"></div></center>
		  	</div>
		</div>
	</div>

</div>

<div class="row" id="first">
	<!-- Map of the country -->
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-heading">
		  	Partner Outcomes <div class="display_date"></div>
		  </div>
		  <div class="panel-body" id="partner_div">
		    <center><div class="loader"></div></center>
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
		  	Partner TAT Outcomes <div class="display_date"></div>
		  </div>
		  <div class="panel-body" id="partner_tat_outcomes">
		    <center><div class="loader"></div></center>
		  </div>
		</div>
	</div>
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-heading" id="heading">
		  	Partner TAT Details <div class="display_date"></div>
		  </div>
		  <div class="panel-body" id="partner_tat_details">
		    <center><div class="loader"></div></center>
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
		// console.log(filter_value);
    	$("#partner_div").html("<center><div class='loader'></div></center>");
    	$("#partner_tat_outcomes").html("<center><div class='loader'></div></center>");
    	$("#partner_tat_details").html("<center><div class='loader'></div></center>");
    	$("#vlOutcomes").html("<center><div class='loader'></div></center>");
    	$("#gender").html("<center><div class='loader'></div></center>");
    	$("#age").html("<center><div class='loader'></div></center>");
    	$("#samples").html("<center><div class='loader'></div></center>");
    	$("#justification").html("<center><div class='loader'></div></center>");
    	$("#long_tracking").html("<center><div class='loader'></div></center>");
    	$("#current_sup_dynamic").html("<center><div class='loader'></div></center>");
    	$("#current_sup").html("<center><div class='loader'></div></center>");
    	$("#partnerCounties").html("<center><div class='loader'></div></center>");
    	$("#partnerCountyOutcomes").html("<center><div class='loader'></div></center>");
    	$("#partnerSites").html("<center><div class='loader'></div></center>");


		$("#partner_tat_outcomes").load("{{ url('tat/outcomes/1') }}");
		$("#partner_tat_details").load("{{ url('tat/details/1') }}");
		$("#partner_div").load("{{ url('summary/outcomes/3') }}");

		if(filter_value && filter_value != 'null'){
			$("#vlOutcomes").load("{{ url('summary/vl_outcomes') }}");
			$("#gender").load("{{ url('summary/gender') }}");
			$("#ageGroups").load("{{ url('summary/age') }}"); 
			$("#samples").load("{{ url('summary/sample_types') }}");
			$("#justification").load("{{ url('summary/justification') }}");
			// $("#sub_counties").load("{{ url('county/division_table/3/4') }}");

			$("#long_tracking").load("{{ url('summary/get_patients') }}");
			$("#current_sup_dynamic").load("{{ url('summary/get_current_suppresion') }}");
			$("#current_sup").load("{{ url('summary/current_suppression') }}");

			// $("#partner_div").load("{{ url('summary/county_outcomes') }}");
			$("#partnerCounties").load("{{ url('county/division_table/3/1') }}");
			$("#partnerCountyOutcomes").load("{{ url('summary/outcomes/3/1') }}");
			$("#partnerSites").load("{{ url('county/division_table/3/4') }}");

		}
	}

	$().ready(function(){

		$("#first").show();
		$("#second").hide();
		$("#third").hide();

		$("select").change(function(){			
			var filter_value = $(this).val();

			if(filter_value == 'null'){
        		$("#first").show();
        		$("#second").hide();
				$("#third").hide();
			}else{	 
        		$("#first").hide();
        		$("#second").show();
				$("#third").show();
			}
		});

	    date_filter('yearly', "{{ date('Y') }}");
	    // reload_page();
	});

</script>

@endsection