<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lookup;
use DB;
use Str;

class LabController extends Controller
{

	public function lab_performance_stat()
	{
		extract($this->get_filters());

		$sql = "CALL `proc_get_vl_lab_performance_stats`('".$year."','".$month."','".$to_year."','".$to_month."');";

		$rows = DB::select($sql);

		$data['div'] = Str::random(15);
		$data['table_head'] = "
			<tr class='colhead'>
				<th>No</th>
				<th>Lab</th>
				<th>Facilities Sending Samples to Lab/Hub</th>
				<th>Facilities Doing Remote Site Entry</th>
				<th>Remote Site Entry %</th>
				<th>Received Samples at Lab</th>
				<th>Rejected Samples (on receipt at lab)</th>
				<th>% Rejection On Receipt at Lab</th>
				<th>All Samples Tested(plus reruns) Done at Lab</th>
				<th>Tests with Valid Results</th>
				<th>Redraws/Invalids after Testing</th>
				<th>EQA Tests</th>
				<th>Controls Run</th>
				<th>Lab TAT (Received-Dispatch)</th>
			</tr>
		";
		$ul = '';
		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);
			$routine = ((int) $value['undetected'] + (int) $value['less1000'] + (int) $value['less5000'] + (int) $value['above5000']);
			$routinesus = ((int) $value['less5000'] + (int) $value['above5000']);
			$name = "POC Sites";

			if(($value['sitesremotelogging'] + $value['sitesending'])){
				$entry_percentage = round((((int) $value['sitesremotelogging'] /((int) $value['sitesending'])) * 100), 2);
			}else{
				$entry_percentage = 0;
			}
			if($value['received']){
				$rejection = round((($value['rejected']*100)/$value['received']), 1, PHP_ROUND_HALF_UP);
			}else{
				$rejection = 0;
			}

			if($value['name']) $name = $value['name'];

			$ul .= "<tr>
						<td>".($key+1)."</td>
						<td>".$name."</td>
						<td>".number_format((int) $value['sitesending'])."</td>
						<td>".number_format((int) $value['sitesremotelogging'])."</td>
						<td>".$entry_percentage."%</td>
						<td>".number_format((int) $value['received'])."</td>
						<td>".number_format((int) $value['rejected']) . "</td>
						<td>".$rejection ."</td>
						<td>".number_format((int) $routine + (int) $value['baseline'] + (int) $value['confirmtx'] + (int) $value['invalids']) ."</td>
						<td>".number_format((int) $routine + (int) $value['baseline'] + (int) $value['confirmtx'])."</td>
						<td>".number_format((int) $value['invalids'])."</td>
						<td>".number_format((int) $value['eqa'])."</td>
						<td>".number_format((int) $value['controls'])."</td>
						<td>".number_format((int) $value['tat2'] + (int) $value['tat3'])."</td>				
					</tr>";
		}
		if (empty($rows))
			$ul .= "<tr><td colspan='15'><center><strong>No Data Found</strong></center></td></tr>";

		$data['rows'] = $ul;
		return view('tables.data-table', $data);
	}




	public function labs_turnaround()
	{
		extract($this->get_filters());

		$title = " (" . $year . ")";
		if($month) $title = " (" . $year . ", " . $this->resolve_month($month) . ")";
		if($to_year) $title = " (" . $year . ", " . $this->resolve_month($month) . " - ". $to_year . ", " . $this->resolve_month($to_month) .")";

		$sql = "CALL `proc_get_labs_tat`(".$year_month_query.",'".$lab."')";
		
		$rows = DB::select($sql);

		if (!($lab == 0 || $lab == '')) {
			$data = $this->bars(['Processing-Dispatch (P-D)', 'Receipt to-Processing (R-P)', 'Collection-Receipt (C-R)','Collection-Dispatch (C-D)'], 'column', ['rgba(0, 255, 0, 0.498039)','rgba(255, 255, 0, 0.498039)','rgba(255, 0, 0, 0.498039)','#913D88'], ['', '','',' Days']);
			$this->columns($data, 3, 3, 'spline');

			$data['outcomes'][0]['yAxis'] = 1;
			$data['outcomes'][1]['yAxis'] = 1;
			$data['outcomes'][2]['yAxis'] = 1;

			$data['title'] = "";
			
			$data['categories'][0] = 'No Data';
			$data["outcomes"][0]["data"][0]	= 0;
			$data["outcomes"][1]["data"][0]	= 0;
			$data["outcomes"][2]["data"][0]	= 0;
			$data["outcomes"][3]["data"][0]	= 0;
			
			foreach ($rows as $key => $value) {
				$data['categories'][$key] = date('F', mktime(0, 0, 0, $value->month, 10));
				$data["outcomes"][0]["data"][$key]	= round($value->tat3,1);
				$data["outcomes"][1]["data"][$key]	= round($value->tat2,1);
				$data["outcomes"][2]["data"][$key]	= round($value->tat1,1);
				$data["outcomes"][3]["data"][$key]	= round($value->tat4,1);
			}

			return view('charts.dual_axis', $data)->render();
		} else {
			$view_data = '';
			$for_labs = true;

			foreach ($rows as $key => $value) {
				$value = get_object_vars($value);
				// $title = strtolower(str_replace(" ", "_", $value['labname']));
				$title = $value['labname'];
				if(!$title) $title = "POC Sites";
				$div = Str::random(15);
				$tat1 = round($value['tat1']);
				$tat2 = round($value['tat2']+$tat1);
				$tat3 = round($value['tat3']+$tat2);
				$tat4 = round($value['tat4']);

				$view_data .= view('charts.tat', compact('tat1', 'tat2', 'tat3', 'tat4', 'div', 'title', 'for_labs'))->render();
			}
			$view_data .= view('charts.tat_key');
			
			return $view_data;
		}		
	}



	public function labs_outcomes()
	{
		extract($this->get_filters());

		$sql = "CALL `proc_get_lab_outcomes`('".$year."','".$month."','".$to_year."','".$to_month."')";
		$rows = DB::select($sql);

		$data = $this->bars(['Not Suppressed', 'Suppressed'], 'column');

		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);
			$data['categories'][$key] = $value['labname'];
			if(!$data['categories'][$key]) $data['categories'][$key] = "POC Sites";
			$data["outcomes"][0]["data"][$key]	=  (int) $value['sustxfl'];
			$data["outcomes"][1]["data"][$key]	=  (int) $value['detectableNless1000'];
		}
		return view('charts.bar_graph', $data);
	}


	public function yearly_trends()
	{
		extract($this->get_filters());

	
		$sql = "CALL `proc_get_vl_yearly_lab_trends`(" . $lab . ");";
		$rows = DB::select($sql);
		
		$year;
		$i = 0;
		$b = true;

		$data;

		$suppression_trends = $this->bars(['Not Suppressed', 'Suppressed'], 'column');

		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);

			if($b){
				$b = false;
				$year = (int) $value['year'];

				$suppression_trends['outcomes'][$i]['data'] = array_fill(0, 12, 0);
				$test_trends['outcomes'][$i]['data'] = array_fill(0, 12, 0);
				$rejected_trends['outcomes'][$i]['data'] = array_fill(0, 12, 0);
				$tat_trends['outcomes'][$i]['data'] = array_fill(0, 12, 0);
			}

			$y = (int) $value['year'];
			if($value['year'] != $year){
				$i++;
				$year--;
			}

			$month = (int) $value['month'];
			$month--;

			$tests = (int) $value['suppressed'] + (int) $value['nonsuppressed'];

			$suppression_trends['outcomes'][$i]['name'] = $value['year'];
			$suppression_trends['outcomes'][$i]['data'][$month] = round(@(($value['suppressed']*100)/$tests), 1, PHP_ROUND_HALF_UP);


			$test_trends['outcomes'][$i]['name'] = $value['year'];
			$test_trends['outcomes'][$i]['data'][$month] = $tests;

			$rejected_trends['outcomes'][$i]['name'] = $value['year'];
			$rejected_trends['outcomes'][$i]['data'][$month] = round(@(($value['rejected']*100)/$value['received']), 1, PHP_ROUND_HALF_UP);

			$tat_trends['outcomes'][$i]['name'] = $value['year'];
			$tat_trends['outcomes'][$i]['data'][$month] = (int) $value['tat4'];

		}
		

		return $data;
	}

	public function lab_site_rejections()
	{	
		extract($this->get_filters());

		if(!$lab) $lab = 0;
		
		$sql = "CALL `proc_get_vl_lab_site_rejections`({$lab}, '{$year}', '{$month}', '{$to_year}', '{$to_month}' );";

		$rows = DB::select($sql);

		$ul = '';
		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);

			$ul .= "<tr>
						<td>".($key+1)."</td>
						<td>".$value['facility']."</td>
						<td>".$value['rejection_reason']."</td>
						<td>".number_format((int) $value['total_rejections'])."</td>						
					</tr>";
		}

		return $ul;
	}

	public function rejections()
	{	
		extract($this->get_filters());
		if(!$lab) $lab = 0;
		
		$sql = "CALL `proc_get_vl_lab_rejections`({$lab}, '{$year}', '{$month}', '{$to_year}', '{$to_month}' );";
		
		$rows = DB::select($sql);

		$data = $this->bars(['Rejected Samples', '% Rejected'], 'column', ['', ' %']);
		$this->columns($data, 1, 1, 'spline');
		$this->yAxis($data, 0, 0);

		$total = 0;
		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);
			$total += $value['total'];
		}		

		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);
			$data['categories'][$key] = $value['alias'];
		
			$data['outcomes'][0]['data'][$key] = (int) $value['total'];
			$data['outcomes'][1]['data'][$key] = round(($value['total']/$total)*100,1);
		}

		if($lab == 0){
			$data['chart_title'] = "National Rejections";
		}
		else{
			$data['chart_title'] = "Lab Rejections";
		}
		return view('charts.dual_axis', $data);
	}


	/*
		POC Routes
	*/

	public function poc_performance_stat()
	{
		extract($this->get_filters());

		if(!$county) $county = 0;
		$sql = "CALL `proc_get_vl_poc_performance_stats`('".$year."','".$month."','".$to_year."','".$to_month."','".$county."');";

		$rows = DB::select($sql);

		$data['div'] = Str::random(15);
		$data['table_head'] = "
			<tr class='colhead'>
				<th>No</th>
				<th>Hub</th>
				<th>MFL</th>
				<th>County</th>
				<th>Facilities Sending Samples</th>
				<th>Received Samples at Hub</th>
				<th>Rejected Samples (on receipt at Hub)</th>
				<th>All Test Done at Hub</th>
				<th>Redraw (after testing)</th>
				<th>Routine VL Tests</th>
				<th>Routine VL Tests &gt; 1000</th>
				<th>Baseline VL Tests</th>
				<th>Baseline VL Tests &gt; 1000</th>
				<th>Confirmatory Repeat Tests</th>
				<th>Confirmatory Repeat Tests &gt; 1000</th>
				<th>Total Tests with Valid Outcomes</th>
				<th>Total Tests &gt; 1000 with Valid Outcomes</th>
				<th>View Spoke Details</th>
			</tr>
		";

		$ul = '';
		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);
			$routine = ((int) $value['undetected'] + (int) $value['less1000'] + (int) $value['less5000'] + (int) $value['above5000']);
			$routinesus = ((int) $value['less5000'] + (int) $value['above5000']);
			$name = "POC Sites";
			if($value['name']) $name = $value['name'];
			$ul .= "<tr>
						<td>".($key+1)."</td>
						<td>".$name."</td>
						<td>".$value['facilitycode']."</td>
						<td>".$value['county']."</td>
						<td>".number_format((int) $value['sitesending'])."</td>
						<td>".number_format((int) $value['received'])."</td>
						<td>".number_format((int) $value['rejected']) . " (" . 
							round((($value['rejected']*100)/$value['received']), 1, PHP_ROUND_HALF_UP)."%)</td>
						<td>".number_format((int) $routine + (int) $value['baseline'] + (int) $value['confirmtx'] + (int) $value['invalids'])."</td>
						<td>".number_format((int) $value['invalids'])."</td>
						<td>".number_format($routine)."</td>
						<td>".number_format($routinesus)."</td>
						<td>".number_format((int) $value['baseline'])."</td>
						<td>".number_format((int) $value['baselinesustxfail'])."</td>
						<td>".number_format((int) $value['confirmtx'])."</td>
						<td>".number_format((int) $value['confirm2vl'])."</td>
						<td>".number_format((int) $routine + (int) $value['baseline'] + (int) $value['confirmtx'])."</td>
						<td>".number_format((int) $routinesus + (int) $value['baselinesustxfail'] + (int) $value['confirm2vl'])."</td>
						<td> <button class='btn btn-primary' onclick='expand_poc(" . $value['id'] . ");' style='background-color: #1BA39C;color: white; margin-top: 1em;margin-bottom: 1em;'>View Spokes</button> </td>						
					</tr>";
		}
		$data['rows'] = $ul;
		return view('tables.data-table', $data);
	}


	public function poc_performance_details($facility_id)
	{
		extract($this->get_filters());

		$sql = "CALL `proc_get_vl_poc_site_details`('".$facility_id."','".$year."','".$month."','".$to_year."','".$to_month."');";

		$rows = DB::select($sql);

		$data['div'] = Str::random(15);

		$ul = '';
		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);
			$routinesus = ((int) $value['less5000'] + (int) $value['above5000']);
			$routine = ((int) $value['undetected'] + (int) $value['less1000'] + (int) $value['less5000'] + (int) $value['above5000']);
			$routinesup = ((int) $value['undetected'] + (int) $value['less1000']);
			if($routine){
				$suppressed = round((($routinesup*100)/$routine), 1, PHP_ROUND_HALF_UP);
			}else{
				$suppressed = 0;
			}
			$name = "POC Sites";
			if($value['name']) $name = $value['name'];
			$ul .= "<tr>
						<td>".($key+1)."</td>
						<td>".$value['name']."</td>
						<td>".$value['facilitycode']."</td>
						<td>".$value['county']."</td>
						<td>".number_format((int) $value['received'])."</td>
						<td>".number_format((int) $value['rejected']) . " (" . 
							round((($value['rejected']*100)/$value['received']), 1, PHP_ROUND_HALF_UP)."%)</td>
						<td>".number_format((int) $routine + (int) $value['baseline'] + (int) $value['confirmtx'] + (int) $value['invalids'])."</td>
						<td>".number_format((int) $value['invalids'])."</td>
						<td>".number_format($routine)."</td>
						<td>".number_format($routinesus)."</td>
						<td>".number_format((int) $value['baseline'])."</td>
						<td>".number_format((int) $value['baselinesustxfail'])."</td>
						<td>".number_format((int) $value['confirmtx'])."</td>
						<td>".number_format((int) $value['confirm2vl'])."</td>
						<td>".number_format((int) $routine + (int) $value['baseline'] + (int) $value['confirmtx'])."</td>
						<td>".number_format((int) $routinesus + (int) $value['baselinesustxfail'] + (int) $value['confirm2vl'])."</td>
						<td>".number_format($routinesup)."</td>
						<td>".$suppressed."</td>

						<td>".number_format((int) $value['adults'])."</td>
						<td>".number_format((int) $value['paeds'])."</td>
						<td>".number_format((int) $value['rejected'])."</td>				
					</tr>";
		}
		$data['rows'] = $ul;
		return view('tables.poc_site_details', $data);
	}

	public function test_trends()
	{
		extract($this->get_filters());

		$sql = "CALL `proc_get_labs_testing_trends`('".$year."', '".$lab."')";
		
		$rows = DB::select($sql);
		
		$data['div'] = Str::random(15);

		if (!empty($rows)) {
		$months = array(1,2,3,4,5,6,7,8,9,10,11,12);

		$count = count($rows);
		$labname = 'No Data';
		foreach ($months as $key => $value1) {
			$data['categories'][$key] = date("F", mktime(null, null, null, $value1));
			foreach ($rows as $key2 => $value2) {				
				if ((int) $value1 == (int) $value2->month) {
					$labname = $value2->labname;
					$data['outcomes'][0]['name'] = $value2->labname;
					$data['outcomes'][0]['data'][$key] = (int) $value2->alltests;
				}
			}

			
			for ($i=$count; $i < 12; $i++) {
				$data['outcomes'][0]['name'] = $labname;
				$data['outcomes'][0]['data'][$i] = 0;
			}
			// if(!isset($data['test_trends'][$key]['data'][$count])) $data['test_trends'][$key]['data'][$count]=0;
			// $count++;
		}

		// dd($data);

		// 	$categories = array();
		// 	$categories2 = array();
		// 	foreach ($rows as $key => $value) {
		// 		if (!in_array($value['labname'], $categories2)) {
		// 			$labname = "POC Sites";
		// 			if($value['labname']) $labname = $value['labname'];
		// 			$categories[] = $labname;
		// 			$categories2[] = $value['labname'];
		// 		}
		// 	}
		// 	// print_r($categories);die();

		// 	$months = array(1,2,3,4,5,6,7,8,9,10,11,12);
		// 	$count = 0;
		// 	foreach ($categories as $key => $value) {
		// 		foreach ($months as $key1 => $value1) {
		// 			foreach ($row as $key2 => $value2) {
		// 				if ((int) $value1 == (int) $value2['month'] && $categories2[$key] == $value2['labname']) {
		// 					// $data['test_trends'][$key]['data'][$count] = (int) $value2['alltests'] + (int) $value['eqa'] + (int) $value['confirmtx'];
		// 					$data['test_trends'][$key]['name'] = $value;
		// 					$data['test_trends'][$key]['data'][$count] = (int) $value2['alltests'];
		// 				}
		// 			}
		// 			if(!isset($data['test_trends'][$key]['data'][$count])) $data['test_trends'][$key]['data'][$count]=0;
		// 			$count++;
		// 		}
		// 		$count = 0;
		// 	}
		} else {
			echo "<pre>";print_r("NO TESTING TRENDS DATA FOUND FOR THE SELECTED PERIOD!");echo "</pre>";die();
		}

		// $sql2 = "CALL `proc_get_avg_labs_testing_trends`('".$year."', '".$lab."')";
		// $rows2 = DB::select($sql);

		// $i = count($data['test_trends']);
		// $count = 0;
		// foreach ($rows2 as $key => $value) {
				
		// 	$data['test_trends'][$i]['name'] = 'Average Lab Testing Volumes';
		// 	$data['test_trends'][$i]['data'][$count] = (int) $value['alltests'];
		// 	$count++;
		// }

		
		// //echo "<pre>";print_r($result2);die();
		return view('charts.line_graph', $data);
	}

	public function rejection_trends()
	{
		extract($this->get_filters());

		$sql = "CALL `proc_get_labs_testing_trends`('".$year."', '".$lab."')";
		
		$rows = DB::select($sql);
		
		$data['div'] = Str::random(15);

		if (!empty($rows)) {
		$months = array(1,2,3,4,5,6,7,8,9,10,11,12);

		$count = count($rows);
		$labname = 'No Data';
		foreach ($months as $key => $value1) {
			$data['categories'][$key] = date("F", mktime(null, null, null, $value1));
			foreach ($rows as $key2 => $value2) {				
				if ((int) $value1 == (int) $value2->month) {
					$labname = $value2->labname;
					$data['outcomes'][0]['name'] = $value2->labname;
					$data['outcomes'][0]['data'][$key] = round(@((int) $value2->rejected * 100 / (int) $value2->received), 1);
				}
			}

			
			for ($i=$count; $i < 12; $i++) {
				$data['outcomes'][0]['name'] = $labname;
				$data['outcomes'][0]['data'][$i] = 0;
			}
			// if(!isset($data['test_trends'][$key]['data'][$count])) $data['test_trends'][$key]['data'][$count]=0;
			// $count++;
		}

		// dd($data);

		// 	$categories = array();
		// 	$categories2 = array();
		// 	foreach ($rows as $key => $value) {
		// 		if (!in_array($value['labname'], $categories2)) {
		// 			$labname = "POC Sites";
		// 			if($value['labname']) $labname = $value['labname'];
		// 			$categories[] = $labname;
		// 			$categories2[] = $value['labname'];
		// 		}
		// 	}
		// 	// print_r($categories);die();

		// 	$months = array(1,2,3,4,5,6,7,8,9,10,11,12);
		// 	$count = 0;
		// 	foreach ($categories as $key => $value) {
		// 		foreach ($months as $key1 => $value1) {
		// 			foreach ($row as $key2 => $value2) {
		// 				if ((int) $value1 == (int) $value2['month'] && $categories2[$key] == $value2['labname']) {
		// 					// $data['test_trends'][$key]['data'][$count] = (int) $value2['alltests'] + (int) $value['eqa'] + (int) $value['confirmtx'];
		// 					$data['test_trends'][$key]['name'] = $value;
		// 					$data['test_trends'][$key]['data'][$count] = (int) $value2['alltests'];
		// 				}
		// 			}
		// 			if(!isset($data['test_trends'][$key]['data'][$count])) $data['test_trends'][$key]['data'][$count]=0;
		// 			$count++;
		// 		}
		// 		$count = 0;
		// 	}
		} else {
			echo "<pre>";print_r("NO TESTING TRENDS DATA FOUND FOR THE SELECTED PERIOD!");echo "</pre>";die();
		}

		// $sql2 = "CALL `proc_get_avg_labs_testing_trends`('".$year."', '".$lab."')";
		// $rows2 = DB::select($sql);

		// $i = count($data['test_trends']);
		// $count = 0;
		// foreach ($rows2 as $key => $value) {
				
		// 	$data['test_trends'][$i]['name'] = 'Average Lab Testing Volumes';
		// 	$data['test_trends'][$i]['data'][$count] = (int) $value['alltests'];
		// 	$count++;
		// }

		
		// //echo "<pre>";print_r($result2);die();
		return view('charts.line_graph', $data);
	}

}
