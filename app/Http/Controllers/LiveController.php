<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lookup;
use DB;
use Str;

class LiveController extends Controller
{

	public function get_dropdown(){
		$labs = DB::table('labs')->get();
		$data = "<option disabled> Select a lab </option>";

		foreach ($labs as $key => $value) {
			$value = get_object_vars($value);
			$data .= "<option value=" . $value['id'] . ">" . $value['name'] . "</option>";

		}
		return $data;
	}

	public function get_data($type=2, $lab=1){
		$sql = "CALL `proc_get_vl_lab_live_data`('".$type."')";
		$sql2 = "CALL `proc_get_vl_live_data_totals`('".$type."')";
		$sql3 = "CALL `proc_get_vl_live_lab_samples`('".$type."', '".$lab."')";
		$sql4 = "CALL `proc_get_vl_live_lab_samples`('".$type."', '0')";

		$result = DB::select($sql);
		$totals = DB::select($sql2);
		$row = DB::select($sql3);
		$row2 = DB::select($sql4);

		$data = null;

		$data['updated'] = date('D d-m-Y g:i a');

		$data['year_to_date'] = number_format($totals[0]->yeartodate) . '/' . number_format($totals[0]->monthtodate);

		foreach ($totals[0] as $key => $value) {
			$data[$key] = number_format((int) $value);
		}

		$data['enteredsamplesatsite'] = (int) $totals[0]->enteredsamplesatsite;
		$data['enteredsamplesatlab'] = (int) $totals[0]->enteredsamplesatlab;
		$data['enteredreceivedsameday'] = (int) $totals[0]->enteredreceivedsameday;
		$data['enterednotreceivedsameday'] = (int) $totals[0]->enterednotreceivedsameday;


		$data['machines'][0] = "Abbot";
		$data['machines'][1] = "Panther";
		$data['machines'][2] = "Roche";

		$data['minprocess'][0] = (int) $totals[0]->abbottinprocess;
		$data['minprocess'][1] = (int) $totals[0]->panthainprocess;
		$data['minprocess'][2] = (int) $totals[0]->rocheinprocess;

		$data['mprocessed'][0] = (int) $totals[0]->abbottprocessed;
		$data['mprocessed'][1] = (int) $totals[0]->panthaprocessed;
		$data['mprocessed'][2] = (int) $totals[0]->rocheprocessed;

		$i=0;


		foreach ($result as $key => $value) {
			$value = get_object_vars($value);
			$data['labs'][$i] = $value['name'];
			$data['enteredsamplesatsitea'][$i] = (int) $value['enteredsamplesatsite'];
			$data['enteredsamplesatlaba'][$i] = (int) $value['enteredsamplesatlab'];
			$data['receivedsamplesa'][$i] = (int) $value['receivedsamples'];
			$data['inqueuesamplesa'][$i] = (int) $value['inqueuesamples'];
			$data['inprocesssamplesa'][$i] = (int) $value['inprocesssamples'];
			$data['processedsamplesa'][$i] = (int) $value['processedsamples'];
			$data['pendingapprovala'][$i] = (int) $value['pendingapproval'];
			$data['dispatchedresultsa'][$i] = (int) $value['dispatchedresults'];
			$data['oldestinqueuesamplea'][$i] = (int) $value['oldestinqueuesample'];

			$phpdate = strtotime( $value['dateupdated'] );
			$data['updated_time'] = date('D d-m-Y g:i a', $phpdate);
			//$data['updated_time'] = $value['dateupdated'];
			$phpdate = strtotime( $value['dateupdated'] );
			$data['updated_time'] = date('D d-m-Y g:i a', $phpdate);

			$i++;

		}

		$data['age_cat'] = array('1 week', '2 weeks', '3 weeks', '&gt; 4 weeks');

		$data['age'][0] = (int) $row[0]->oneweek;
		$data['age'][1] = (int) $row[0]->twoweeks;
		$data['age'][2] = (int) $row[0]->threeweeks;
		$data['age'][3] = (int) $row[0]->aboveamonth;

		$data['age_nat'][0] = (int) $row2[0]->oneweek;
		$data['age_nat'][1] = (int) $row2[0]->twoweeks;
		$data['age_nat'][2] = (int) $row2[0]->threeweeks;
		$data['age_nat'][3] = (int) $row2[0]->aboveamonth;

		return json_encode($data);

	}
}
