<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lookup;
use DB;
use Str;

class SuppressionController extends Controller
{

	public function breakdowns($division, $second_division)
	{
		extract($this->get_filters());
		// Regimens
		if($division == 11){
			$default = 0;
			$actual = 1;

			if ($second_division == 1) {
				$sql = "CALL `proc_get_vl_regimens_breakdowns_outcomes`('".$regimen."','".$year."','".$month."','".$to_year."','".$to_month."','".$actual."','".$default."','".$default."','".$default."')";
			} elseif ($second_division == 3) {
				$sql = "CALL `proc_get_vl_regimens_breakdowns_outcomes`('".$regimen."','".$year."','".$month."','".$to_year."','".$to_month."','".$default."','".$actual."','".$default."','".$default."')";
			} elseif ($second_division == 2) {
				$sql = "CALL `proc_get_vl_regimens_breakdowns_outcomes`('".$regimen."','".$year."','".$month."','".$to_year."','".$to_month."','".$default."','".$default."','".$actual."','".$default."')";
			} elseif ($second_division == 4) {
				$sql = "CALL `proc_get_vl_regimens_breakdowns_outcomes`('".$regimen."','".$year."','".$month."','".$to_year."','".$to_month."','".$default."','".$default."','".$default."','".$actual."')";
			}
		}
		// Ages
		if($division == 12){
			$default = 0;
			$actual = 1;

			if ($second_division == 1) {
				$sql = "CALL `proc_get_vl_age_breakdowns_outcomes`('".$age_cat."','".$year."','".$month."','".$to_year."','".$to_month."','".$actual."','".$default."','".$default."','".$default."')";
			} elseif ($second_division == 3) {
				$sql = "CALL `proc_get_vl_age_breakdowns_outcomes`('".$age_cat."','".$year."','".$month."','".$to_year."','".$to_month."','".$default."','".$actual."','".$default."','".$default."')";
			} elseif ($second_division == 2) {
				$sql = "CALL `proc_get_vl_age_breakdowns_outcomes`('".$age_cat."','".$year."','".$month."','".$to_year."','".$to_month."','".$default."','".$default."','".$actual."','".$default."')";
			} elseif ($second_division == 4) {
				$sql = "CALL `proc_get_vl_age_breakdowns_outcomes`('".$age_cat."','".$year."','".$month."','".$to_year."','".$to_month."','".$default."','".$default."','".$default."','".$actual."')";
			}

		}

		$rows = DB::select($sql);

		$data['div'] = Str::random(15);
		$data['modal'] = Str::random(15);
		if($division == 12) $data['is_counties'] = true;

		$li = $table = '';

		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);

			if ($key<16) {
				// $li .= '<a href="javascript:void(0);" class="list-group-item" ><strong>'.($key+1).'.</strong>&nbsp;'.$value['name'].':&nbsp;&nbsp;&nbsp;'.round($value['percentage'],1).'%&nbsp;&nbsp;&nbsp;('.number_format($value['total']).')</a>';
				$li .= '<div ><strong>'.($key+1).'.</strong>&nbsp;'.$value['name'].':&nbsp;&nbsp;&nbsp;'.round($value['percentage'],1).'%&nbsp;&nbsp;&nbsp;('.number_format($value['total']).')</div>';
			}
			$table .= '<tr>';
			$table .= '<td>'.($key+1).'</td>';
			$table .= '<td>'.$value['name'].'</td>';
			$table .= '<td>'.number_format((int) $value['total']).'</td>';
			$table .= '<td>'.number_format((int) $value['suppressed']).'</td>';
			$table .= '<td>'.number_format((int) $value['nonsuppressed']).'</td>';
			$table .= '<td>'.round($value['percentage'],1).'%</td>';
			if ($division == 12){
				$table .= '<td>'.number_format((int) $value['maletest']).'</td>';
				$table .= '<td>'.number_format((int) $value['malenonsuppressed']).'</td>';
				$table .= '<td>'.number_format((int) $value['femaletest']).'</td>';
				$table .= '<td>'.number_format((int) $value['femalenonsuppressed']).'</td>';
			}
			$table .= '</tr>';
		}

		$data['listings'] = $li;
		$data['table_rows'] = $table;
		return view('tables.listings', $data);
	}

	public function regimen_age()
	{
		extract($this->get_filters());

		if (!$partner) {
			$sql = "CALL `proc_get_vl_regimen_age`('".$regimen."','".$year."','".$month."','".$to_year."','".$to_month."')";
		} else {
			$sql = "CALL `proc_get_vl_partner_regimen_age`('".$partner."','".$regimen."','".$year."','".$month."','".$to_year."','".$to_month."')";
		}

		$result = DB::select($sql);
		$data = $this->bars(['Not Suppressed', 'Suppressed', 'Suppression'], 'column', ['#F2784B', '#66ff66', '#1BA39C'], ['', '', ' %']);
		$this->columns($data, 2, 2, 'spline');
		$this->yAxis($data, 0, 1);

		$ages = ['noage', 'less2', 'less9', 'less14', 'less19', 'less24', 'over25'];

		foreach ($result as $key => $value) {
			$value = get_object_vars($value);
			$data['categories'][0] 			= 'No Age';
			$data['categories'][1] 			= 'Less 2';
			$data['categories'][2] 			= 'Less 9';
			$data['categories'][3] 			= 'Less 14';
			$data['categories'][4] 			= 'Less 19';
			$data['categories'][5] 			= 'Less 24';
			$data['categories'][6] 			= 'over 25';

			foreach ($ages as $age_key => $age) {
				$data["outcomes"][0]["data"][$age_key]	=  (int) $value[$age . '_nonsuppressed'];
				$data["outcomes"][1]["data"][$age_key]	=  (int) $value[$age]  - (int) $value[$age . '_nonsuppressed'];

				if($value[$age]){
					$data["outcomes"][2]["data"][$age_key]	=  round(@(((int) $value[$age]  - (int) $value[$age . '_nonsuppressed'])/(int) $value[$age])*100, 1);
				}else{
					$data["outcomes"][2]["data"][$age_key]	= 0;
				}
			}
		}
		return view('charts.dual_axis', $data);
	}


	public function regimen_gender()
	{
		extract($this->get_filters());

		if (!$partner) {
			$sql = "CALL `proc_get_vl_regimen_gender`('".$regimen."','".$year."','".$month."','".$to_year."','".$to_month."')";
		} else {
			$sql = "CALL `proc_get_vl_partner_regimen_gender`('".$partner."','".$regimen."','".$year."','".$month."','".$to_year."','".$to_month."')";
		}

		$result = DB::select($sql);
		$data = $this->bars(['Not Suppressed', 'Suppressed', 'Suppression'], 'column', ['#F2784B', '#66ff66', '#1BA39C'], ['', '', ' %']);
		$this->columns($data, 2, 2, 'spline');
		$this->yAxis($data, 0, 1);

		$genders = ['male', 'female', ];

		foreach ($result as $key => $value) {
			$value = get_object_vars($value);
			$data['categories'][0] 			= 'Male';
			$data['categories'][1] 			= 'Female';
			$data['categories'][2] 			= 'No Data';


			foreach ($genders as $gender_key => $gender) {
				$data["outcomes"][0]["data"][$gender_key]	=  (int) $value[$gender . 'nonsuppressed'];
				$data["outcomes"][1]["data"][$gender_key]	=  (int) $value[$gender . 'test'] - (int) $value[$gender . 'nonsuppressed'];

				if($value[$gender . 'test']){
					$data["outcomes"][2]["data"][$gender_key]	=  round(@(((int) $value[$gender . 'test'] - (int) $value[$gender . 'nonsuppressed'])/(int) $value[$gender . 'test'])*100, 1);
				}
			}

			$data["outcomes"][0]["data"][2]	= (int) $value['nogendernonsuppressed'];
			$data["outcomes"][1]["data"][2]	= (int) $value['nodata'] - (int) $value['nogendernonsuppressed'];

			if($value['nodata']){
				$data["outcomes"][2]["data"][2]	= round(@(((int) $value['nodata'] - (int) $value['nogendernonsuppressed'])/(int) $value['nodata'])*100, 1);
			}else{
				$data["outcomes"][2]["data"][2]	= 0;
			}
		}
		return view('charts.dual_axis', $data);
	}



	public function age_gender()
	{
		extract($this->get_filters());

		if (!$partner) {
			$sql = "CALL `proc_get_vl_age_gender`('".$age_cat."','".$year."','".$month."','".$to_year."','".$to_month."')";
		} else {
			$sql = "CALL `proc_get_vl_partner_age_gender`('".$partner."','".$age_cat."','".$year."','".$month."','".$to_year."','".$to_month."')";
		}

		$result = DB::select($sql);
		$data = $this->bars(['Not Suppressed', 'Suppressed', 'Suppression'], 'column', ['#F2784B', '#66ff66', '#1BA39C'], ['', '', ' %']);
		$this->columns($data, 2, 2, 'spline');
		$this->yAxis($data, 0, 1);

		$genders = ['male', 'female', ];
		
		if ($partner==null) {
			$data['categories'][0] 			= 'Male';
			$data['categories'][1] 			= 'Female';
			$data['categories'][2] 			= 'No Data';
			foreach ($result as $key => $value) {
				$nodata = (int) $value['nodatanonsuppressed'] + (int) $value['nodatasuppressed'];
				$male = (int) $value['malenonsuppressed'] + (int) $value['malesuppressed'];
				$female = (int) $value['femalenonsuppressed'] + (int) $value['femalesuppressed'];

				$data["outcomes"][0]["data"][0]	=  (int) $value['malenonsuppressed'];
				$data["outcomes"][1]["data"][0]	=  (int) $value['malesuppressed'];
				$data["outcomes"][2]["data"][0]	=  round(((int) $value['malesuppressed']/$male)*100,1);
				$data["outcomes"][0]["data"][1]	=  (int) $value['femalenonsuppressed'];
				$data["outcomes"][1]["data"][1]	=  (int) $value['femalesuppressed'];
				$data["outcomes"][2]["data"][1]	=  round(((int) $value['femalesuppressed']/$female)*100,1);
				$data["outcomes"][0]["data"][2]	=  (int) $value['nodatanonsuppressed'];
				$data["outcomes"][1]["data"][2]	=  (int) $value['nodatasuppressed'];
				$data["outcomes"][2]["data"][2]	=  round(((int) $value['nodatasuppressed']/$nodata)*100,1);
			}
		} else {
			$count = 0;
			foreach ($result as $key => $value) {
				$suppressed = (int) ($value['Undetected']+$value['less1000']);
				$nonsuppressed = (int) ($value['less5000']+$value['above5000']);
				$totalValidTests = (int) ($suppressed+$nonsuppressed);
				$data['categories'] = $value['name'];
				$data["outcomes"][0]["data"][$count]	=  (int) ($nonsuppressed);
				$data["outcomes"][1]["data"][$count]	=  (int) ($suppressed);
				$data["outcomes"][2]["data"][$count]	=  round(((int) $suppressed/$totalValidTests)*100,1);
				$count++;
			}
		}
		return view('charts.dual_axis', $data);
	}
}
