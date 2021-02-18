@extends('layouts.master')

@section('content')

<div class="row">
  <div class="col-md-12" id="poctatdiv">
    <div class="col-md-4">
      
    </div>
    <div class="col-md-5">
      <div class="col-md-4 title-name" id="title">
        <center>POC TAT <l style="color:red;">(Days)</l></center>
      </div>
      <div class="col-md-8">
        <div id="poctat"></div>
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
          <div class="key cr"><center>Collection Receipt (C-R)</center></div>
          <div class="key rp"><center>Receipt to Processing (R-P)</center></div>
        </div>
        <div class="col-md-6">
          <div class="key pd"><center>Processing Dispatch (P-D)</center></div>
          <div class="key"><center><div class="cd"></div>Collection Dispatch (C-D)</center></div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Testing Trends <div class="display_date"></div>
            </div>
            <div class="panel-body">
                <div id="testing_trends"><center><div class="loader"></div></center></div>
                <div class="col-md-12" style="margin-top: 1em;margin-bottom: 1em;">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-5 col-sm-12 col-xs-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            Summary Outcomes <div class="display_date" ></div>
          </div>
          <div class="panel-body" id="vl_outcomes">
            <center><div class="loader"></div></center>
          </div>
          
        </div>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            Outcomes By Age <div class="display_date" ></div>
          </div>
          <div class="panel-body" id="ages">
            <center><div class="loader"></div></center>
          </div>
          
        </div>
    </div>

    <div class="col-md-3 col-sm-12 col-xs-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            Outcomes By Gender <div class="display_date" ></div>
          </div>
          <div class="panel-body" id="gender">
            <center><div class="loader"></div></center>
          </div>
          
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                County Outcomes <div class="display_date"></div>
            </div>
            <div class="panel-body">
                <div id="county_outcomes"><center><div class="loader"></div></center></div>
                <div class="col-md-12" style="margin-top: 1em;margin-bottom: 1em;">
                </div>
            </div>
        </div>
    </div>
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


<div id="my_empty_div"></div>
		

@endsection


@section('scripts')

<script type="text/javascript">

	function reload_page()
	{
		// var filter_value = $("#{{ $filter_name }}").val();

        $("#testing_trends").html("<div>Loading...</div>");
        $("#vl_outcomes").html("<div>Loading...</div>");
        $("#gender").html("<div>Loading...</div>");
        $("#ages").html("<div>Loading...</div>");
        $("#poc").html("<div>Loading...</div>");
        $("#poctat").html("<div>Loading...</div>");
        $("#county_outcomes").html("<div>Loading...</div>");

        $("#poctat").load("{{ url('summary/turnaroundtime') }}");
        $("#testing_trends").load("{{ url('summary/outcomes/10/1') }}");
        $("#vl_outcomes").load("{{ url('summary/vl_outcomes/10') }}");
        $("#gender").load("{{ url('summary/gender/10') }}");
        $("#ages").load("{{ url('summary/age/10') }}");
        $("#poc").load("{{ url('lab/poc_performance_stat') }}");
		$("#county_outcomes").load("{{ url('summary/outcomes/10') }}");
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
	    date_filter('yearly', "{{ date('Y') }}");
	    // reload_page();
	});

</script>

@endsection
