<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
* 
*/
class Template_model extends MY_Model
{
	
	function __construct()
	{
		parent:: __construct();
	}

	function get_counties_dropdown()
	{
		$dropdown = '';
		$county_data = $this->db->get('countys')->result_array();

		foreach ($county_data as $key => $value) {
			$dropdown .= '<option value="'.$value['ID'].'">'.$value['name'].'</option>';
		}
		
		return $dropdown;
	}
}
?>