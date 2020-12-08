<?php
defined('BASEPATH') or exit('No direct script access allowed!');

/**
* 
*/
class Subcounties extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('subcounty_model');
		$this->load->model('county_model');
	}

	function subcounty_outcomes($year=NULL,$month=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['trends']= $this->subcounty_model->subcounty_outcomes($year,$month,$to_year,$to_month);
		$data['div_name'] = "subcounty_subcounties_outcomes";		

		$this->load->view('trends_outcomes_view', $data);
	}

	function subcounties_table($year=NULL,$month=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes'] = $this->subcounty_model->county_subcounties($year,$month,$to_year,$to_month);
		$data['sites'] = TRUE;
		$data['sub_county'] = TRUE;

		$link = '';

		$data['link'] =  base_url('charts/county/download_subcounty_table/' . $link);
		$data['table_div'] = "random_table";

    	$this->load->view('counties_table_view',$data);
	}
	
	function subcounty_vl_outcomes($year=NULL,$month=NULL,$subcounty=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes']= $this->subcounty_model->subcounty_vl_outcomes($year,$month,$subcounty,$to_year,$to_month);

		$this->load->view('vl_outcomes_view',$data);
	}

	function subcounty_gender($year=NULL,$month=NULL,$subcounty=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes'] = $this->subcounty_model->subcounty_gender($year,$month,$subcounty,$to_year,$to_month);
		
    	$this->load->view('gender_view',$data);
	}

	function subcounty_age($year=NULL,$month=NULL,$subcounty=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes'] = $this->subcounty_model->subcounty_age($year,$month,$subcounty,$to_year,$to_month);
		
    	// $this->load->view('agegroup_view',$data);
    	$this->load->view('agegroup_view',$data);
	}

	// function sample_types($year=NULL,$subcounty=NULL, $all=NULL)
	// {
	// 	$data['outcomes'] = $this->subcounty_model->subcounty_samples($year,$subcounty, $all);
	// 	$link = $year . '/' . $subcounty;

	// 	$data['link'] = base_url('charts/subcounties/download_sampletypes/' . $link);

 //    	$this->load->view('national_sample_types',$data);
	// }

	// function download_sampletypes($year=NULL,$subcounty=NULL)
	// {
	// 	$this->subcounty_model->download_sampletypes($year,$subcounty);
	// }

	function subcounty_sites($year=NULL,$month=NULL,$subcounty=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes'] = $this->subcounty_model->subcounty_sites($year,$month,$subcounty,$to_year,$to_month);
		
    	$link = $year . '/' . $month . '/' . $subcounty . '/' . $to_year . '/' . $to_month;
		$data['link'] =  base_url('charts/subcounties/download_subcounty_sites/' . $link);
		$data['div_name'] = 'subcounty_sites_datatable';

    	$this->load->view('partner_site__view',$data);
	}

	function download_subcounty_sites($year=NULL,$month=NULL,$subcounty=NULL,$to_year=NULL,$to_month=NULL)
	{
		$this->subcounty_model->download_subcounty_sites($year,$month,$subcounty,$to_year,$to_month);
	}

	function get_patients($year=NULL,$month=NULL,$subcounty=NULL,$to_year=NULL,$to_month=NULL)
	{		
		$data['trends'] = $this->subcounty_model->get_patients($year,$month,$subcounty,$to_year,$to_month);
		$data['div_name'] = "unique_patients";

		$this->load->view('longitudinal_view',$data);
	}

	function get_current_suppresion($year=NULL,$month=NULL,$subcounty=NULL,$to_year=NULL,$to_month=NULL)
	{		
		$data['outcomes'] = $this->subcounty_model->get_current_suppresion($year,$month,$subcounty,$to_year,$to_month);
		$data['div_name'] = "current_suppression_pie";
		$this->load->view('pie_chart_view',$data);
	}

	function subcounty_outcome_table($year=NULL,$month=NULL,$to_year=NULL,$to_month=NULL)
		{
			$data['subcountyListing'] = TRUE;
			$data['outcomes']= $this->county_model->county_outcome_table($year,$month,$to_year,$to_month,$data);
					

			$link = $year . '/' . $month . '/' . $to_year . '/' . $to_month;

			// $data['link'] =  base_url('charts/county/download_county_table/' . $link);

	    	$this->load->view('counties_outcome_table_view', $data);
		}
}
?>