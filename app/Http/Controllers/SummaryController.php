<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lookup;
use DB;
use Str;

class SummaryController extends Controller
{

	public function turnaroundtime($nat=true)
	{
		extract($this->get_filters());

		$type = 0;
		$id = 0;
		if ($county) {
			$type = 1; 
			$id = $county;
		}

 		if ($nat) {
 			$sql = "CALL `proc_get_national_tat`('".$year."','".$month."','".$to_year."','".$to_month."')";
 		} else {
 			$sql = "CALL `proc_get_poc_tat`('".$year."','".$month."','".$to_year."','".$to_month."')";
 		}

		$rows = DB::select($sql);

		$count =  $tat1 = $tat2 = $tat3 = $tat4 = 0;
		$tat = [];

		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);
			if (($value['tat1']!=0) || ($value['tat2']!=0) || ($value['tat3']!=0) || ($value['tat4']!=0)) {
				$count++;

				$tat1 += $value['tat1'];
				$tat2 += $value['tat2'];
				$tat3 += $value['tat3'];
				$tat4 += $value['tat4'];
			}
		}
		if(!$count) $count = 1;

		$tat1 = round($tat1 / $count);
		$tat2 = round($tat2 / $count) + $tat1;
		$tat3 = round($tat3 / $count) + $tat2;
		$tat4 = round($tat4 / $count);

		$div = Str::random(15);
		// return null;

		return view('charts.tat', compact('tat1', 'tat2', 'tat3', 'tat4', 'div'));
	}

	public function vl_coverage()
	{
		extract($this->get_filters());

		$current_suppression = $this->get_patients_data();
		// echo "<pre>";print_r($current_suppression);die();
		$data['div'] = Str::random(15);

		$data['coverage'] = round($current_suppression['coverage']);
		if ($data['coverage'] < 51) {
			$data['color'] = 'rgba(255,0,0,0.5)';
		} else if ($data['coverage'] > 50 && $data['coverage'] < 71) {
			$data['color'] = 'rgba(255,255,0,0.5)';
		} else if ($data['coverage'] > 70) {
			$data['color'] = 'rgba(0,255,0,0.5)';
		}
		
		return view('charts.coverage', $data);
		// return $data;
	}

	public function outcomes($division=1, $second_division=null)
	{
		extract($this->get_filters());

		if($division == 1){
			if ($second_division == 4) {
				$sql = "CALL `proc_get_county_sites_outcomes`('".$county."','".$year."','".$month."','".$to_year."','".$to_month."')";
			} 
			else if ($second_division == 3) {
				$sql = "CALL `proc_get_vl_county_partner_outcomes`('".$county."','".$year."','".$month."','".$to_year."','".$to_month."')";
			} 
			else{
				$sql = "CALL `proc_get_county_outcomes`('".$year."','".$month."','".$to_year."','".$to_month."')";
			}
		}
		else if($division == 3){
			if($second_division == 4){
				$sql = "CALL `proc_get_partner_sites_outcomes`('".$partner."','".$year."','".$month."','".$to_year."','".$to_month."')";
			}
			else if($second_division == 1){
				$sql = "CALL `proc_get_vl_partner_county_details`('".$partner."','".$year."','".$month."','".$to_year."','".$to_month."')";
			}
			else{
				$sql = "CALL `proc_get_partner_outcomes`('".$year."','".$month."','".$to_year."','".$to_month."')";
			}
		}
		else if($division == 4){
			$sql = "CALL `proc_get_all_sites_outcomes`('".$year."','".$month."','".$to_year."','".$to_month."')";
		}
		else if($division == 10){
			if($second_division == 1){
				if(!$county) $county = 0;
				$sql = "CALL `proc_get_vl_poc_trends`('".$county."','".$year."','".$month."','".$to_year."','".$to_month."')";
			}else{
				$sql = "CALL `proc_get_vl_poc_county_trends`('".$year."','".$month."','".$to_year."','".$to_month."')";
			}
		}

		$rows = DB::select($sql);

		$data = $this->bars(['Not Suppressed', 'LLV', 'LDL', 'Suppression', config('var.suppression_target') . '% Target'], 'column', ['#F2784B', '#66ff66', '#1BA39C'], ['', '', '', ' %', ' %']);
		$this->columns($data, 3, 4, 'spline');
		$this->yAxis($data, 0, 2);


		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);

			$suppressed = isset($value['suppressed']) ? $value['suppressed'] : ($value['less1000'] + $value['undetected']);
			$nonsuppressed = isset($value['nonsuppressed']) ? $value['nonsuppressed'] : ($value['less5000'] + $value['above5000']);

			if($division == 10 && $second_division){
				$data['categories'][$key] = $this->resolve_month($value['month']).'-'.$value['year'];
				// $data['categories'][$key] = json_encode($value);
			}
			else{
				$data['categories'][$key] = $value['name'] ?? $value['partnername'] ?? $value['county'] ?? $value['countyname'] ?? '';
			}
			$data['outcomes'][0]['data'][$key] = (int) ($value['nonsuppressed'] ?? $nonsuppressed);
			$data['outcomes'][1]['data'][$key] = (int) ($value['less1000'] ?? 0);
			$data['outcomes'][2]['data'][$key] = (int) ($value['undetected'] ?? 0);
			if($suppressed || $nonsuppressed){
				$data['outcomes'][3]['data'][$key] = round(@(((int) $suppressed*100)/((int) $suppressed+(int) $nonsuppressed)),1);
			}else{
				$data['outcomes'][3]['data'][$key] = 0;
			}
			$data['outcomes'][4]['data'][$key] = config('var.suppression_target');
		}
		return view('charts.dual_axis', $data);
	}

	public function vl_outcomes($division=null)
	{
		extract($this->get_filters());


		if($division == 10){
			if(!$county) $county = 0;
			$sql = "CALL `proc_get_county_poc_vl_outcomes`('".$county."','".$year."','".$month."','".$to_year."','".$to_month."')";
		}
		else if ($partner || $partner === 0) {
			$sql = "CALL `proc_get_partner_vl_outcomes`('".$partner."','".$year."','".$month."','".$to_year."','".$to_month."')";
			$sql2 = "CALL `proc_get_partner_sitessending`('".$partner."','".$year."','".$month."','".$to_year."','".$to_month."')";
			$sql3 = "CALL `proc_get_vl_pmtct`('0', '".$year."','".$month."','".$to_year."','".$to_month."', '0', '0', '".$partner."', '0', '0')";
			// $sql3 = "CALL `proc_get_vl_current_suppression`('3','".$partner."')";
		}
		else if($county) {
			$sql = "CALL `proc_get_regional_vl_outcomes`('".$county."','".$year."','".$month."','".$to_year."','".$to_month."')";
			$sql2 = "CALL `proc_get_regional_sitessending`('".$county."','".$year."','".$month."','".$to_year."','".$to_month."')";
			$sql3 = "CALL `proc_get_vl_pmtct`('0', '".$year."','".$month."','".$to_year."','".$to_month."', '0', '".$county."', '0', '0', '0')";
			// $sql3 = "CALL `proc_get_vl_current_suppression`('1','".$county."')";			
		}
		else if($subcounty) {
			$sql = "CALL `proc_get_vl_subcounty_vl_outcomes`('".$subcounty."','".$year."','".$month."','".$to_year."','".$to_month."')";
			$sql2 = "CALL `proc_get_vl_current_suppression`('2','".$subcounty."')";
			$sql3 = "CALL `proc_get_vl_pmtct`('0', '".$year."','".$month."','".$to_year."','".$to_month."', '0', '0', '0', '".$subcounty."', '0')";
		}
		else if($site) {
			$sql = "CALL `proc_get_sites_vl_outcomes`('".$site."','".$year."','".$month."','".$to_year."','".$to_month."')";

			$sql3 = "CALL `proc_get_vl_pmtct`('0', '".$year."','".$month."','".$to_year."','".$to_month."', '0', '0', '0', '0', '".$site."')";
		}
		else if($regimen) {
			$sql = "CALL `proc_get_vl_regimen_vl_outcomes`('".$regimen."','".$year."','".$month."','".$to_year."','".$to_month."')";
		}
		else if($age_cat) {
			$sql = "CALL `proc_get_vl_age_vl_outcomes`('".$age_cat."','".$year."','".$month."','".$to_year."','".$to_month."')";
		}
		else if($agency) {
			$sql = "CALL `proc_get_vl_funding_agencies_outcomes`('".$year."','".$month."','".$to_year."','".$to_month."','".'0'."','".$agency."')";
			$sql2 = "CALL `proc_get_vl_fundingagencies_sitessending`('".$year."','".$month."','".$to_year."','".$to_month."','".'0'."','".$agency."')";
		}
		else{
			$sql = "CALL `proc_get_national_vl_outcomes`('".$year."','".$month."','".$to_year."','".$to_month."')";
			$sql2 = "CALL `proc_get_national_sitessending`('".$year."','".$month."','".$to_year."','".$to_month."')";
			$sql3 = "CALL `proc_get_vl_pmtct`('0', '".$year."','".$month."','".$to_year."','".$to_month."', '1', '0', '0', '0','0')";
			// $sql3 = "CALL `proc_get_vl_current_suppression`('0','0')";			
		}

		$rows = DB::select($sql);
		$pmtct = NULL;
		if(isset($sql2)) $sitessending = DB::select($sql2);
		if (isset($sql3)) $pmtct = DB::select($sql3);

		$color = array('#6BB9F0', '#F2784B', '#1BA39C', '#5C97BF');

		$data['div'] = Str::random(15);
 
		$data['outcomes']['name'] = 'Tests';
		$data['outcomes']['colorByPoint'] = true;
		$data['paragraph'] = '';
 
		$data['outcomes']['data'][0]['name'] = '&lt;= 400';
		$data['outcomes']['data'][1]['name'] = '401 - 999';
		$data['outcomes']['data'][2]['name'] = 'Not Suppressed';
 
		$count = 0;
 
		$data['outcomes']['data'][0]['y'] = $count;
		$data['outcomes']['data'][1]['y'] = $count;
		$data['outcomes']['data'][2]['y'] = $count;

		$value = get_object_vars($rows[0]);

		$total = (int) ($value['undetected'] + $value['less1000'] + $value['less5000'] + $value['above5000']);
		$less = (int) ($value['undetected'] + $value['less1000']);
		$greater = (int) ($value['less5000'] + $value['above5000']);
		$non_suppressed = $greater + (int) $value['confirm2vl'];
		$total_tests = (int) $value['confirmtx'] + $total + (int) $value['baseline'];
		
		// 	<td colspan="2">Cumulative Tests (All Samples Run):</td>
    	// 	<td colspan="2">'.number_format($value['alltests']).'</td>
    	// </tr>
    	// <tr>
		$data['paragraph'] .= "<table class='table'> " .
			'<tr>
	    		<td colspan="2">Total VL tests done:</td>
	    		<td colspan="2">'.number_format($total_tests ).'</td>
	    	</tr>

			<tr>
	    		<td>&nbsp;&nbsp;&nbsp;Routine VL Tests with Valid Outcomes:</td>
	    		<td>'.number_format($total).'</td>
	    		<td>Proportion of Routine VL Tests suppressed</td>
	    		<td>'.number_format(round((@($total/$total_tests)*100), 2)).'%</td>
	    	</tr>

	    	<tr>
	    		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Valid Tests &gt;= 1000 copies/ml (HVL):</td>
	    		<td>'.number_format($greater).'</td>
	    		<td>Proportion of Valid HVL Tests Suppressed</td>
	    		<td>'.round((@($greater/$total)*100),2).'%</td>
	    	</tr>

			<tr>
	    		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Valid Tests &lt= 1000 copies/ml:</td>
	    		<td>'.number_format($less).'</td>
	    		<td>Proportion of Valid Tests &lt= 1000 copies/ml Suppressed</td>
	    		<td>'.number_format(round((@($less/$total)*100), 2)).'%</td>
	    	</tr>

	    	<tr>
	    		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Valid Tests &lt= 400 copies/ml (LDL):</td>
	    		<td>'.number_format($value['undetected']).'</td>
	    		<td>Proportion of Valid LDL Tests Suppressed</td>
	    		<td>'.round((@($value['undetected']/$total)*100),2).'%</td>
	    	</tr>

	    	<tr>
	    		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Valid Tests 401 - 999 copies/ml (LLV):</td>
	    		<td>'.number_format($value['less1000']).'</td>
	    		<td>Proportion of Valid LLV Tests Suppressed</td>
	    		<td>'.round((@($value['less1000']/$total)*100),2).'%</td>
	    	</tr>
	    	<tr>
	    		<td>&nbsp;&nbsp;&nbsp;Confirmatory Repeat Tests:</td>
	    		<td>'.number_format($value['confirmtx']).'</td>
	    		<td>Non Suppression ( &gt;= 1000cp/ml)</td>
	    		<td>'.number_format($value['confirm2vl']). ' (' .round(@($value['confirm2vl'] * 100 / $value['confirmtx']), 2). '%)' .'</td>
	    	</tr>
	    	<tr>
	    		<td>&nbsp;&nbsp;&nbsp;Baseline VLs:</td>
	    		<td>'.number_format($value['baseline']).'</td>
	    		<td>Non Suppression ( &gt;= 1000cp/ml)</td>
	    		<td>'.number_format($value['baselinesustxfail']). ' (' .round(@($value['baselinesustxfail'] * 100 / $value['baseline']), 2). '%)' .'</td>
	    	</tr>';

	    if (isset($pmtct)) {
	    	foreach ($pmtct as $key => $line) {
	    		$line_tests = ($line->undetected + $line->less1000 + $line->less5000 + $line->above5000);
	    		$line_suppressed = ($line->undetected + $line->less1000);
	    		$data['paragraph'] .= '<tr>
		    		<td>' . $line->pmtcttype . ':</td>
		    		<td>'.number_format($line_tests).'</td>
		    		<td>Percentage Suppression</td>
		    		<td>'. round(@(($line_suppressed*100)/$line_tests), 2, PHP_ROUND_HALF_UP).'%</td>
		    	</tr>';
	    	}
	    }

	    $data['paragraph'] .= '<tr>
	    		<td>
	    			Rejected Samples:
	    			<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="left" title="Rejected samples are samples that are not processed once gotten to the lab due to issues like sample damage, lack of request form accompaning the samples etc." style="padding-left: 2px;padding-right: 2px;padding-top: 2px;padding-bottom: 2px;"><i>?</i></button>
	    		</td>
	    		<td>'.number_format($value['rejected']).'</td>
	    		<td>Percentage Rejection Rate</td>
	    		<td>'. round(@(($value['rejected']*100)/$value['received']), 2, PHP_ROUND_HALF_UP).'%</td>
	    	</tr>';
    	/*
			<tr>
	    		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Valid Tests &lt; LDL:</td>
	    		<td>'.number_format($value['undetected']).'</td>
	    		<td>Percentage Undetectable</td>
	    		<td>'.round((@($value['undetected']/$total)*100),1).'%</td>
	    	</tr>
    	*/
					
		$data['outcomes']['data'][0]['y'] = (int) $value['undetected'];
		$data['outcomes']['data'][1]['y'] = (int) $value['less1000'];
		$data['outcomes']['data'][2]['y'] = (int) $value['less5000']+(int) $value['above5000'];

		$data['outcomes']['data'][0]['color'] = '#1BA39C';
		$data['outcomes']['data'][1]['color'] = '#66ff66';
		$data['outcomes']['data'][2]['color'] = '#F2784B';


		$count = $sites = 0;

		if(isset($sql2) && !$subcounty){

			foreach ($sitessending as $key => $value) {
				$value = get_object_vars($value);

				if ((int) $value['sitessending'] != 0) {
					$sites = (int) $sites + (int) $value['sitessending'];
					$count++;
				}
			}
			// echo "<pre>";print_r($sites);echo "<pre>";print_r($count);echo "<pre>";print_r(round(@$sites / $count));die();
			$data['paragraph'] .= "<tr>
						<td>Average Sites Sending:</td>
						<td>".number_format(round(@($sites / $count)))."</td>
						<td>Total Sites Ever Sent:</td>
						<td>".number_format($sites)."</td>
					</tr>";
		}
		$data['paragraph'] .= '</table>';
		$data['outcomes']['data'][2]['sliced'] = true;
		$data['outcomes']['data'][2]['selected'] = true;

		return view('charts.pie_chart', $data);
	}

	public function justification()
	{
		extract($this->get_filters());


		if ($partner || $partner === 0) {
			$sql = "CALL `proc_get_partner_justification`('".$partner."','".$year."','".$month."','".$to_year."','".$to_month."')";
		}else if($county) {
			$sql = "CALL `proc_get_regional_justification`('".$county."','".$year."','".$month."','".$to_year."','".$to_month."')";
		}else if($site) {
			$sql = "CALL `proc_get_vl_site_justification`('".$site."','".$year."','".$month."','".$to_year."','".$to_month."')";
		}else if($agency) {
			$sql = "CALL `proc_get_vl_fundingagencies_justification`('".$year."','".$month."','".$to_year."','".$to_month."','".'0'."','".$agency."')";
		}else {
			$sql = "CALL `proc_get_national_justification`('".$year."','".$month."','".$to_year."','".$to_month."')";
		}

		$rows = DB::select($sql);

		$data['div'] = Str::random(15);
		
		$data['outcomes']['data'][0]['name'] = 'Not Defined';
		$data['outcomes']['data'][0]['y'] = 0;
		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);
			
			$data['outcomes']['data'][$key]['name'] = $value['name'];
			$data['outcomes']['data'][$key]['y'] = (int) $value['justifications'];
		}
		return view('charts.pie_chart', $data);
	}


	public function age($division=null)
	{
		extract($this->get_filters());

		if($division == 10){
			if(!$county) $county = 0;
			$sql = "CALL `proc_get_vl_regional_poc_age`('".$county."','".$year."','".$month."','".$to_year."','".$to_month."')";
		}
		else if ($partner || $partner === 0) {
			$sql = "CALL `proc_get_partner_age`('".$partner."','".$year."','".$month."','".$to_year."','".$to_month."')";
		}else if($county) {
			$sql = "CALL `proc_get_regional_age`('".$county."','".$year."','".$month."','".$to_year."','".$to_month."')";
		}else if($subcounty) {
			$sql = "CALL `proc_get_vl_subcounty_age`('".$subcounty."','".$year."','".$month."','".$to_year."','".$to_month."')";
		}else if($site) {
			$sql = "CALL `proc_get_sites_age`('".$site."','".$year."','".$month."','".$to_year."','".$to_month."')";
		}else if($regimen) {
			$sql = "CALL `proc_get_vl_regimen_age`('".$regimen."','".$year."','".$month."','".$to_year."','".$to_month."')";
		}else if($agency) {
			$sql = "CALL `proc_get_vl_fundingagencies_age`('".$year."','".$month."','".$to_year."','".$to_month."','".'0'."','".$agency."')";
		}else {
			$sql = "CALL `proc_get_national_age`('".$year."','".$month."','".$to_year."','".$to_month."')";
		}

		$rows = DB::select($sql);

		$data = $this->bars(['Not Suppressed', 'LLV', 'LDL'], 'column', ['#F2784B', '#66ff66', '#1BA39C']);
		$data['categories'][0] = 'Not Defined';
		$data["outcomes"][0]["data"][0]	= 0;
		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);
			$data['categories'][$key] 			= $value['name'] ?? json_encode($value);
			$data["outcomes"][0]["data"][$key]	=  (int) (($value['less5000'] ?? 0) + ($value['above5000'] ?? 0));
			$data["outcomes"][1]["data"][$key]	=  (int) ($value['less1000'] ?? 0);
			$data["outcomes"][2]["data"][$key]	=  (int) ($value['undetected'] ?? 0);
		}
		return view('charts.bar_graph', $data);
	}


	public function gender($division=null)
	{
		extract($this->get_filters());

		if($division == 10){
			if(!$county) $county = 0;
			$sql = "CALL `proc_get_vl_regional_poc_gender`('".$county."','".$year."','".$month."','".$to_year."','".$to_month."')";
		}
		else if ($partner || $partner === 0) {
			$sql = "CALL `proc_get_partner_gender`('".$partner."','".$year."','".$month."','".$to_year."','".$to_month."')";
		}else if($county) {
			$sql = "CALL `proc_get_regional_gender`('".$county."','".$year."','".$month."','".$to_year."','".$to_month."')";
		}else if($subcounty) {
			$sql = "CALL `proc_get_vl_subcounty_gender`('".$subcounty."','".$year."','".$month."','".$to_year."','".$to_month."')";
		}else if($site) {
			$sql = "CALL `proc_get_sites_gender`('".$site."','".$year."','".$month."','".$to_year."','".$to_month."')";
		}else if($regimen) {
			$sql = "CALL `proc_get_vl_regimen_gender`('".$regimen."','".$year."','".$month."','".$to_year."','".$to_month."')";
		}else if($agency) {
			$sql = "CALL `proc_get_vl_fundingagencies_gender`('".$year."','".$month."','".$to_year."','".$to_month."','".'0'."','".$agency."')";
		}else {
			$sql = "CALL `proc_get_national_gender`('".$year."','".$month."','".$to_year."','".$to_month."')";
		}

		
		$rows = DB::select($sql);
		$data = $this->bars(['Not Suppressed', 'LLV', 'LDL'], 'column', ['#F2784B', '#66ff66', '#1BA39C']);
		$data['categories'][0] = 'Not Defined';
		$data["outcomes"][0]["data"][0]	= 0;
		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);

			$data['categories'][$key] 			= $value['name'] ?? json_encode($value);
			$data["outcomes"][0]["data"][$key]	=  (int) (($value['less5000'] ?? 0) + ($value['above5000'] ?? 0));
			$data["outcomes"][1]["data"][$key]	=  (int) ($value['less1000'] ?? 0);
			$data["outcomes"][2]["data"][$key]	=  (int) ($value['undetected'] ?? 0);
		}
		return view('charts.bar_graph', $data);
	}


	public function sample_types($all=true)
	{
		extract($this->get_filters());

		$type = $id = 0;
		$multipleID = '';
		
 		if ($county){$type = 1; $id = $county;}
 		else if($subcounty){$type = 2; $id = $subcounty;}
 		else if($site){$type = 3; $id = $site;}
 		else if($partner){$type = 4; $id = $partner;}
 		else if($lab){$type = 5; $id = $lab;}
 		else if($regimen){$type = 6; $id = $regimen;}
 		else if($age_cat){$type = 7; $multipleID = $age_cat;}

		$sql = "CALL `proc_get_vl_sample_types_trends`('".$type."','".$id."','".$year."','".$month."','".$to_year."','".$to_month."','".$multipleID."')";

		

		$rows = DB::select($sql);
		$data = $this->bars(['Not Suppressed', 'LLV', 'LDL']);
		if ($all)
			$data = $this->bars(['Not Suppressed', 'LLV', 'LDL', 'Invalids']);

		// $plasma = 'plasma';
		// $edta = 'edta';
		// $dbs = 'dbs';

		// if($all){
		// 	$plasma = 'all' . $plasma;
		// 	$edta = 'all' . $edta;
		// 	$dbs = 'all' . $dbs;
		// }
		$index = 0;
		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);

			$data['categories'][$key] 			= $this->resolve_month($value['month']).'-'.$value['year'];
			$data["outcomes"][0]["data"][$key]	=  ((int) $value['less5000'] + (int) $value['above5000']);
			$data["outcomes"][1]["data"][$key]	=  (int) $value['less1000'];
			$data["outcomes"][2]["data"][$key]	=  (int) $value['undetected'];
			if ($all)
				$data["outcomes"][3]["data"][$key]	=  (int) $value['invalids'];
		}
		return view('charts.bar_graph', $data);
	}

	public function get_patients()
	{
		return view('charts.longitudinal_view', $this->get_patients_data());
	}

	public function get_patients_data()
	{
		extract($this->get_filters());

		$sql;

		if ($to_year == 0) {
			$to_year = $year;
			$year = $year-1;
			$month = $to_month = (int)date('m');
		}
		
		if ($partner || $partner === 0) {
			$params = "patient/partner/{$partner}/{$api_type}/{$year}/{$month}/{$to_year}/{$to_month}";
		} 
		else if($county){
			$c = DB::table('countys')->where('id', $county)->first()->CountyMFLCode;
			$params = "patient/county/{$c}/{$api_type}/{$year}/{$month}/{$to_year}/{$to_month}";
		}
		else if($subcounty){
			$c = DB::table('districts')->where('id', $subcounty)->first()->SubCountyDHISCode;
			$params = "patient/subcounty/{$c}/{$api_type}/{$year}/{$month}/{$to_year}/{$to_month}";
		}
		else if($site){
			$c = DB::table('facilitys')->where('id', $site)->first()->facilitycode;
			$params = "patient/facility/{$c}/{$api_type}/{$year}/{$month}/{$to_year}/{$to_month}";
		}
		else{
			$params = "patient/national/{$api_type}/{$year}/{$month}/{$to_year}/{$to_month}";
		}

		$result = $this->req($params);	

		$data['div'] = Str::random(15);

		$data['outcomes'][0]['name'] = "Patients grouped by tests received";

		$data['title'] = " ";

		$data['unique_patients'] = 0;
		$data['size'] = 0;
		// $data['total_patients'] = $res->totalartmar;
		$data['total_patients'] = $result->art;
		$data['as_at'] = $result->as_at;
		$data['total_tests'] = 0;

		foreach ($result->unique as $key => $value) {

			$data['categories'][$key] = (int) $value->tests;
		
			$data['outcomes'][0]['data'][$key] = (int) $value->totals;
			$data['unique_patients'] += (int) $value->totals;
			$data['total_tests'] += ($data['categories'][$key] * $data['outcomes'][0]['data'][$key]);
			$data['size']++;

		}

		$data['coverage'] = round(@($data['unique_patients'] / $data['total_patients'] * 100), 2);
		if ($data['coverage'] ==INF) {
			$data['coverage'] = 0;
		}

		return $data;
	}

	public function get_current_suppresion()
	{
		extract($this->get_filters());


		if ($partner || $partner === 0) {
			$params = "patient/suppression/partner/{$partner}/{$api_type}/{$year}/{$month}/{$to_year}/{$to_month}";
		}
		else if ($county) {
			$c = DB::table('countys')->where(['id' => $county])->first()->CountyMFLCode;
			$params = "patient/suppression/county/{$c}/{$api_type}/{$year}/{$month}/{$to_year}/{$to_month}";
		}
		else if($subcounty){
			$c = DB::table('districts')->where('id', $subcounty)->first()->SubCountyDHISCode;
			$params = "patient/suppression/subcounty/{$c}/{$api_type}/{$year}/{$month}/{$to_year}/{$to_month}";
		}
		else if($site){
			$c = DB::table('facilitys')->where('id', $site)->first()->facilitycode;
			$params = "patient/suppression/facility/{$c}/{$api_type}/{$year}/{$month}/{$to_year}/{$to_month}";
		}
		else{
			$params = "patient/suppression/national/{$api_type}/{$year}/{$month}/{$to_year}/{$to_month}";
		}

		/*
			API CALL
	
		*/
		$result = $this->req($params);	


		$data['div'] = Str::random(15);

		$data['outcomes']['name'] = 'Tests';
		$data['outcomes']['colorByPoint'] = true;
		$data['paragraph'] = '';

		$data['outcomes']['data'][0]['name'] = '<= 400 copies/ml (LDL)';
		$data['outcomes']['data'][1]['name'] = '401 - 999 copies/ml (LLV)';
		$data['outcomes']['data'][2]['name'] = '>= 1000 copies/ml (HVL)';
		
		$data['outcomes']['data'][0]['y'] = (int) $result->rcategory1;
		$data['outcomes']['data'][1]['y'] = (int) $result->rcategory2;
		$data['outcomes']['data'][2]['y'] = (int) $result->rcategory3 + (int) $result->rcategory4;
		
		$data['outcomes']['data'][0]['z'] = number_format($result->rcategory1);
		$data['outcomes']['data'][1]['z'] = number_format($result->rcategory2);
		$data['outcomes']['data'][2]['z'] = number_format($result->rcategory3 + $result->rcategory4);

		$data['outcomes']['data'][0]['color'] = '#1BA39C';
		$data['outcomes']['data'][1]['color'] = '#66ff66';
		$data['outcomes']['data'][2]['color'] = '#F2784B';

		$data['outcomes']['data'][0]['sliced'] = true;
		$data['outcomes']['data'][0]['selected'] = true;

		$data['paragraph'] = "<p>  ";
		$data['paragraph'] .= "<= 400 copies/ml - " . number_format($result->rcategory1) . "<br />";
		$data['paragraph'] .= "401 - 999 copies/ml - " . number_format($result->rcategory2) . "<br />";
		$data['paragraph'] .= "Non Suppressed - " . number_format($result->rcategory3 + $result->rcategory4) . "<br />";
		$data['paragraph'] .= "<b>N.B.</b> These values exclude baseline tests. </p>";

		return view('charts.pie_chart', $data);
	}

	public function current_suppression()
	{
		extract($this->get_filters());

		if ($partner || $partner === 0) {
			$sql = "CALL `proc_get_vl_current_suppression`('3','".$partner."')";
		}else if($county) {
			$sql = "CALL `proc_get_vl_current_suppression`('1','".$county."')";
		}else {
			$sql = "CALL `proc_get_vl_current_suppression`('0','0')";
		}

		$data['div'] = Str::random(15);

		$rows = DB::select($sql);

		$result = $rows[0];
		
		$data['outcomes']['data'][0]['name'] = '<= 400 copies/ml (LDL)';
		$data['outcomes']['data'][1]['name'] = '401 - 999 copies/ml (LLV)';
		$data['outcomes']['data'][2]['name'] = '>= 1000 copies/ml (HVL)';

		$data['outcomes']['data'][0]['y'] = (int) $result->undetected;
		$data['outcomes']['data'][1]['y'] = (int) $result->less1000;
		$data['outcomes']['data'][2]['y'] = (int) $result->nonsuppressed;

		$data['outcomes']['data'][0]['z'] = number_format($result->less1000);
		$data['outcomes']['data'][1]['z'] = number_format($result->undetected);
		$data['outcomes']['data'][2]['z'] = number_format($result->nonsuppressed);

		$data['outcomes']['data'][0]['color'] = '#1BA39C';
		$data['outcomes']['data'][1]['color'] = '#66ff66';
		$data['outcomes']['data'][2]['color'] = '#F2784B';		

		$data['outcomes']['data'][1]['sliced'] = true;
		$data['outcomes']['data'][1]['selected'] = true;

		$data['paragraph'] = "<p>  ";
		$data['paragraph'] .= "<= 400 copies/ml - " . number_format($result->undetected) . "<br />";
		$data['paragraph'] .= "401 - 999 copies/ml - " . number_format($result->less1000) . "<br />";
		$data['paragraph'] .= "Non Suppressed - " . number_format($result->nonsuppressed) . "<br />";
		$data['paragraph'] .= "<b>N.B.</b> These values exclude baseline tests. </p>";

		return view('charts.pie_chart', $data);
	}

	public function current_gender_chart($type)
	{
		extract($this->get_filters());

		if ($partner || $partner === 0) {
			$sql = "CALL `proc_get_vl_current_gender_suppression_listing_partner`({$type}, {$partner})";
		}else if ($county) {
			$sql = "CALL `proc_get_vl_current_gender_suppression_listing`({$type}, {$county})";
		}else{
			$param = 1000;
			$sql = "CALL `proc_get_vl_current_gender_suppression_listing_partner`({$type}, {$param})";
		}

		$data['div'] = Str::random(15);

		$rows = DB::select($sql);
		$result = $rows[0];

		$data['outcomes'][0]['name'] = 'Not Suppressed';
		$data['outcomes'][1]['name'] = 'Suppressed';

		$data['categories'][0] = 'No data';
		$data['outcomes'][0]["data"][0] = (int) $result->nogender_nonsuppressed;
		$data['outcomes'][1]["data"][0] = (int) $result->nogender_suppressed;

		$data['categories'][1] = 'Male';
		$data['outcomes'][0]["data"][1] = (int) $result->male_nonsuppressed;
		$data['outcomes'][1]["data"][1] = (int) $result->male_suppressed;

		$data['categories'][2] = 'Female';
		$data['outcomes'][0]["data"][2] = (int) $result->female_nonsuppressed;
		$data['outcomes'][1]["data"][2] = (int) $result->female_suppressed;
 
		$data['outcomes'][0]['drilldown']['color'] = '#913D88';
		$data['outcomes'][1]['drilldown']['color'] = '#96281B';

		return view('charts.line_graph', $data);
	}



	public function current_age_chart($type)
	{
		extract($this->get_filters());

		if ($partner || $partner === 0) {
			$sql = "CALL `proc_get_vl_current_age_suppression_listing_partner`({$type}, {$partner})";
		}else if ($county) {
			$sql = "CALL `proc_get_vl_current_age_suppression_listing`({$type}, {$county})";
		}else{
			$param = 1000;
			$sql = "CALL `proc_get_vl_current_age_suppression_listing_partner`({$type}, {$param})";
		}

		$data['div'] = Str::random(15);

		$rows = DB::select($sql);
		$result = $rows[0];

		$data['outcomes'][0]['name'] = 'Not Suppressed';
		$data['outcomes'][1]['name'] = 'Suppressed';

		$data['categories'][0] = 'No data';
		$data['outcomes'][0]["data"][0] = (int) $result->noage_nonsuppressed;
		$data['outcomes'][1]["data"][0] = (int) $result->noage_suppressed;

		$data['categories'][1] = 'Less 2';
		$data['outcomes'][0]["data"][1] = (int) $result->less2_nonsuppressed;
		$data['outcomes'][1]["data"][1] = (int) $result->less2_suppressed;

		$data['categories'][2] = '2-9';
		$data['outcomes'][0]["data"][2] = (int) $result->less9_nonsuppressed;
		$data['outcomes'][1]["data"][2] = (int) $result->less9_suppressed;

		$data['categories'][3] = '10-14';
		$data['outcomes'][0]["data"][3] = (int) $result->less14_nonsuppressed;
		$data['outcomes'][1]["data"][3] = (int) $result->less14_suppressed;

		$data['categories'][4] = '15-19';
		$data['outcomes'][0]["data"][4] = (int) $result->less19_nonsuppressed;
		$data['outcomes'][1]["data"][4] = (int) $result->less19_suppressed;

		$data['categories'][5] = '20-24';
		$data['outcomes'][0]["data"][5] = (int) $result->less24_nonsuppressed;
		$data['outcomes'][1]["data"][5] = (int) $result->less24_suppressed;

		$data['categories'][6] = '25+';
		$data['outcomes'][0]["data"][6] = (int) $result->over25_nonsuppressed;
		$data['outcomes'][1]["data"][6] = (int) $result->over25_suppressed;
 
		$data['outcomes'][0]['drilldown']['color'] = '#913D88';
		$data['outcomes'][1]['drilldown']['color'] = '#96281B';

		return view('charts.line_graph', $data);
	}


	public function suppression_listings($type)
	{
		extract($this->get_filters());

		if ($partner || $partner === 0) {
			$sql = "CALL `proc_get_vl_current_suppression_listing_partners`({$type}, {$partner})";
		}else if ($county) {
			$sql = "CALL `proc_get_vl_current_suppression_listing`({$type}, {$county})";
		}else{
			$param = 1000;
			$sql = "CALL `proc_get_vl_current_suppression_listing_partners`({$type}, {$param})";
		}

		$data['div'] = Str::random(15);
		$data['modal'] = Str::random(15);

		$data['table_head'] = "
			<th>#</th>
			<th>Name</th>
			<th>Current Suppression</th>
			<th>Patients Tested</th>
			<th>Patients on Art as at September 30, 2017</th>
			<th>Coverage</th>
		";

		$rows = DB::select($sql);

		$li = $table = '';

		$cols = ['', 'countyname', 'subcounty', 'partnername', 'name'];

		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);

			$patients = ($value['suppressed']+$value['nonsuppressed']);
			if($patients){
				$suppression = round(($value['suppressed']*100/$patients),1);
			}else{
				$suppression = 0;
			}
			if($value['totallstrpt']){
				$coverage = round(($patients*100/$value['totallstrpt']),1);
			}else{
				$coverage = 0;
			}


			if ($key<15) {
				// $li .= '<a href="javascript:void(0);" class="list-group-item" ><strong>'.($key+1).'.</strong>&nbsp;'.$value[$cols[$type]] .':&nbsp;'.$suppression.'%</a>';
				$li .= '<div ><strong>'.($key+1).'.</strong>&nbsp;'.$value[$cols[$type]] .':&nbsp;'.$suppression.'%</div>';
			}

			$table .= '<tr>';
			$table .= '<td>'.($key+1).'</td>';
			$table .= '<td>'.$value[$cols[$type]].'</td>';
			$table .= '<td>'.$suppression.'%</td>';
			$table .= '<td>'.$patients.'</td>';
			$table .= '<td>'.($value['totallstrpt']).'</td>';
			$table .= '<td>'.$coverage.'%</td>';
			$table .= '</tr>';
		}
		$data['listings'] = $li;
		$data['table_rows'] = $table;
		return view('tables.listings', $data);
	}

	public function suppression_age_listings($type, $suppressed)
	{
		extract($this->get_filters());

		if ($partner || $partner === 0) {
			$sql = "CALL `proc_get_vl_current_age_suppression_listing_partner`({$type}, {$partner})";
		}else if ($county) {
			$sql = "CALL `proc_get_vl_current_age_suppression_listing`({$type}, {$county})";
		}else{
			$param = 1000;
			$sql = "CALL `proc_get_vl_current_age_suppression_listing_partner`({$type}, {$param})";
		}

		$data['div'] = Str::random(15);
		$data['modal'] = Str::random(15);

		$rows = DB::select($sql);

		$li = $table = '';

		$cols = ['', 'countyname', 'subcounty', 'partnername', 'name'];


		if($suppressed == 1){
			$data['table_head'] = "
				<th>#</th>
				<th>Name</th>
				<th>No Age Suppressed</th>
				<th>&lt;2 Suppressed</th>
				<th>2-9 Suppressed</th>
				<th>10-14 Suppressed</th>
				<th>15-19 Suppressed</th>
				<th>20-24 Suppressed</th>
				<th>25+ Suppressed</th>
			";
		}
		else{
			$data['table_head'] = "
				<th>#</th>
				<th>Name</th>
				<th>No Age Non suppressed</th>
				<th>&lt;2 Non Suppressed</th>
				<th>2-9 Non Suppressed</th>
				<th>10-14 Non Suppressed</th>
				<th>15-19 Non Suppressed</th>
				<th>20-24 Non Suppressed</th>
				<th>25+ Non Suppressed</th>
			";
		}

		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);

			if ($key<15) {
				$li .= '<div ><strong>'.($key+1).'.</strong>&nbsp;'.$value[$cols[$type]].'</div>';
			}

			$table .= '<tr>';
			$table .= '<td>'.($key+1).'</td>';
			$table .= '<td>'.$value[$cols[$type]].'</td>';

			if($suppressed == 1){
				$table .= '<td>'.$value['noage_suppressed'].'</td>';
				$table .= '<td>'.$value['less2_suppressed'].'</td>';
				$table .= '<td>'.$value['less9_suppressed'].'</td>';
				$table .= '<td>'.$value['less14_suppressed'].'</td>';
				$table .= '<td>'.$value['less19_suppressed'].'</td>';
				$table .= '<td>'.$value['less24_suppressed'].'</td>';
				$table .= '<td>'.$value['over25_suppressed'].'</td>';
			}
			else{
				$table .= '<td>'.$value['noage_nonsuppressed'].'</td>';
				$table .= '<td>'.$value['less2_nonsuppressed'].'</td>';				
				$table .= '<td>'.$value['less9_nonsuppressed'].'</td>';				
				$table .= '<td>'.$value['less14_nonsuppressed'].'</td>';				
				$table .= '<td>'.$value['less19_nonsuppressed'].'</td>';				
				$table .= '<td>'.$value['less24_nonsuppressed'].'</td>';				
				$table .= '<td>'.$value['over25_nonsuppressed'].'</td>';
			}
			$table .= '</tr>';
		}
		$data['listings'] = $li;
		$data['table_rows'] = $table;
		return view('tables.listings', $data);
	}

	public function suppression_gender_listings($type)
	{
		extract($this->get_filters());

		if ($partner || $partner === 0) {
			$sql = "CALL `proc_get_vl_current_gender_suppression_listing_partner`({$type}, {$partner})";
		}else if ($county) {
			$sql = "CALL `proc_get_vl_current_gender_suppression_listing`({$type}, {$county})";
		}else{
			$param = 1000;
			$sql = "CALL `proc_get_vl_current_gender_suppression_listing_partner`({$type}, {$param})";
		}

		$data['div'] = Str::random(15);
		$data['modal'] = Str::random(15);

		$data['table_head'] = "
			<th>#</th>
			<th>Name</th>
			<th>Male Suppressed</th>
			<th>Male Non suppressed</th>
			<th>Female Suppressed</th>
			<th>Female Non suppressed</th>
			<th>No Gender Suppressed</th>
			<th>No Gender Non suppressed</th>
		";

		$rows = DB::select($sql);

		$li = $table = '';

		$cols = ['', 'countyname', 'subcounty', 'partnername', 'name'];

		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);

			if ($key<15) {
				$li .= '<div ><strong>'.($key+1).'.</strong>&nbsp;'.$value[$cols[$type]].'</div>';
			}

			$table .= '<tr>';
			$table .= '<td>'.($key+1).'</td>';
			$table .= '<td>'.$value[$cols[$type]].'</td>';
			$table .= '<td>'.$value['male_suppressed'].'</td>';
			$table .= '<td>'.$value['male_nonsuppressed'].'</td>';
			$table .= '<td>'.$value['female_suppressed'].'</td>';
			$table .= '<td>'.$value['female_nonsuppressed'].'</td>';
			$table .= '<td>'.$value['nogender_suppressed'].'</td>';
			$table .= '<td>'.$value['nogender_nonsuppressed'].'</td>';
			$table .= '</tr>';
		}
		$data['listings'] = $li;
		$data['table_rows'] = $table;
		return view('tables.listings', $data);
	}


	public function county_partner_table()
	{
		extract($this->get_filters());

		$type = 4;

		$sql = "CALL `proc_get_vl_site_summary`('".$year."','".$month."','".$to_year."','".$to_month."')";
		$sqlAge = "CALL `proc_get_vl_county_agecategories_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$county."');";
		$sqlGender = "CALL `proc_get_vl_county_gender_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$county."');";


		$result = DB::select($sql);
		$resultage = DB::select($sqlAge);
		$resultGender = DB::select($sqlGender);

		$data['div'] = Str::random(15);

		$partners =  $ageData = $genderData = [];

		foreach ($resultage as $key => $value) {
			if (!in_array($value->selection, $partners)) $partners[] = $value->selection;
		}

		foreach ($partners as $key => $value) {
			foreach ($resultGender as $k => $v) {
				if ($value == $v->selection) {
					$genderData[$value]['selection'] = $v->selection;
					if ($v->name == 'F'){
						$genderData[$value]['femaletests'] = $v->tests;
						$genderData[$value]['femalesustx'] = ($v->less5000+$v->above5000);
					}
					if ($v->name == 'M'){
						$genderData[$value]['maletests'] = $v->tests;
						$genderData[$value]['malesustx'] = ($v->less5000+$v->above5000);
					}
					if ($v->name == 'No Data'){
						$genderData[$value]['Nodatatests'] = $v->tests;
						$genderData[$value]['Nodatasustx'] = ($v->less5000+$v->above5000);
					}
				}
			}
		}


		foreach ($partners as $key => $value) {
			foreach ($resultage as $k => $v) {
				if ($value == $v->selection) {
					$ageData[$value]['selection'] = $v->selection;
					if ($v->name == '15-19') {
						$ageData[$value]['less19tests'] = $v->tests;
						$ageData[$value]['less19sustx'] = ($v->less5000+$v->above5000);	
					}
					if ($v->name == '10-14') {
						$ageData[$value]['less14tests'] = $v->tests;
						$ageData[$value]['less14sustx'] = ($v->less5000+$v->above5000);	
					}
					if ($v->name == 'Less 2') {
						$ageData[$value]['less2tests'] = $v->tests;
						$ageData[$value]['less2sustx'] = ($v->less5000+$v->above5000);	
					}
					if ($v->name == '2-9') {
						$ageData[$value]['less9tests'] = $v->tests;
						$ageData[$value]['less9sustx'] = ($v->less5000+$v->above5000);	
					}
					if ($v->name == '20-24') {
						$ageData[$value]['less25tests'] = $v->tests;
						$ageData[$value]['less25sustx'] = ($v->less5000+$v->above5000);	
					}
					if ($v->name == '25+') {
						$ageData[$value]['above25tests'] = $v->tests;
						$ageData[$value]['above25sustx'] = ($v->less5000+$v->above5000);	
					}
				}
			}
		}

		foreach ($result as $key => $value) {
			$value = get_object_vars($value);
			if (in_array($value['partner'], $partners)){
				$routine = ((int) $value['undetected'] + (int) $value['less1000'] + (int) $value['less5000'] + (int) $value['above5000']);
				$routinesus = ((int) $value['less5000'] + (int) $value['above5000']);
				$validTests = ((int) $routine + (int) $value['baseline'] + (int) $value['confirmtx']);
				$femaletests = ($genderData[$value['partner']]['femaletests']) ? number_format((int) $genderData[$value['partner']]['femaletests']) : 0;
				$femalesustx = ($genderData[$value['partner']]['femalesustx']) ? number_format((int) $genderData[$value['partner']]['femalesustx']) : 0;
				$maletests = ($genderData[$value['partner']]['maletests']) ? number_format((int) $genderData[$value['partner']]['maletests']) : 0;
				$malesustx = ($genderData[$value['partner']]['malesustx']) ? number_format((int) $genderData[$value['partner']]['malesustx']) : 0;
				$Nodatatests = ($genderData[$value['partner']]['Nodatatests']) ? number_format((int) $genderData[$value['partner']]['Nodatatests']) : 0;
				$Nodatasustx = ($genderData[$value['partner']]['Nodatasustx']) ? number_format((int) $genderData[$value['partner']]['Nodatasustx']) : 0;
				$less2tests = ($ageData[$value['partner']]['less2tests']) ? number_format($ageData[$value['partner']]['less2tests']) : 0;
				$less2sustx = ($ageData[$value['partner']]['less2sustx']) ? number_format($ageData[$value['partner']]['less2sustx']) : 0;
				$less9tests = ($ageData[$value['partner']]['less9tests']) ? number_format($ageData[$value['partner']]['less9tests']) : 0;
				$less9sustx = ($ageData[$value['partner']]['less9sustx']) ? number_format($ageData[$value['partner']]['less9sustx']) : 0;
				$less14tests = ($ageData[$value['partner']]['less14tests']) ? number_format($ageData[$value['partner']]['less14tests']) : 0;
				$less14sustx = ($ageData[$value['partner']]['less14sustx']) ? number_format($ageData[$value['partner']]['less14sustx']) : 0;
				$less19tests = ($ageData[$value['partner']]['less19tests']) ? number_format($ageData[$value['partner']]['less19tests']) : 0;
				$less19sustx = ($ageData[$value['partner']]['less19sustx']) ? number_format($ageData[$value['partner']]['less19sustx']) : 0;
				$less25tests = ($ageData[$value['partner']]['less25tests']) ? number_format($ageData[$value['partner']]['less25tests']) : 0;
				$less25sustx = ($ageData[$value['partner']]['less25sustx']) ? number_format($ageData[$value['partner']]['less25sustx']) : 0;
				$above25tests = ($ageData[$value['partner']]['above25tests']) ? number_format($ageData[$value['partner']]['above25tests']) : 0;
				$above25sustx = ($ageData[$value['partner']]['above25sustx']) ? number_format($ageData[$value['partner']]['above25sustx']) : 0;
				$table .= "<tr>
							<td>".($key+1)."</td>
							<td>".$value['partner']."</td>
							<td>".number_format((int) $value['received'])."</td>
							<td>".number_format((int) $value['rejected']) . " (" . 
								round((($value['rejected']*100)/$value['received']), 1, PHP_ROUND_HALF_UP)."%)</td>
							<td>".number_format((int) $validTests + (int) $value['invalids'])."</td>
							<td>".number_format((int) $value['invalids'])."</td>
							<td>".number_format($routine)."</td>
							<td>".number_format($routinesus)."</td>
							<td>".number_format((int) $value['baseline'])."</td>
							<td>".number_format((int) $value['baselinesustxfail'])."</td>
							<td>".number_format((int) $value['confirmtx'])."</td>
							<td>".number_format((int) $value['confirm2vl'])."</td>
							<td>".number_format($validTests)."</td>
							<td>".number_format((int) $routinesus + (int) $value['baselinesustxfail'] + (int) $value['confirm2vl'])."</td>
							<td>".$femaletests."</td>
							<td>".$femalesustx."</td>
							<td>".$maletests."</td>
							<td>".$malesustx."</td>
							<td>".$Nodatatests."</td>
							<td>".$Nodatasustx."</td>
							<td>".$less2tests."</td>
							<td>".$less2sustx."</td>
							<td>".$less9tests."</td>
							<td>".$less9sustx."</td>
							<td>".$less14tests."</td>
							<td>".$less14sustx."</td>
							<td>".$less19tests."</td>
							<td>".$less19sustx."</td>
							<td>".$less25tests."</td>
							<td>".$less25sustx."</td>
							<td>".$above25tests."</td>
							<td>".$above25sustx."</td>";
				
				$table .= "</tr>";
			}
		}

	}
}
