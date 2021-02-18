@extends('layouts.master')

@section('content')

<div class="row">
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
</div>

<div class="row">
	<center><h3>Current suppression rates <div class="display_current_range"></div></h3></center>
</div>

<div class="row">
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
			  Counties
			</div>
		  	<div class="panel-body">
		  	<div id="countys">
		  		<div>Loading...</div>
		  	</div>
		  	<!-- -->
		  </div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
			  Sub counties 
			</div>
		  	<div class="panel-body">
		  	<div id="subcounty">
		  		<div>Loading...</div>
		  	</div>
		  </div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
			  Facilities 
			</div>
		  	<div class="panel-body">
		  	<div id="facilities">
		  		<div>Loading...</div>
		  	</div>
		  </div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
			  Partners
			</div>
		  	<div class="panel-body">
		  	<div id="partners">
		  		<div>Loading...</div>
		  	</div>
		  	<!-- -->
		  </div>
		</div>
	</div>
</div>

<div class="row">
	<center><h3>Current suppression suppressed age data <div class="display_current_range"></div></h3></center>
</div>

<div class="row">
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
			  Counties
			</div>
		  	<div class="panel-body">
		  	<div id="countys_a">
		  		<div>Loading...</div>
		  	</div>
		  	<!-- -->
		  </div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
			  Sub counties 
			</div>
		  	<div class="panel-body">
		  	<div id="subcounty_a">
		  		<div>Loading...</div>
		  	</div>
		  </div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
			  Facilities 
			</div>
		  	<div class="panel-body">
		  	<div id="facilities_a">
		  		<div>Loading...</div>
		  	</div>
		  </div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
			  Partners
			</div>
		  	<div class="panel-body">
		  	<div id="partners_a">
		  		<div>Loading...</div>
		  	</div>
		  	<!-- -->
		  </div>
		</div>
	</div>
</div>

<div class="row">
	<center><h3>Current suppression non suppressed age data <div class="display_current_range"></div></h3></center>
</div>

<div class="row">
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
			  Counties
			</div>
		  	<div class="panel-body">
		  	<div id="countys_na">
		  		<div>Loading...</div>
		  	</div>
		  	<!-- -->
		  </div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
			  Sub counties 
			</div>
		  	<div class="panel-body">
		  	<div id="subcounty_na">
		  		<div>Loading...</div>
		  	</div>
		  </div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
			  Facilities 
			</div>
		  	<div class="panel-body">
		  	<div id="facilities_na">
		  		<div>Loading...</div>
		  	</div>
		  </div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
			  Partners
			</div>
		  	<div class="panel-body">
		  	<div id="partners_na">
		  		<div>Loading...</div>
		  	</div>
		  	<!-- -->
		  </div>
		</div>
	</div>
</div>

<div class="row">
	<center><h3>Current suppression gender data <div class="display_current_range"></div></h3></center>
</div>

<div class="row">
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
			  Counties
			</div>
		  	<div class="panel-body">
		  	<div id="countys_g">
		  		<div>Loading...</div>
		  	</div>
		  	<!-- -->
		  </div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
			  Sub counties 
			</div>
		  	<div class="panel-body">
		  	<div id="subcounty_g">
		  		<div>Loading...</div>
		  	</div>
		  </div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
			  Facilities 
			</div>
		  	<div class="panel-body">
		  	<div id="facilities_g">
		  		<div>Loading...</div>
		  	</div>
		  </div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
			  Partners
			</div>
		  	<div class="panel-body">
		  	<div id="partners_g">
		  		<div>Loading...</div>
		  	</div>
		  	<!-- -->
		  </div>
		</div>
	</div>
</div>

@endsection


@section('scripts')

<script type="text/javascript">

	function reload_page()
	{
		$("#countys").html("<div>Loading...</div>");
		$("#partners").html("<div>Loading...</div>");
		$("#subcounty").html("<div>Loading...</div>");
		$("#facilities").html("<div>Loading...</div>");

		$("#countys_g").html("<div>Loading...</div>");
		$("#partners_g").html("<div>Loading...</div>");
		$("#subcounty_g").html("<div>Loading...</div>");
		$("#facilities_g").html("<div>Loading...</div>");


		$("#countys_a").html("<div>Loading...</div>");
		$("#partners_a").html("<div>Loading...</div>");
		$("#subcounty_a").html("<div>Loading...</div>");
		$("#facilities_a").html("<div>Loading...</div>");

		$("#countys_na").html("<div>Loading...</div>");
		$("#partners_na").html("<div>Loading...</div>");
		$("#subcounty_na").html("<div>Loading...</div>");
		$("#facilities_na").html("<div>Loading...</div>");


		$("#current_sup").load("{{ url('summary/current_suppression') }}");

		$("#countys").load("{{ url('summary/suppression_listings/1') }}");
		$("#subcounty").load("{{ url('summary/suppression_listings/2') }}");
		$("#partners").load("{{ url('summary/suppression_listings/3') }}");
		$("#facilities").load("{{ url('summary/suppression_listings/4') }}");

		$("#countys_g").load("{{ url('summary/suppression_gender_listings/1') }}");
		$("#partners_g").load("{{ url('summary/suppression_gender_listings/3') }}");
		$("#subcounty_g").load("{{ url('summary/suppression_gender_listings/2') }}");
		$("#facilities_g").load("{{ url('summary/suppression_gender_listings/4') }}");

		$("#countys_a").load("{{ url('summary/suppression_age_listings/1/1') }}");
		$("#subcounty_a").load("{{ url('summary/suppression_age_listings/2/1') }}");
		$("#partners_a").load("{{ url('summary/suppression_age_listings/3/1') }}");
		$("#facilities_a").load("{{ url('summary/suppression_age_listings/4/1') }}");

		$("#countys_na").load("{{ url('summary/suppression_age_listings/1/0') }}");
		$("#subcounty_na").load("{{ url('summary/suppression_age_listings/2/0') }}");
		$("#partners_na").load("{{ url('summary/suppression_age_listings/3/0') }}");
		$("#facilities_na").load("{{ url('summary/suppression_age_listings/4/0') }}");

		$("#long_tracking").load("{{ url('summary/get_patients') }}");
		$("#current_sup_dynamic").load("{{ url('summary/get_current_suppresion') }}");
	}


	$().ready(function(){
	    date_filter('yearly', "{{ date('Y') }}");
	    // reload_page();
	});

</script>

@endsection