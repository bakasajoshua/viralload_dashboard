<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lookup;
use DB;
use Str;

class PmtctController extends Controller
{

	public function outcomes()
	{
		extract($this->get_filters());

		// groupby -> 0 === Year, Month
		// groupby -> 1 === County
		// groupby -> 2 === Subcounty
		// groupby -> 3 === Partner
		// groupby -> 4 === Facility

		$groupby = 0;
		if($national){
			$groupby = 1;
			$national = 0;
		}

		$sql = "CALL `proc_get_vl_pmtct_suppression`('".$pmtct."','".$groupby."','".$year."','".$month."','".$to_year."','".$to_month."','".$national."','".$county."','".$partner."','".$subcounty."','".$site."')";

		$rows = DB::select($sql);

		$data = $this->bars(['Not Suppressed', 'LLV', 'LDL', 'Suppression', config('var.suppression_target') . '% Target'], 'column', ['#F2784B', '#66ff66', '#1BA39C'], ['', '', '', ' %', ' %']);
		$this->columns($data, 3, 4, 'spline');
		$this->yAxis($data, 0, 2);

		foreach ($rows as $key => $value) {
			$value = get_object_vars($value);

			// if(isset($value['month'])) $data['categories'][$key] = $this->resolve_month($value['month'])." - ".$value['year'];	
			if($groupby == 0) $data['categories'][$key] = $this->resolve_month($value['month'])." - ".$value['year'];	
			else{
				$data['categories'][$key] = $value['name'];
			}

					
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

}
