@extends('layouts.master')

@section('content')

<div id="first">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
		    <div class="panel panel-default">
		      <div class="panel-heading">
		        LAB PERFORMANCE STATS <div class="display_date"></div>
		      </div>
		      <div class="panel-body" id="lab_perfomance_stats">
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
	</div>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    Turn around Time <div class="display_date" ></div>
			  </div>
			  <div class="panel-body" id="ttime">
			    <div>Loading...</div>
			  </div>
			  
			</div>
		</div>	
	</div>

	{{-- <div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  	Routine VLs Result Outcomes <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="results">
			    <div>Loading...</div>
			  </div>
			</div>
		</div>
	</div> --}}
</div>

<div id="second">
	<div class="row">	
		<div style="color:red;"><center>Click on Lab(s) on legend to view only for the lab(s) selected</center></div>
		<div class="col-md-6 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  	Labs Testing Trends <div class="display_date" ></div>
			  </div>
			  <div class="panel-body" id="test_trends">
			  	<center><div class="loader"></div></center>
			  </div>
			  
			</div>
		</div>
		
		<div class="col-md-6 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  	Labs Rejection Trends <div class="display_date" ></div>
			  </div>
			  <div class="panel-body" id="rejected">
			  	<center><div class="loader"></div></center>
			  </div>
			  
			</div>
		</div>

		
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  	Labs TAT Trends <div class="display_date" ></div>
			  </div>
			  <div class="panel-body" id="lab_tat">
			  	<center><div class="loader"></div></center>
			  </div>
			  
			</div>
		</div>		
	</div>
	{{-- <div class="row">
		<div id="lab_summary">
  
  		</div>
	</div>

	<div class="row">
		<div id="graphs">
  
  		</div>
	</div>	--}}
</div>

<div id="third">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="panel panel-default">
	      <div class="panel-heading">
	        Samples Rejections <div class="display_date"></div>
	      </div>
	      <div class="panel-body" id="lab_rejections">
	        <center><div class="loader"></div></center>
	      </div>
	    </div>
	  </div>
	</div>	
</div>

<div id="fourth">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="panel panel-default">
	      <div class="panel-heading">
	        Rejections By Facilities <div class="display_date"></div>
	      </div>
	      <div class="panel-body" id="lab_facility_rejections">
	        <center><div class="loader"></div></center>
	      </div>
	    </div>
	  </div>
	</div>	
</div>

<div id="fifth">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="panel panel-default">
	      <div class="panel-heading">
	         Hub-Spoke Stats <div class="display_date"></div>
	      </div>
	      <div class="panel-body" id="poc">
	        <center><div class="loader"></div></center>
	      </div>
	    </div>
	  </div>
	</div>	

	<!-- <div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="panel panel-default">
	      <div class="panel-heading">
	        POC Outcomes <div class="display_date"></div>
	      </div>
	      <div class="panel-body" id="poc_outcomes">
	        <center><div class="loader"></div></center>
	      </div>
	    </div>
	  </div>
	</div>	 -->
</div>

{{--
<div class="row">
	<div class="col-md-4 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-heading">
		    VLs Tested by Sample Type <div class="display_date" ></div>
		  </div>
		  <div class="panel-body" id="samples">
		    <div>Loading...</div>
		  </div>
		</div>
	</div>
	<div class="col-md-4 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-heading">
		    VLs Gender Breakdown <div class="display_date" ></div>
		  </div>
		  <div class="panel-body" id="lab_gender">
		    <div>Loading...</div>
		  </div>
		</div>
	</div>
	<div class="col-md-4 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-heading">
		    VLs Age Breakdown <div class="display_date" ></div>
		  </div>
		  <div class="panel-body" id="lab_age">
		    <div>Loading...</div>
		  </div>
		</div>
	</div>
</div>
--}}

<div id="my_empty_div"></div>
		

@endsection


@section('scripts')

<script type="text/javascript">

	function reload_page()
	{
		var filter_value = $("#{{ $filter_name }}").val();

	    $("#lab_perfomance_stats").html("<div>Loading...</div>"); 
 		$("#rejected").html("<div>Loading...</div>"); 
		$("#test_trends").html("<div>Loading...</div>");
		$("#samples").html("<div>Loading...</div>");
		$("#lab_gender").html("<div>Loading...</div>");
		$("#lab_age").html("<div>Loading...</div>");
		$("#ttime").html("<div>Loading...</div>");
		$("#lab_tat").html("<div>Loading...</div>");
		{{-- $("#results").html("<div>Loading...</div>"); --}}
		$("#lab_facility_rejections").html("<div>Loading...</div>");
		$("#poc").html("<div>Loading...</div>");
		$("#poc_outcomes").html("<div>Loading...</div>");


		$("#lab_perfomance_stats").load("{{ url('lab/lab_performance_stat/') }}");
		$("#ttime").load("{{ url('lab/labs_turnaround/') }}");
		$("#lab_tat").load("{{ url('lab/labs_turnaround/') }}");
		{{-- $("#results").load("{{ url('lab/labs_outcomes/') }}");	--}}
		$("#lab_rejections").load("{{ url('lab/rejections/') }}");
		$("#poc").load("{{ url('lab/poc_performance_stat/') }}");
		$("#test_trends").load("{{ url('lab/test_trends/') }}");
		$("#rejected").load("{{ url('lab/rejection_trends/') }}");
		// $("#poc_outcomes").load("{{ url('lab/poc_performance_details/') }}");

		if(filter_value && filter_value != 'null'){
			// $("#lab_facility_rejections").load("{{ url('lab/lab_site_rejections/') }}");

			// $("#graphs").load("{{ url('lab/lab_trends/') }}");
		}

	}

	function expand_modal(div_name)
	{
		$(div_name).modal('show');
	}

	function expand_poc(facility_id)
	{
		$("#my_empty_div").load("{{ url('lab/poc_performance_details') }}/"+facility_id);
	}


	$().ready(function(){
			
		$("#first").show();
    	$("#second").hide();
    	$("#fourth").hide();
    	$("#fifth").hide();
    	$("#breadcrum").hide();


		$("select").change(function(){			
			var filter_value = $(this).val();

			if(filter_value == 'null'){			
				$("#first").show();
	        	$("#second").hide();
	        	$("#fourth").hide();
	        	$("#breadcrum").hide();
			}else{	     
				$("#first").hide();
	        	$("#second").show();
	        	// $("#fourth").show();

	        	if(filter_value == 11 || filter_value == '11'){
	        		$("#fifth").show();
	        	}
			}
		});
	    date_filter('yearly', "{{ date('Y') }}");
	    // reload_page();
	});

</script>

@endsection
