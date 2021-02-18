@extends('layouts.master')

@section('content')

<div id="first">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    Regimen Outcomes <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="regimen_outcomes">
			    <center><div class="loader"></div></center>
			  </div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    Adult Regimen Outcomes <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="adult_regimen_outcomes">
			    <center><div class="loader"></div></center>
			  </div>
			</div>
		</div>		
		<div class="col-md-6 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    Paeds Regimen Outcomes <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="paeds_regimen_outcomes">
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
			  <div class="panel-heading">
			    Testing Trends <div class="display_date"></div>
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
			  	Gender <div class="display_date" ></div>
			  </div>
			  <div class="panel-body" id="gender">
			  	<center><div class="loader"></div></center>
			  </div>
			  
			</div>
		</div>
		<div class="col-md-3 col-sm-3 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  	Age <div class="display_date" ></div>
			  </div>
			  <div class="panel-body" id="age">
			  	<center><div class="loader"></div></center>
			  </div>
			  
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  	Counties <div class="display_date" ></div>
			  </div>
			  <div class="panel-body" id="countiesRegimen">
			  	<center><div class="loader"></div></center>
			  </div>
			  
			</div>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  	Partners <div class="display_date" ></div>
			  </div>
			  <div class="panel-body" id="partnersRegimen">
			  	<center><div class="loader"></div></center>
			  </div>
			  
			</div>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  	Sub-counties <div class="display_date" ></div>
			  </div>
			  <div class="panel-body" id="subcountiesRegimen">
			  	<center><div class="loader"></div></center>
			  </div>
			  
			</div>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  	Facilities <div class="display_date" ></div>
			  </div>
			  <div class="panel-body" id="FacilitiesRegimen">
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
			  	County Outcomes <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="county">
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
		

    	$("#regimen_outcomes").html("<center><div class='loader'></div></center>");
    	$("#adult_regimen_outcomes").html("<center><div class='loader'></div></center>");
    	$("#paeds_regimen_outcomes").html("<center><div class='loader'></div></center>");
    	
    	$("#samples").html("<center><div class='loader'></div></center>");
    	$("#vlOutcomes").html("<center><div class='loader'></div></center>");
    	$("#gender").html("<center><div class='loader'></div></center>");
    	$("#age").html("<center><div class='loader'></div></center>");
    	$("#county").html("<center><div class='loader'></div></center>");


		if(filter_value && filter_value != 'null'){

			$("#samples").load("{{ url('summary/sample_types/0') }}");
			$("#vlOutcomes").load("{{ url('summary/vl_outcomes') }}");
			$("#gender").load("{{ url('suppression/regimen_gender') }}");
			$("#age").load("{{ url('suppression/regimen_age') }}");

			$("#countiesRegimen").load("{{ ('suppression/breakdowns/11/1') }}");
			$("#subcountiesRegimen").load("{{ ('suppression/breakdowns/11/2') }}");
			$("#partnersRegimen").load("{{ ('suppression/breakdowns/11/3') }}");
			$("#FacilitiesRegimen").load("{{ ('suppression/breakdowns/11/4') }}");


			$("#county").load("{{ url('county/subcounty_outcomes/11') }}");
		}else{
    		$("#regimen_outcomes").load("{{ url('county/subcounty_outcomes/11') }}");
    		$("#adult_regimen_outcomes").load("{{ url('county/subcounty_outcomes/11/1') }}");
    		$("#paeds_regimen_outcomes").load("{{ url('county/subcounty_outcomes/11/2') }}");
		}
	}


	$().ready(function(){

		$("#second").hide();
		$("#first").show();


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