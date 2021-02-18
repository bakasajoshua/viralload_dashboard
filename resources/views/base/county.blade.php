@extends('layouts.master')

@section('content')

<div id="first">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  	Counties Outcomes <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="county">
			    <center><div class="loader"></div></center>
			  </div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				Counties <div class="display_date"></div>
				</div>
			  	<div class="panel-body" id="county_sites">
			  		<center><div class="loader"></div></center>
			  	</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				Counties Outcome by Age and Gender <div class="display_date"></div>
				</div>
			  	<div class="panel-body" id="county_outcome_age_gender">
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
			  <div class="panel-heading" id="heading">
			  Sub-Counties Outcomes <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="subcounty">
			    <center><div class="loader"></div></center>
			  </div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading" id="heading">
			  Sub-Counties Suppression <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="subcountypos">
			    <center><div class="loader"></div></center>
			  </div>
			</div>
		</div>


		<div class="col-md-4 col-sm-4 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    Tests done by unique patients <div class="display_date"></div>
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
	
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				  Sub-Counties <div class="display_date"></div>
				</div>
			  	<div class="panel-body" id="sub_counties">
			  		<center><div class="loader"></div></center>
			  	</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				  County Facilities <div class="display_date"></div>
				</div>
			  	<div class="panel-body" id="county_facilities">
			  		<center><div class="loader"></div></center>
			  	</div>
			</div>
		</div>
		<!-- <div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				  Partners <div class="display_date"></div>
				</div>
			  	<div class="panel-body" id="partners">
			  		<center><div class="loader"></div></center>
			  	</div>
			</div>
		</div> -->
		<!-- <div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				  Facilities PMTCT <div class="display_date"></div>
				</div>
			  	<div class="panel-body" id="facilities_pmtct">
			  		<center><div class="loader"></div></center>
			  	</div>
			</div>
		</div> -->
		
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
			  		County Partners Outcomes <div class="display_date"></div>
			  	</div>
			  	<div class="panel-body" id="county_partners">
			    	<center><div class="loader"></div></center>
			  	</div>
			</div>
		</div>
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				 County Partners <div class="display_date"></div>
				</div>
			  	<div class="panel-body" id="partners">
			  		<center><div class="loader"></div></center>
			  	</div>
			</div>
		</div>
	</div>
</div>

<div id="third">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading" id="heading">
			  	County TAT Outcomes <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="county_tat_outcomes">
			    <center><div class="loader"></div></center>
			  </div>
			</div>
		</div>
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading" id="heading">
			  	County TAT Details <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="county_tat_details">
			    <center><div class="loader"></div></center>
			  </div>
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
		
    	$("#county").html("<center><div class='loader'></div></center>");
    	$("#county_sites").html("<center><div class='loader'></div></center>");
    	$("#county_outcome_age_gender").html("<center><div class='loader'></div></center>");
    	$("#county_tat_outcomes").html("<center><div class='loader'></div></center>");
    	$("#county_tat_details").html("<center><div class='loader'></div></center>");

    	$("#subcounty").html("<center><div class='loader'></div></center>");
    	$("#subcountypos").html("<center><div class='loader'></div></center>");
    	$("#sub_counties").html("<center><div class='loader'></div></center>");
    	$("#county_facilities").html("<center><div class='loader'></div></center>");
    	$("#partners").html("<center><div class='loader'></div></center>");
    	$("#long_tracking").html("<center><div class='loader'></div></center>");
    	$("#current_sup_dynamic").html("<center><div class='loader'></div></center>");
    	$("#current_sup").html("<center><div class='loader'></div></center>");
    	$("#county_partners").html("<center><div class='loader'></div></center>");


		$("#county").load("{{ url('summary/outcomes') }}");
		$("#county_sites").load("{{ url('county/division_table/1') }}");
		$("#county_outcome_age_gender").load("{{ url('county/county_outcome_table') }}");
		$("#county_tat_outcomes").load("{{ url('tat/outcomes') }}");
		$("#county_tat_details").load("{{ url('tat/details') }}");

		if(filter_value && filter_value != 'null'){
			$("#subcounty").load("{{ url('county/subcounty_outcomes') }}");
			$("#subcountypos").load("{{ url('county/subcounty_outcomes') }}");
			
			$("#sub_counties").load("{{ url('county/division_table/1/2') }}");
			$("#partners").load("{{ url('county/division_table/1/3') }}");
			$("#county_facilities").load("{{ url('county/division_table/1/4') }}");
			
			$("#county_partners").load("{{ url('summary/outcomes/1/3') }}");

			$("#long_tracking").load("{{ url('summary/get_patients') }}");
			$("#current_sup_dynamic").load("{{ url('summary/get_current_suppresion') }}");
			$("#current_sup").load("{{ url('summary/current_suppression') }}");
			// $("#partners").load("{{ url('county/division_table/1/3') }}");		
		}
	}


	$().ready(function(){
		// $("#filter_agency").val(1).change();
		// $(".display_date").html("{{ $display_date }}");


    	$("#first").show();
		$("#second").hide();
		$("#third").show();


		$("select").change(function(){			
			var filter_value = $(this).val();

			if(filter_value == 'null'){
        		$("#first").show();
				$("#second").hide();
				$("#third").show();
			}else{	        		
        		$("#second").show();
				$("#first").hide();
				$("#third").show();
			}
		});

	    date_filter('yearly', "{{ date('Y') }}");
	    // reload_page();
	});

</script>

@endsection