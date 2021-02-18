@extends('layouts.master')

@section('content')

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

<div id="my_empty_div"></div>

@endsection

@section('scripts')

<script type="text/javascript">

  $().ready(function() {

    $("#lab_perfomance_stats").load("https://covid-19-kenya.org/dash_labs");
            
  });
   
</script>

@endsection