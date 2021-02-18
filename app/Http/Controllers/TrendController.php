<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lookup;
use DB;
use Str;

class TrendController extends Controller
{


	public function monthly_trends($division)
	{
		extract($this->get_filters());

		if($division == 4){
			$sql = "CALL `proc_get_sites_trends`('".$site."','".$year."')";
		}

		$rows = DB::select($sql);

		$data = $this->bars(['Tests', 'Suppressed', 'Non Suppressed', 'Rejected', ], 'spline');
		$data['categories'] = $this->get_months();

		foreach($data['categories'] as $cat_key => $category){
			foreach ($rows as $key2 => $value2) {
				$value2 = get_object_vars($value2);
				$value1 = $cat_key + 1;
				if ((int) $value1 == (int) $value2['month']) {
					$data['outcomes'][0]['data'][$cat_key] = ((int) $value2['undetected']+(int) $value2['less1000']+(int) $value2['less5000']+(int) $value2['above5000'] + (int) $value2['confirmtx'] + (int) $value2['baseline']);
					$data['outcomes'][1]['data'][$cat_key] = (int) $value2['undetected']+(int) $value2['less1000'];
					$data['outcomes'][2]['data'][$cat_key] = (int) $value2['less5000']+(int) $value2['above5000'];
					$data['outcomes'][3]['data'][$cat_key] = (int) $value2['rejected'];

				}				
			}			
		}
		return view('charts.line_graph', $data);
	}

	public function monthly_sample_types($division)
	{
		extract($this->get_filters());

		if($division == 4){
			$sql = "CALL `proc_get_sites_sample_types`('".$site."','".$year."')";
		}

		$rows = DB::select($sql);

		$data = $this->bars(['DBS', 'Plasma', ], 'bar');
		$data['categories'] = $this->get_months();

		foreach($data['categories'] as $cat_key => $category){
			foreach ($rows as $key => $value) {
				$value = get_object_vars($value);
				// $month = $cat_key + 1;
				if (($cat_key + 1) == (int) $value['month']) {
					$data["outcomes"][0]["data"][$cat_key]	= (int) $value['dbs'];
					$data["outcomes"][1]["data"][$cat_key]	= (int) ($value['plasma'] + $value['edta']);

				}				
			}			
		}
		return view('charts.bar_graph', $data);
	}	

	public function yearly_trends()
	{
		extract($this->get_filters());

		if($partner){
			$sql = "CALL `proc_get_vl_partner_yearly_trends`(" . $partner . ");";
		}else if ($county) {
			$sql = "CALL `proc_get_vl_yearly_trends`(" . $county . ");";
		} else {
			$sql = "CALL `proc_get_vl_national_yearly_trends`();";
		}

		$rows = DB::select($sql);

		$data_suppression = $this->bars(['Suppression Rate (%)'], 'spline', [], [' %']);
		$data_tests = $this->bars(['Number of Valid Tests'], 'spline', [], [' ']);
		$data_rejection = $this->bars(['Rejection (%)'], 'spline', [], [' %']);
		$data_tat = $this->bars(['TAT (Days)'], 'spline', [], [' ']);

		$data_suppression['categories'] = $data_tests['categories'] = $data_rejection['categories'] = $data_tat['categories'] = $this->get_months();
		$data_suppression['div_class'] = $data_tests['div_class'] = $data_rejection['div_class'] = $data_tat['div_class'] = 'col-md-6';

		$data_suppression['chart_title'] = 'Suppression Trends';
		$data_tests['chart_title'] = 'Testing Trends';
		$data_rejection['chart_title'] = 'Rejection Rate Trends';
		$data_tat['chart_title'] = 'Turn Around Time (Collection - Dispatch)';

		$data_suppression['yAxis'] = 'Suppression Rate (%)';
		$data_tests['yAxis'] = 'Number of Valid Tests';
		$data_rejection['yAxis'] = 'Rejection (%)';
		$data_tat['yAxis'] = 'TAT (Days)';

		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);

			if($b){
				$b = false;
				$year = (int) $value['year'];
			}

			$y = (int) $value['year'];
			if($value['year'] != $year){
				$i++;
				$year--;
			}

			$month = (int) $value['month'];
			$month--;

			$tests = (int) $value['suppressed'] + (int) $value['nonsuppressed'];

			$data_suppression['outcomes'][$i]['name'] = $value['year'];
			$data_suppression['outcomes'][$i]['data'][$month] = round(@(($value['suppressed']*100)/$tests), 4, PHP_ROUND_HALF_UP);

			$data_tests['outcomes'][$i]['name'] = $value['year'];
			$data_tests['outcomes'][$i]['data'][$month] = $tests;

			$data_rejection['outcomes'][$i]['name'] = $value['year'];
			$data_rejection['outcomes'][$i]['data'][$month] = round(@(($value['rejected']*100)/$value['received']), 4, PHP_ROUND_HALF_UP);

			$data_tat['outcomes'][$i]['name'] = $value['year'];
			$data_tat['outcomes'][$i]['data'][$month] = (int) $value['tat4'];
		}
		$view_data = view('charts.line_graph', $data_suppression)->render() . view('charts.line_graph', $data_tests)->render() . view('charts.line_graph', $data_rejection)->render() . view('charts.line_graph', $data_tat)->render();
		return $view_data;
	}



	public function yearly_summary()
	{
		extract($this->get_filters());


		if($partner){
			$sql = "CALL `proc_get_vl_partner_yearly_summary`(" . $partner . ");";
		}else if ($county) {
			$sql = "CALL `proc_get_vl_yearly_summary`(" . $county . ");";
		} else {
			$sql = "CALL `proc_get_vl_national_yearly_summary`();";
		}

		$rows = DB::select($sql);

		$data = $this->bars(['Nonsuppressed', 'Suppressed', 'Suppression'], 'column', [], [' ', ' ', ' %']);
		$this->columns($data, 2, 2, 'spline');
		$data['outcomes'][0]['yAxis'] = 1;
		$data['outcomes'][1]['yAxis'] = 1;


		foreach ($rows as $i => $value) {
			$value = get_object_vars($value);
			$data['categories'][$i] = $value['year'];
			
			$data['outcomes'][0]['data'][$i] = (int) $value['nonsuppressed'];
			$data['outcomes'][1]['data'][$i] = (int) $value['suppressed'];
			$data['outcomes'][2]['data'][$i] = round(@(((int) $value['suppressed']*100)/((int) $value['suppressed']+(int) $value['nonsuppressed'])),1);
		}
		return view('charts.dual_axis', $data);
	}


	public function yearly_age_summary()
	{
		extract($this->get_filters());


		if($partner){
			$sql = "CALL `proc_get_vl_yearly_summary`(" . $partner . ");";
		}else if ($county) {
			$sql = "CALL `proc_get_vl_yearly_summary`(" . $county . ");";
		} else {
			$sql = "CALL `proc_get_vl_national_yearly_tests_age`();";
		}

		$rows = DB::select($sql);

		$data = $this->bars(['No Data', '25+', '20-24', '15-19', '10-14', '2-9', 'Less 2', 'Less 19 Contribution'], 'column');
		$this->yAxis($data, 0, 6);
		$data['outcomes'][7]['type'] = "spline";
		$data['outcomes'][7]['tooltip'] = array("valueSuffix" => ' %');

		$b = true;

		$age_categories = [0 => 0, 6 => 6, 7 => 5, 8 => 4, 9 => 3, 10 => 2, 11 => 1];

		$i=0;

		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);

			if($b){
				$b = false;
				$year = (int) $value['year'];				
			}

			if($year != $value['year']){
				$total = $data['outcomes'][0]['data'][$i] + $data['outcomes'][1]['data'][$i] + $data['outcomes'][2]['data'][$i] + $data['outcomes'][3]['data'][$i] + $data['outcomes'][4]['data'][$i] + $data['outcomes'][5]['data'][$i] + $data['outcomes'][6]['data'][$i];

				$numerator = $data['outcomes'][3]['data'][$i] + $data['outcomes'][4]['data'][$i] + $data['outcomes'][5]['data'][$i] + $data['outcomes'][6]['data'][$i];

				$data['outcomes'][7]['data'][$i] = round(@( $numerator*100 / $total ),1);

				$year++;
				$i++;
			}

			$data['categories'][$i] = $value['year'];
			$age = (int) $value['age'];

			$data['outcomes'][$age_categories[$age]]['data'][$i] = (int) $value['tests'];
		}
		$total = $data['outcomes'][0]['data'][$i] + $data['outcomes'][1]['data'][$i] + $data['outcomes'][2]['data'][$i] + $data['outcomes'][3]['data'][$i] + $data['outcomes'][4]['data'][$i] + $data['outcomes'][5]['data'][$i] + $data['outcomes'][6]['data'][$i];

		$numerator = $data['outcomes'][3]['data'][$i] + $data['outcomes'][4]['data'][$i] + $data['outcomes'][5]['data'][$i] + $data['outcomes'][6]['data'][$i];

		$data['outcomes'][7]['data'][$i] = round(@( $numerator*100 / $total ),1);

		return view('charts.line_graph', $data);
	}



	public function quarterly_trends()
	{
		extract($this->get_filters());


		if($partner){
			$sql = "CALL `proc_get_vl_partner_yearly_trends`(" . $partner . ");";
		}else if ($county) {
			$sql = "CALL `proc_get_vl_yearly_trends`(" . $county . ");";
		} else {
			$sql = "CALL `proc_get_vl_national_yearly_trends`();";
		}

		$rows = DB::select($sql);

		$data_suppression = $this->bars(['Suppression Rate (%)'], 'spline', [], [' %']);
		$data_tests = $this->bars(['Number of Valid Tests'], 'spline', [], [' ']);
		$data_rejection = $this->bars(['Rejection (%)'], 'spline', [], [' %']);
		$data_tat = $this->bars(['TAT (Days)'], 'spline', [], [' ']);

		$data_suppression['categories'] = $data_tests['categories'] = $data_rejection['categories'] = $data_tat['categories'] = $this->get_months();
		$data_suppression['div_class'] = $data_tests['div_class'] = $data_rejection['div_class'] = $data_tat['div_class'] = 'col-md-6';

		$data_suppression['chart_title'] = 'Suppression Trends';
		$data_tests['chart_title'] = 'Testing Trends';
		$data_rejection['chart_title'] = 'Rejection Rate Trends';
		$data_tat['chart_title'] = 'Turn Around Time (Collection - Dispatch)';

		$data_suppression['yAxis'] = 'Suppression Rate (%)';
		$data_tests['yAxis'] = 'Number of Valid Tests';
		$data_rejection['yAxis'] = 'Rejection (%)';
		$data_tat['yAxis'] = 'TAT (Days)';
		
		$year;
		$i = 0;
		$b = true;
		$limit = 0;
		$quarter = 1;

		$data;

		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);

			if($b){
				$b = false;
				$year = (int) $value['year'];
			}

			$y = (int) $value['year'];
			$name = $y . ' Q' . $quarter;
			if($value['year'] != $year){
				$year--;
				if($month != 2){
					$i++;
				}
			}

			$month = (int) $value['month'];
			$modulo = ($month % 3);

			$month= $modulo-1;

			if($modulo == 0){
				$month = 2;
			}			

			$tests = (int) $value['suppressed'] + (int) $value['nonsuppressed'];

			$data_suppression['outcomes'][$i]['name'] = $name;
			$data_suppression['outcomes'][$i]['data'][$month] = round(@(($value['suppressed']*100)/$tests), 4, PHP_ROUND_HALF_UP);

			$data_tests['outcomes'][$i]['name'] = $name;
			$data_tests['outcomes'][$i]['data'][$month] = $tests;

			$data_rejection['outcomes'][$i]['name'] = $name;
			$data_rejection['outcomes'][$i]['data'][$month] = round(@(($value['rejected']*100)/$value['received']), 4, PHP_ROUND_HALF_UP);

			$data_tat['outcomes'][$i]['name'] = $name;
			$data_tat['outcomes'][$i]['data'][$month] = (int) $value['tat4'];

			if($modulo == 0){
				$i++;
				$quarter++;
				$limit++;
			}
			if($quarter == 5) $quarter = 1;
			if ($limit == 8) break;
		}
		$view_data = view('charts.line_graph', $data_suppression)->render() . view('charts.line_graph', $data_tests)->render() . view('charts.line_graph', $data_rejection)->render() . view('charts.line_graph', $data_tat)->render();
		return $view_data;
	}

	public function quarterly_outcomes()
	{
		extract($this->get_filters());


		if($partner){
			$sql = "CALL `proc_get_vl_partner_yearly_trends`(" . $partner . ");";
		}else if ($county) {
			$sql = "CALL `proc_get_vl_yearly_summary`(" . $county . ");";
		} else {
			$sql = "CALL `proc_get_vl_national_yearly_summary`();";
		}

		$rows = DB::select($sql);

		$data = $this->bars(['Nonsuppressed', 'Suppressed', 'Suppression'], 'column', [], [' ', ' ', ' %']);
		$this->columns($data, 2, 2, 'spline');
		$this->yAxis($data, 0, 1);


		
		$year;
		$years = 1;
		$prev_year = date('Y') - 1;
		$cur_month = date('m');

		$extra = ceil($cur_month / 3);
		$columns = 8 + $extra;

		$b = true;
		$quarter = 1;

		$i = 8;

		$data['categories'] = array_fill(0, $columns, "Null");
		$data['outcomes'][0]['data'] = array_fill(0, $columns, 0);
		$data['outcomes'][1]['data'] = array_fill(0, $columns, 0);
		$data['outcomes'][2]['data'] = array_fill(0, $columns, 0);

		foreach ($rows as $i => $value) {
			$value = get_object_vars($value);


			if($b){
				$b = false;
				$year = (int) $value['year'];
			}

			$y = (int) $value['year'];
			$name = $y . ' Q' . $quarter;
			if($value['year'] != $year){
				$year--;
				$years++;

				if($years > 3) break;

				if($year == $prev_year){
					$i = 4;
					$quarter=1;
				}
			}

			$month = (int) $value['month'];
			$modulo = ($month % 3);

			$data['categories'][$i] = $name;

			$data['outcomes'][0]['data'][$i] += (int) $value['nonsuppressed'];
			$data['outcomes'][1]['data'][$i] += (int) $value['suppressed'];	
			$data['outcomes'][2]['data'][$i] = round(@(( $data['outcomes'][1]['data'][$i]*100)/
				($data['outcomes'][0]['data'][$i]+$data['outcomes'][1]['data'][$i])),1);		

			if($modulo == 0){
				$i++;
				$quarter++;
			}
			if($quarter == 5){
				$quarter = 1;
				$i = 0;
			}	
		}
		$view_data = view('charts.line_graph', $data_suppression)->render() . view('charts.line_graph', $data_tests)->render() . view('charts.line_graph', $data_rejection)->render() . view('charts.line_graph', $data_tat)->render();
		return $view_data;
	}
}
