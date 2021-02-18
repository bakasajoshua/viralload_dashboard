<?php

namespace App;

use DB;
use Str;

class Lookup 
{
	
	public static function resolve_month($m)
	{
		$months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
		return $months[$m] ?? '';
	}
	
	public static function get_percentage($num, $den, $roundby=2)
	{
		if(!$den){
			$val = 0;
		}else{
			$val = round(($num / $den * 100), $roundby);
		}
		return $val;
	}

	public static function get_dropdown($type)
	{
		
		$details = [
			'County' => [
				'filter_name' => 'county_filter',
				'table_name' => 'countys',
				'default_option' => 'National',
			],
			'Sub County' => [
				'filter_name' => 'sub_county_filter',
				'table_name' => 'districts',
				'default_option' => 'National',
			],
			'Facility' => [
				'filter_name' => 'site_filter',
				'table_name' => 'facilitys',
				'default_option' => 'All Facilities',
			],
			'Partner' => [
				'filter_name' => 'partner_filter',
				'table_name' => 'partners',
				'default_option' => 'All Partners',
			],
			'Lab' => [
				'filter_name' => 'lab_filter',
				'table_name' => 'labs',
				'default_option' => 'All Labs',
			],
			'Regimen' => [
				'filter_name' => 'regimen_filter',
				'table_name' => 'viralregimen',
				'default_option' => 'All Regimen',
			],
			'Age Category' => [
				'filter_name' => 'age_filter',
				'table_name' => 'agecategory',
				'default_option' => 'All Age Categories',
			],
			'Funding Agency' => [
				'filter_name' => 'funding_agency_filter',
				'table_name' => 'funding_agencies',
				'default_option' => 'All Funding Agencies',
			],
		];
		$r = $details[$type];
		$r['display_date'] = '(' . date('Y') . ')';
		$r['dropdown_type'] = $type;
		if($type == 'Facility') return $r;
		$r['divisions'] = DB::table($r['table_name'])->select('id', 'name')
			->when($type == 'Regimen', function($query){
				return $query->addSelect('code');
			})
			->when($type == 'Lab', function($query){
				return $query->addSelect('labname');
			})
			->orderBy('name', 'ASC')->get();
		return $r;
	}
}
