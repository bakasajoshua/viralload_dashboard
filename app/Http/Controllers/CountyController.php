<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lookup;
use DB;
use Str;

class CountyController extends Controller
{

	public $table_head = "
			<tr class='colhead'>
				<th>No</th>
				<th>Name</th>
				<th>Facilities Sending Samples</th>
				<th>Received Samples at Lab</th>
				<th>Rejected Samples (on receipt at lab)</th>
				<th>All Test (plus reruns) Done at Lab</th>
				<th>Redraw (after testing)</th>
				<th>Routine VL-Tests</th>
				<th>Routine VL Tests &gt; 1000</th>
				<th>Baseline VL-Tests</th>
				<th>Baseline VL Tests &gt; 1000</th>
				<th>Confirmatory Repeat-Tests</th>
				<th>Confirmatory Repeat &gt; 1000</th>
				<th>Total Tests with Valid Outcomes-Tests</th>
				<th>Total Tests with Valid Outcomes &gt; 1000</th>
				<th>Female-Tests</th>
				<th>Female &gt; 1000</th>
				<th>Male-Tests</th>
				<th>Male &gt; 1000</th>
				<th>No Data-Tests</th>
				<th>No Data &gt; 1000</th>
				<th>Less 2 Yrs-Tests</th>
				<th>Less 2 Yrs &gt; 1000</th>
				<th>2 - 9 Yrs-Tests</th>
				<th>2 - 9 Yrs &gt; 1000</th>
				<th>10 - 14 yrs-Tests</th>
				<th>10 - 14 yrs &gt; 1000</th>
				<th>15 - 19 yrs-Tests</th>
				<th>15 - 19 yrs &gt; 1000</th>
				<th>20- 24 yrs-Tests</th>
				<th>20- 24 yrs &gt; 1000</th>
				<th>Above 25 yrs-Tests</th>
				<th>Above 25 yrs &gt; 1000</th>
			</tr>
		"; 


	public function subcounty_outcomes($division=3, $ageGroup=0)
	{
		extract($this->get_filters());

		if($division == 3){
			if($county){
				$sql = "CALL `proc_get_vl_county_subcounty_outcomes`('".$county."','".$year."','".$month."','".$to_year."','".$to_month."')";
			}
			else{
				$sql = "CALL `proc_get_vl_subcounty_outcomes`('".$year."','".$month."','".$to_year."','".$to_month."')";
			}			
		}
		// Regimen
		else if($division == 11){
			if($regimen){
				$sql = "CALL `proc_get_vl_county_regimen_outcomes`('".$regimen."','".$year."','".$month."','".$to_year."','".$to_month."')";
			}
			else{	
				// $sql = "CALL `proc_get_vl_regimen_outcomes`('".$year."','".$month."','".$to_year."','".$to_month."','".$ageGroup."')";
				$sql = "CALL `proc_get_vl_regimen_outcomes`('".$year."','".$month."','".$to_year."','".$to_month."','".$ageGroup."')";
			}
		}
		// Age
		else if($division == 12){
			if($age_cat && $ageGroup == 1){
				$sql = "CALL `proc_get_vl_county_age_outcomes`('".$age_cat."','".$year."','".$month."','".$to_year."','".$to_month."')";
			}
			else if($age_cat && $ageGroup == 11){
				$age = $age_cat[0];
				$sql = "CALL `proc_get_vl_age_regimen`('".$age."','".$year."','".$month."','".$to_year."','".$to_month."')";
			}
			else if($partner){
				$sql = "CALL `proc_get_vl_partner_age_outcomes`('".$partner."','".$year."','".$month."','".$to_year."','".$to_month."')";
			}
			else{	
				$sql = "CALL `proc_get_vl_age_outcomes`('".$year."','".$month."','".$to_year."','".$to_month."')";
			}
		}

		
		$rows = DB::select($sql);

		$data = $this->bars(['Not Suppressed', 'LLV', 'LDL', 'Suppression', config('var.suppression_target') . '% Target'], 'column', ['#F2784B', '#66ff66', '#1BA39C'], ['', '', '', ' %', ' %']);
		$this->columns($data, 3, 4, 'spline');
		$this->yAxis($data, 0, 2);

		$data['categories'][0] = 'Not Defined';			
		$data['outcomes'][0]['data'][0] = 0;
		$data['outcomes'][1]['data'][0] = 0;
		$data['outcomes'][2]['data'][0] = 0;		
		$data['outcomes'][3]['data'][0] = config('var.suppression_target');
		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);

			$data['categories'][$key] 					= $value['name'] ?? $value['regimenname'] ?? json_encode($value);			
			$data['outcomes'][0]['data'][$key] = (int) $value['nonsuppressed'];
			$data['outcomes'][1]['data'][$key] = (int) ($value['less1000'] ?? 0);
			$data['outcomes'][2]['data'][$key] = (int) ($value['undetected'] ?? 0);
			if($value['suppressed'] || $value['nonsuppressed']){
				$data['outcomes'][3]['data'][$key] = round(@(((int) $value['suppressed']*100)/((int) $value['suppressed']+(int) $value['nonsuppressed'])),1);
			}else{
				$data['outcomes'][3]['data'][$key] = 0;
			}
			$data['outcomes'][4]['data'][$key] = config('var.suppression_target');

			if($key == 49) break;
		}
		return view('charts.dual_axis', $data);
	}

	public function division_table($division=1, $second_division=0)
	{
		extract($this->get_filters());
		$column = $column2 = '';

		// Counties
		if($division == 1){
			// County Subcounties
			if($second_division == 2){
				$type=2;
				$county = $county ?? 0;
				$sql = "CALL `proc_get_vl_subcounty_details`('".$county."','".$year."','".$month."','".$to_year."','".$to_month."')";
				$sqlAge = "CALL `proc_get_vl_county_agecategories_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$county."');";
				$sqlGender = "CALL `proc_get_vl_county_gender_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$county."');";

				$column = 'subcounty_id';
				$column2 = 'subcounty';				
			}
			// County Partners
			else if($second_division == 3){
				$type=1;
				$sql = "CALL `proc_get_vl_county_partners`('".$county."','".$year."','".$month."','".$to_year."','".$to_month."')";
				$sqlAge = "CALL `proc_get_vl_county_agecategories_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$county."');";
				$sqlGender = "CALL `proc_get_vl_county_gender_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$county."');";

				$column = 'partner';	
			}
			// County Facilities
			else if($second_division == 4){
				$type=1;
				$type2=3;
				// $sql = "CALL `proc_get_vl_sites_details`('".$county."','".$year."','".$month."','".$to_year."','".$to_month."')";
				$sql = "CALL `proc_get_vl_sites_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$county."')";
				$sqlAge = "CALL `proc_get_vl_county_agecategories_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type2."','".$county."');";
				$sqlGender = "CALL `proc_get_vl_county_gender_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type2."','".$county."');";

				$column = 'facility_id';	
				$column2 = 'facility';	
			}

			// All Counties
			else{
				$type=0;
				$default = 0;
				$sql = "CALL `proc_get_vl_county_details`('".$year."','".$month."','".$to_year."','".$to_month."')";
				$sqlAge = "CALL `proc_get_vl_county_agecategories_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$default."');";
				$sqlGender = "CALL `proc_get_vl_county_gender_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$default."');";

				$column = 'county';
			}
		}
		// Subcounties
		else if($division == 2){
			if($second_division == 4){
				$type = 1;
				$sql = "CALL `proc_get_vl_subcounty_sites_details`('".$subcounty."','".$year."','".$month."','".$to_year."','".$to_month."')";
				$sqlAge = "CALL `proc_get_vl_subcounty_agecategories_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$subcounty."');";
				$sqlGender = "CALL `proc_get_vl_subcounty_gender_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$subcounty."');";
				$column = 'facility';
			}
			else{
				$type=2;
				$county = $county ?? 0;
				$sql = "CALL `proc_get_vl_subcounty_details`('".$county."','".$year."','".$month."','".$to_year."','".$to_month."')";
				$sqlAge = "CALL `proc_get_vl_county_agecategories_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$county."');";
				$sqlGender = "CALL `proc_get_vl_county_gender_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$county."');";

				$column = 'subcounty_id';
				$column2 = 'subcounty';
			}
		}
		// Partners
		else if($division == 3){
			if($second_division == 1){
				$type = 1;
				$sql = "CALL `proc_get_vl_partner_county_details`('".$partner."','".$year."','".$month."','".$to_year."','".$to_month."')";
				$sqlAge = "CALL `proc_get_vl_partner_agecategories_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$partner."');";
				$sqlGender = "CALL `proc_get_vl_partner_gender_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$partner."');";
				$column = 'county';
			}
			else if($second_division == 4){
				$type = 2;

				$sql = "CALL `proc_get_partner_sites_details`('".$partner."','".$year."','".$month."','".$to_year."','".$to_month."')";

				$sqlAge = "CALL `proc_get_vl_partner_agecategories_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$partner."');";
				$sqlGender = "CALL `proc_get_vl_partner_gender_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$partner."');";
				$column = 'facility_id';
				$column2 = 'facility';
			}
			else{
				$type=1;
				$sql = "CALL `proc_get_vl_county_partners`('".$county."','".$year."','".$month."','".$to_year."','".$to_month."')";
				$sqlAge = "CALL `proc_get_vl_county_agecategories_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$county."');";
				$sqlGender = "CALL `proc_get_vl_county_gender_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$county."');";

				$column = 'partner';
			}
		}
		else if($division == 4){
			$type = 1;
			$type2 = 3;
			$sql = "CALL `proc_get_vl_sites_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$county."')";
			$sqlAge = "CALL `proc_get_vl_county_agecategories_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type2."','".$county."');";
			$sqlGender = "CALL `proc_get_vl_county_gender_details`('".$year."','".$month."','".$to_year."','".$to_month."','".$type2."','".$county."');";

			$column = 'facility';
		}
		$result = DB::select($sql);
		$resultAge = DB::select($sqlAge);
		$resultGender = DB::select($sqlGender);

		// if($division == 2 && $second_division == 0) dd($resultGender);

		$counties = $ageData = $genderData = $rows = [];

		$genders = ['F' => 'female', 'M' => 'male', 'No Data' => 'NoData'];
		$ages = ['Less 2' => 'less2', '2-9' => 'less9', '10-14' => 'less14', '15-19' => 'less19', '20-24' => 'less25', '25+' => 'above25'];

		foreach ($result as $key => $value) {
			$value = get_object_vars($value);

			$identifier = $value[$column] ?? $value['name'];
			foreach ($resultAge as $k => $v) {
				if($v->selection != $identifier || $v->name == 'No Data') continue;

				$value[$ages[$v->name] . 'tests'] = $v->tests;
				$value[$ages[$v->name] . 'sustx'] = ($v->less5000+$v->above5000);
			}
			foreach ($resultGender as $k => $v) {
				if($v->selection != $identifier) continue;

				$value[$genders[$v->name] . 'tests'] = $v->tests;
				$value[$genders[$v->name] . 'sustx'] = ($v->less5000+$v->above5000);
			}

			$rows[$key] = $value;

			/*foreach ($genderData as $k => $v) {
				$rows[$key] = array_merge($rows[$key], $v);
			}
			foreach ($ageData as $k => $v) {
				$rows[$key] = array_merge($rows[$key], $v);
			}*/
		}

		$data['div'] = Str::random(15);

		$data['table_head'] = "
			<tr class='colhead'>
				<th>No</th>
				<th>Name</th>";

		if($division == 4 || $second_division == 4){
			$data['table_head'] .= "<th>MFL Code</th>";
			$data['table_head'] .= "<th>Subcounty</th>";
		}
		else if($division == 3){

		}
		else{
			$data['table_head'] .= "<th>Facilities Sending Samples</th>";
		}
		$data['table_head'] .= "
				<th>Received Samples at Lab</th>
				<th>Rejected Samples (on receipt at lab)</th>
				<th>All Test (plus reruns) Done at Lab</th>
				<th>Redraw (after testing)</th>
				<th>Routine VL-Tests</th>
				<th>Routine VL Tests &gt; 1000</th>
				<th>Baseline VL-Tests</th>
				<th>Baseline VL Tests &gt; 1000</th>
				<th>Confirmatory Repeat-Tests</th>
				<th>Confirmatory Repeat &gt; 1000</th>
				<th>Total Tests with Valid Outcomes-Tests</th>
				<th>Total Tests with Valid Outcomes &gt; 1000</th>
				<th>Female-Tests</th>
				<th>Female &gt; 1000</th>
				<th>Male-Tests</th>
				<th>Male &gt; 1000</th>
				<th>No Data-Tests</th>
				<th>No Data &gt; 1000</th>
				<th>Less 2 Yrs-Tests</th>
				<th>Less 2 Yrs &gt; 1000</th>
				<th>2 - 9 Yrs-Tests</th>
				<th>2 - 9 Yrs &gt; 1000</th>
				<th>10 - 14 yrs-Tests</th>
				<th>10 - 14 yrs &gt; 1000</th>
				<th>15 - 19 yrs-Tests</th>
				<th>15 - 19 yrs &gt; 1000</th>
				<th>20- 24 yrs-Tests</th>
				<th>20- 24 yrs &gt; 1000</th>
				<th>Above 25 yrs-Tests</th>
				<th>Above 25 yrs &gt; 1000</th>
			</tr>
		"; 

		$data['rows'] = '';

		foreach ($rows as $key => $value) {
			// $value = get_object_vars($value);
			
			$routine = ((int) $value['undetected'] + (int) $value['less1000'] + (int) $value['less5000'] + (int) $value['above5000']);
			$routinesus = ((int) $value['less5000'] + (int) $value['above5000']);
			$validTests = ((int) $routine + (int) $value['baseline'] + (int) $value['confirmtx']);

			$data['rows'] .= "<tr>
						<td>".($key+1)."</td>
						<td>".( $value[$column2] ?? $value[$column] ?? $value['name'] )."</td>";

			if($division == 4 || $second_division == 4){
				$data['rows'] .= "<td>".($value['facilitycode'] ?? $value['MFLCode'] ?? null)."</td>";
				$data['rows'] .= "<td>".($value['subcounty'] ?? null)."</td>";
			}else if($division == 3){
				
			}else{
				$data['rows'] .= "<td>".number_format((int) $value['sitesending'])."</td>";
			}
			if($value['received']){
				$rejection = round((($value['rejected']*100)/$value['received']), 1, PHP_ROUND_HALF_UP);
			}else{
				$rejection = 0;
			}
			$data['rows'] .= "			
						<td>".number_format((int) $value['received'])."</td>
						<td>".number_format((int) $value['rejected']) . " ({$rejection}%)</td>
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
						<td>".number_format((int) @$value['femaletests'])."</td>
						<td>".number_format((int) @$value['femalesustx'])."</td>
						<td>".number_format((int) @$value['maletests'])."</td>
						<td>".number_format((int) @$value['malesustx'])."</td>
						<td>".number_format((int) @$value['Nodatatests'])."</td>
						<td>".number_format((int) @$value['Nodatasustx'])."</td>
						<td>".number_format((int) @$value['less2tests'])."</td>
						<td>".number_format((int) @$value['less2sustx'])."</td>
						<td>".number_format((int) @$value['less9tests'])."</td>
						<td>".number_format((int) @$value['less9sustx'])."</td>
						<td>".number_format((int) @$value['less14tests'])."</td>
						<td>".number_format((int) @$value['less14sustx'])."</td>
						<td>".number_format((int) @$value['less19tests'])."</td>
						<td>".number_format((int) @$value['less19sustx'])."</td>
						<td>".number_format((int) @$value['less25tests'])."</td>
						<td>".number_format((int) @$value['less25sustx'])."</td>
						<td>".number_format((int) @$value['above25tests'])."</td>
						<td>".number_format((int) @$value['above25sustx'])."</td>";
			
			$data['rows'] .= "</tr>";
		}
		return view('tables.data-table', $data);
	}



	public function county_outcome_table($subcounty=NULL)
	{
		extract($this->get_filters());

		if($subcounty){
			$sql = "CALL `proc_get_vl_subcounty_outcomes_age_gender`('".$year."','".$month."','".$to_year."','".$to_month."')";
		}
		else{
			$sql = "CALL `proc_get_vl_county_outcomes_age_gender`('".$year."','".$month."','".$to_year."','".$to_month."')";
		}

		$data['div'] = Str::random(15);

		$data['table_head'] = "
			<tr class='colhead'>
				<th>No</th>";

		if($subcounty){
			$data['table_head'] .= "<th>Sub-County</th>";
		}
		$data['table_head'] .= "
			<th >County</th>
			<th>Male Less than 2yrs-Tests</th>
			<th>Male Less than 2yrs &gt; 1000</th>
			<th>Male 2-9yrs-Tests</th>
			<th>Male 2-9yrs &gt; 1000</th>
			<th>Male 10-14yrs-Tests</th>
			<th>Male 10-14yrs &gt; 1000</th>
			<th>Male 15-19yrs-Tests</th>
			<th>Male 15-19yrs &gt; 1000</th>
			<th>Male 20-24yrs-Tests</th>
			<th>Male 20-24yrs &gt; 1000</th>
			<th>Male Above 25yrs-Tests</th>
			<th>Male Above 25yrs &gt; 1000</th>
			<th>Female Less than 2yrs-Tests</th>
			<th>Female Less than 2yrs &gt; 1000</th>
			<th>Female 2-9yrs-Tests</th>
			<th>Female 2-9yrs &gt; 1000</th>
			<th>Female 10-14yrs-Tests</th>
			<th>Female 10-14yrs &gt; 1000</th>
			<th>Female 15-19yrs-Tests</th>
			<th>Female 15-19yrs &gt; 1000</th>
			<th>Female 20-24yrs-Tests</th>
			<th>Female 20-24yrs &gt; 1000</th>
			<th>Female Above 25yrs-Tests</th>
			<th>Female Above 25yrs &gt; 1000</th>
		";

		
		$data['rows'] = '';

		$records = DB::select($sql);
		$region = $county = [];
		
		foreach ($records as $key => $value) {
				if (!in_array($value->region, $region))
				{
					$region[] = $value->region;
					if($subcounty){
						$county[$value->region] = $value->county;
					}
				}

				if ($value->gender == 1) {
					if ($value->age == 6) {
						$mTestUnder2[$value->region] = $value->tests;
						$mNonSupUnder2[$value->region] = $value->nonsup;
					}
					if ($value->age == 7) { 
						$mTest2To9[$value->region] = $value->tests;
						$mNonSup2To9[$value->region] = $value->nonsup;
					}
					if ($value->age == 8) { 
						$mTest10To14[$value->region] = $value->tests;
						$mNonSup10To14[$value->region] = $value->nonsup;
					}
					if ($value->age == 9) { 
						$mTest15To19[$value->region] = $value->tests;
						$mNonSup15To19[$value->region] = $value->nonsup;
					}
					if ($value->age == 10) { 
						$mTest20To24[$value->region] = $value->tests;
						$mNonSup20To24[$value->region] = $value->nonsup;
					}
					if ($value->age == 11) { 
						$mTestAbove25[$value->region] = $value->tests;
						$mNonSupAbove25[$value->region] = $value->nonsup;
					}
				}
			 if ($value->gender == 2) {
				if ($value->age == 6) {
					$fTestUnder2[$value->region] = $value->tests;
					$fNonSupUnder2[$value->region] = $value->nonsup;
				}
				if ($value->age == 7) {
					$fTest2To9[$value->region] = $value->tests;
					$fNonSup2To9[$value->region] = $value->nonsup;
				}
				if ($value->age == 8) {
					$fTest10To14[$value->region] = $value->tests;
					$fNonSup10To14[$value->region] = $value->nonsup;
				}
				if ($value->age == 9) {
					$fTest15To19[$value->region] = $value->tests;
					$fNonSup15To19[$value->region] = $value->nonsup;
				}
				if ($value->age == 10) {
					$fTest20To24[$value->region] = $value->tests;
					$fNonSup20To24[$value->region] = $value->nonsup;
				}
				if ($value->age == 11) {
					$fTestAbove25[$value->region] = $value->tests;
					$fNonSupAbove25[$value->region] = $value->nonsup;
				}
			}
		}
		// echo "<pre>";print_r($county);die();
		foreach ($region as $key => $value) {
			$data['rows'] .= "<tr>
						<td>".($key+1)."</td>
						<td>".$value."</td>";
						// ((isset($data['subcountyListing'])) ? "<td>".$county[$value]."</td>" : "")
			if($subcounty) $data['rows'] .= "<td>".$county[$value]."</td>";

			$data['rows'] .=	"<td>".number_format((int) $mTestUnder2[$value])."</td>
						<td>".number_format((int) $mNonSupUnder2[$value])."</td>
						<td>".number_format((int) $mTest2To9[$value]) ."</td>
						<td>".number_format((int) $mNonSup2To9[$value])."</td>
						<td>".number_format((int) $mTest10To14[$value])."</td>
						<td>".number_format((int) $mNonSup10To14[$value])."</td>
						<td>".number_format((int) $mTest15To19[$value])."</td>
						<td>".number_format((int) $mNonSup15To19[$value])."</td>
						<td>".number_format((int) $mTest20To24[$value])."</td>
						<td>".number_format((int) $mNonSup20To24[$value])."</td>
						<td>".number_format((int) $mTestAbove25[$value])."</td>
						<td>".number_format((int) $mNonSupAbove25[$value])."</td>

						<td>".number_format((int) $fTestUnder2[$value])."</td>
						<td>".number_format((int) $fNonSupUnder2[$value])."</td>
						<td>".number_format((int) $fTest2To9[$value]) ."</td>
						<td>".number_format((int) $fNonSup2To9[$value])."</td>
						<td>".number_format((int) $fTest10To14[$value])."</td>
						<td>".number_format((int) $fNonSup10To14[$value])."</td>
						<td>".number_format((int) $fTest15To19[$value])."</td>
						<td>".number_format((int) $fNonSup15To19[$value])."</td>
						<td>".number_format((int) $fTest20To24[$value])."</td>
						<td>".number_format((int) $fNonSup20To24[$value])."</td>
						<td>".number_format((int) $fTestAbove25[$value])."</td>
						<td>".number_format((int) $fNonSupAbove25[$value])."</td>";
			$data['rows'] .= "</tr>";
			
		}
		return view('tables.data-table', $data);				
	}


}
