@extends('layouts.master')

@section('content')
<div id="first">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    Age Outcomes <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="age_outcomes">
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
	</div>
	<div class="row">
		<div class="col-md-6 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  	VL Outcomes <div class="display_date" ></div>
			  </div>
			  <div class="panel-body" id="vlOutcomes">
			  	<center><div class="loader"></div></center>
			  </div>
			  
			</div>
		</div>
		<div class="col-md-6 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  	Gender <div class="display_date" ></div>
			  </div>
			  <div class="panel-body" id="gender">
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
			  <div class="panel-body" id="countiesAge">
			  	<center><div class="loader"></div></center>
			  </div>
			  
			</div>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  	Partners <div class="display_date" ></div>
			  </div>
			  <div class="panel-body" id="partnersAge">
			  	<center><div class="loader"></div></center>
			  </div>
			  
			</div>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  	Sub-counties <div class="display_date" ></div>
			  </div>
			  <div class="panel-body" id="subcountiesAge">
			  	<center><div class="loader"></div></center>
			  </div>
			  
			</div>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  	Facilities <div class="display_date" ></div>
			  </div>
			  <div class="panel-body" id="facilitiesAge">
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
	<div class="row">
		<!-- Map of the country -->
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading" id="heading">
			  	Regimen Outcomes <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="regimen_age">
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
		console.log(filter_value);

		$("#age_outcomes").html("<center><div class='loader'></div></center>");
		$("#vlOutcomes").html("<center><div class='loader'></div></center>");
		$("#gender").html("<center><div class='loader'></div></center>");
		$("#samples").html("<center><div class='loader'></div></center>");
		$("#county").html("<center><div class='loader'></div></center>");
		$("#regimen_age").html("<center><div class='loader'></div></center>");
		
		$("#countiesAge").html("<center><div class='loader'></div></center>");
		$("#subcountiesAge").html("<center><div class='loader'></div></center>");
		$("#partnersAge").html("<center><div class='loader'></div></center>");
		$("#facilitiesAge").html("<center><div class='loader'></div></center>");


		if(filter_value && filter_value != 'null'){
			$("#vlOutcomes").load("{{ url('summary/vl_outcomes') }}");
			$("#gender").load("{{ url('suppression/age_gender') }}"); 
			$("#samples").load("{{ url('summary/sample_types') }}");
			$("#county").load("{{ url('county/subcounty_outcomes/12/1') }}");
			$("#regimen_age").load("{{ url('county/subcounty_outcomes/12/11') }}");

			$("#countiesAge").load("{{ ('suppression/breakdowns/12/1') }}");
			$("#subcountiesAge").load("{{ ('suppression/breakdowns/12/2') }}");
			$("#partnersAge").load("{{ ('suppression/breakdowns/12/3') }}");
			$("#facilitiesAge").load("{{ ('suppression/breakdowns/12/4') }}");

		}else{
    		$("#age_outcomes").load("{{ url('county/subcounty_outcomes/12') }}");
		}
	}


	$().ready(function(){
		// $("#filter_agency").val(1).change();
		// $(".display_date").html("{{ $display_date }}");

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