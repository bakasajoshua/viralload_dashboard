<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lookup;
use DB;
use Str;

class TatController extends Controller
{

    public function outcomes($type = 0)
    {
		extract($this->get_filters());
		$id = 0;
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

		$result = $this->_getData($type);
		
		foreach ($result as $key => $value) {
			if ($key < 70) {
				$data['categories'][$key] = $value->name;
				$data["outcomes"][0]["data"][$key]	= round($value->tat3,1);
				$data["outcomes"][1]["data"][$key]	= round($value->tat2,1);
				$data["outcomes"][2]["data"][$key]	= round($value->tat1,1);
				$data["outcomes"][3]["data"][$key]	= round($value->tat4,1);
			}
		}
		return view('charts.dual_axis', $data);
	}
	
	public function details($type = 0)
	{
		extract($this->get_filters());
		$id = 0;
		$result = $this->_getData($type);
		// echo "<pre>";print_r($result);die();
		$count = 1;
		$table = '';
		if ($type == 0) $title = 'County';
		if ($type == 1) $title = 'Partner';
		if ($type == 2) $title = 'Sub-county';
		if ($type == 3) $title = 'Facility';
		$data['th'] = '<tr class="colhead">
							<th>#</th>
							<th>'.$title.'</th>
							<th>Collection To Receipt</th>
							<th>Receipt To Processing</th>
							<th>Processing To Dispatch</th>
							<th>Collection To Dispatch</th>
						</tr>';
		foreach ($result as $key => $value) {
			$table .= '<td>'.$count.'</td>';
			$table .= '<td>'.$value->name.'</td>';
			$table .= '<td>'.number_format($value->tat1).'</td>';
			$table .= '<td>'.number_format($value->tat2).'</td>';
			$table .= '<td>'.number_format($value->tat3).'</td>';
			$table .= '<td>'.number_format($value->tat4).'</td>';
			$table .= '</tr>';
			$count++;
		}
		$data['outcomes'] = $table;
		return view('tables.datatable', $data);
	}

	public function _getData($type)
	{
		extract($this->get_filters());
		// $type = 0;
		if ($type==null || $type=='null') $type = 0;
		$id = null;
		if ($type == 0 || $type == '0') {
			if ($id==null || $id=='null') $id = $county;
		} else if ($type == 1 || $type == '1') {
			if ($id==null || $id=='null') $id = $partner;
		} else if ($type == 2 || $type == '2') {
			if ($id==null || $id=='null') $id = $subcounty;
		} else if ($type == 3 || $type == '3') {
			if ($id==null || $id=='null') $id = $site;
		}
	
		if ($id==null || $id=='null') $id = 0;
		
		// $sql = "CALL `proc_get_vl_tat_ranking`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$id."')";
		$sql = "CALL `proc_get_vl_tat_ranking`('".$year."','".$month."','".$to_year."','".$to_month."','".$type."','".$id."')";

		
		return DB::select($sql);
	}
}