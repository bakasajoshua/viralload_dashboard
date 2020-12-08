<?php
defined('BASEPATH') or exit('No direct script access allowed!');
/**
* 
*/
class Partner_summaries extends MY_Controller
{
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('partner_summaries_model');
	}

	

	function partner_counties_outcomes($year=NULL,$month=NULL,$partner=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['trends'] = $this->partner_summaries_model->partner_counties_outcomes($year,$month,$partner,$to_year,$to_month);
		$data['div_name'] = "partner_county_outcomes";		

		$this->load->view('trends_outcomes_view', $data);
	}

	function partner_counties_table($year=NULL,$month=NULL,$partner=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes'] = $this->partner_summaries_model->partner_counties_table($year,$month,$partner,$to_year,$to_month);

		$link = $year . '/' . $month . '/' . $partner . '/' . $to_year . '/' . $to_month;
		$link2 = $partner;
		//$data['link'] = anchor('charts/sites/download_partner_sites/' . $link, 'Download List');

		// $data['link'] = "<a href='" . base_url('charts/partner_summaries/download_partner_counties/' . $link) . "'><button class='btn btn-primary' style='background-color: #009688;color: white;'>Export to Excel</button></a>";
		$data['link'] = base_url('charts/partner_summaries/download_partner_counties/' . $link);
		$data['link2'] = "<a href='" . base_url('charts/sites/download_partner_supported_sites/' . $link2) . "'><button class='btn btn-primary' style='background-color: #009688;color: white;'>DOWNLOAD LIST OF ALL SUPPORTED SITES</button></a>";
		$data['div_name'] = 'partner_counties_datatable';

    	$this->load->view('partner__site__view',$data);
	}

	function download_partner_counties($year=NULL,$month=NULL,$partner=NULL,$to_year=NULL,$to_month=NULL)
	{
		$this->partner_summaries_model->download_partner_counties($year,$month,$partner,$to_year,$to_month);
	}
}
?>