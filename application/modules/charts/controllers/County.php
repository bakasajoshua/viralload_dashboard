<?php
defined("BASEPATH") or exit();
/**
* 
*/
class County extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('county_model');
	}

	function county_table($year=NULL,$month=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes']= $this->county_model->county_table($year,$month,$to_year,$to_month);
		$data['sites'] = TRUE;		

		$link = $year . '/' . $month . '/' . $to_year . '/' . $to_month;

		$data['link'] =  base_url('charts/county/download_county_table/' . $link);
		$data['table_div'] = "first_table";

    	$this->load->view('counties_table_view',$data);
	}

	function download_county_table($year=NULL,$month=NULL,$to_year=NULL,$to_month=NULL)
	{
		$this->county_model->download_county_table($year,$month,$to_year,$to_month);
		
	}

	function county_subcounties($year=NULL,$month=NULL,$county=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes'] = $this->county_model->county_subcounties($year,$month,$county,$to_year,$to_month);
		$data['sites'] = TRUE;

		$link = $year . '/' . $month . '/' . $county . '/' . $to_year . '/' . $to_month;

		$data['link'] =  base_url('charts/county/download_subcounty_table/' . $link);
		$data['table_div'] = "second_table";

    	$this->load->view('counties_table_view',$data);
	}

	function download_subcounty_table($year=NULL,$month=NULL,$county=NULL,$to_year=NULL,$to_month=NULL)
	{
		$this->county_model->download_subcounty_table($year,$month,$county,$to_year,$to_month);
		
	}

	function county_partners($year=NULL,$month=NULL,$county=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes'] = $this->county_model->county_partners($year,$month,$county,$to_year,$to_month);

		$link = $year . '/' . $month . '/' . $county . '/' . $to_year . '/' . $to_month;

		$data['link'] =  base_url('charts/county/download_partner_county_table/' . $link);
		$data['table_div'] = "county_partners_datatable";

    	$this->load->view('counties_table_view',$data);
	}

	function download_partner_county_table($year=NULL,$month=NULL,$county=NULL,$to_year=NULL,$to_month=NULL)
	{
		$this->county_model->download_partners_county_table($year,$month,$county,$to_year,$to_month);
		
	}

	function subcounty_outcomes($year=NULL,$month=NULL,$county=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['trends'] = $this->county_model->subcounty_outcomes($year,$month,$county,$to_year,$to_month);
		$data['div_name'] = "county_subcounties_outcomes";		

		$this->load->view('trends_outcomes_view', $data);
	}

	function subcounty_outcomes_positivity($year=NULL,$month=NULL,$county=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes'] = $this->county_model->subcounty_outcomes($year,$month,$county,$to_year,$to_month);
		$data['type'] = 'percent';
		$data['yAxisText'] = 'Non-suppression';
		$data['div'] = 'sub_counties_positivity_chart';
		
		$this->load->view('county_outcomes_view',$data);
	}

	function county_facilities($year=NULL,$month=NULL,$county=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes'] = $this->county_model->county_facilities($year,$month,$county,$to_year,$to_month);

		$link = $year . '/' . $month . '/' . $county . '/' . $to_year . '/' . $to_month;
		$data['subcountyListing'] = true;
		$data['link'] =  '';
		$data['table_div'] = "county_facilities_table";

    	$this->load->view('counties_table_view',$data);
	}

		function county_outcome_table($year=NULL,$month=NULL,$to_year=NULL,$to_month=NULL)
		{
			$data['sites'] = TRUE;
			$data['outcomes']= $this->county_model->county_outcome_table($year,$month,$to_year,$to_month,$data);
					

			$link = $year . '/' . $month . '/' . $to_year . '/' . $to_month;

			// $data['link'] =  base_url('charts/county/download_county_table/' . $link);

	    	$this->load->view('counties_outcome_table_view', $data);
		}
}
?>