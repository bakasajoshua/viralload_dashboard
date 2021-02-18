<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Str;
use GuzzleHttp\Client;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


	public function get_filters()
	{
		$year = session('filter_year', date('Y'));
		$month = session('filter_month', 0);
		$to_year = session('to_year', 0) ?? 0;
		$to_month = session('to_month', 0) ?? 0;
		// $type = session('filter_type', 1);
		if(!$month) $month = 0;

		$from = $year -1;
		$to = $year;

		$api_type = 0;
		if (!$month) $api_type = 1;

		if ($api_type == 0) {
			if($to_year == 0){
				$api_type = 3;
			}
			else{
				$api_type = 5;
			}
		}


		$agency = session('funding_agency_filter');
		$partner = session('partner_filter');
		if(is_numeric($partner)) $partner = (int) $partner;
		$county = session('county_filter');
		$subcounty = session('sub_county_filter');
		$site = session('site_filter');
		$lab = session('lab_filter') ?? 0;

		$national = 0;
		if(!$county && !$partner && !$subcounty && !$site) $national = 1;

		$pmtct = session('pmtct_filter');
		$regimen = session('regimen_filter');
		$age_cat = session('age_filter');
		$age_cat = $this->build_Inarray($age_cat);

		$year_month_query = "'".$year."','".$month."','".$to_year."','".$to_month."'";

		return compact('year_month_query', 'year', 'month', 'to_year', 'to_month', 'from', 'to', 'national', 'agency', 'partner', 'county', 'subcounty', 'site', 'lab', 'pmtct', 'regimen', 'age_cat', 'api_type');
	}

	public static function bars($categories=[], $type='column', $colours=[], $suffixes=[])
	{
		$data['div'] = Str::random(15);
		foreach ($categories as $key => $value) {
			$data['outcomes'][$key]['name'] = $value;
			$data['outcomes'][$key]['type'] = $type;
			$data['outcomes'][$key]['tooltip'] = ["valueSuffix" => ($suffixes[$key] ?? ' ')];
			if(isset($colours[$key]) && $colours[$key]) $data['outcomes'][$key]['color'] = $colours[$key];
		}
		return $data;
	}

	public static function columns(&$data, $start, $finish, $type='column')
	{
		for ($i=$start; $i <= $finish; $i++) { 
			$data['outcomes'][$i]['type'] = $type;
		}
	}

	public static function yAxis(&$data, $start, $finish, $axis=1)
	{
		for ($i=$start; $i <= $finish; $i++) { 
			$data['outcomes'][$i]['yAxis'] = $axis;
		}
	}



	public function build_Inarray($array = null)
	{
		if (is_null($array)) return null;
		$query = "IN (";
		$elements = sizeof($array);
		foreach ($array as $key => $value) {
			($key+1 == $elements) ? $query .= $value : $query .= $value . ",";
		}
		$query .= ")";
		return $query;
	}	

	public function split_ages($ages=null)
	{
		if ($ages == null) return null;

		$ages =  explode(".", $ages);
		$selected = sizeof($ages);
		$returnAges = array();
		for ($i=0; $i < $selected; $i++) { 
			if ($i != 0) {
				$returnAges[] = $ages[$i];
			}
		}
		return $returnAges;
	}		

	public function get_months()
	{
		return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	}
	
	public function resolve_month($month)
	{
		$months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
		return $months[$month] ?? '';
	}


	public function req($url)
	{
		$base = "http://api.nascop.org/vl/ver2.0/";
		$client = new Client(['base_uri' => $base]);
		$response = $client->request('get', $url);
		return json_decode($response->getBody());
	}



	public $genderArray = [
		'F' => [
			'tests' => 'femaletests',
			'sustx' => 'femalesustx',
		],
		'M' => [
			'tests' => 'maletests',
			'sustx' => 'malesustx',
		],
		'No Data' => [
			'tests' => 'Nodatatests',
			'sustx' => 'Nodatasustx',
		],
	];

	public $ageArray = [
		'Less 2' => [
			'tests' => 'less2tests',
			'sustx' => 'less2sustx',				
		],
		'2-9' => [
			'tests' => 'less9tests',
			'sustx' => 'less9sustx',				
		],
		'10-14' => [
			'tests' => 'less14tests',
			'sustx' => 'less14sustx',				
		],
		'15-19' => [
			'tests' => 'less19tests',
			'sustx' => 'less19sustx',				
		],
		'20-24' => [
			'tests' => 'less25tests',
			'sustx' => 'less25sustx',				
		],
		'25+' => [
			'tests' => 'above25tests',
			'sustx' => 'above25sustx',				
		],
	];
}
