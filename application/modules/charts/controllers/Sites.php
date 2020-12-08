<?php 
defined('BASEPATH') or exit('No direct script access allowed!');

/**
* 
*/
class Sites extends MY_Controller
{
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('sites_model');
	}

	function site_outcomes($year=NULL,$month=NULL,$partner=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['trends'] = $this->sites_model->sites_outcomes($year,$month,$partner,$county,$to_year,$to_month,$data);
		$data['div_name'] = "site_sites_outcomes";		

		$this->load->view('trends_outcomes_view', $data);
	}

	function partner_sites($year=NULL,$month=NULL,$partner=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes'] = $this->sites_model->partner_sites_outcomes($year,$month,$partner,$to_year,$to_month);

		$link = $year . '/' . $month . '/' . $partner . '/' . $to_year . '/' . $to_month;
		$data['link'] =  base_url('charts/sites/download_partner_sites/' . $link);
		$data['subcounty'] = true;
		$data['div_name'] = 'partner_sites_datatable';

    	$this->load->view('partner_site__view',$data);
	}

	function download_partner_sites($year=NULL,$month=NULL,$partner=NULL,$to_year=NULL,$to_month=NULL)
	{
		$this->sites_model->partner_sites_outcomes_download($year,$month,$partner,$to_year,$to_month);
	}


	function site_trends($year=null,$month=null,$site=null,$to_year=NULL,$to_month=NULL)
	{
		$data['trends'] = $this->sites_model->sites_trends($year,$month,$site,$to_year,$to_month);

		$this->load->view('labs_testing_trends',$data);
	}

	function site_outcomes_chart($year=null,$month=null,$site=null,$to_year=NULL,$to_month=NULL)
	{
		$data['trends'] = $this->sites_model->site_outcomes_chart($year,$month,$site,$to_year,$to_month);
		
		$this->load->view('labs_sample_types',$data);
	}

	function site_Vlotcomes($year=null,$month=null,$site=null,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes'] = $this->sites_model->sites_vloutcomes($year,$month,$site,$to_year,$to_month);

		$this->load->view('vl_outcomes_view',$data);
	}

	function site_agegroups($year=null,$month=null,$site=null,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes'] = $this->sites_model->sites_age($year,$month,$site,$to_year,$to_month);

		$this->load->view('agegroup_view',$data);
	}

	function site_gender($year=null,$month=null,$site=null,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes'] = $this->sites_model->sites_gender($year,$month,$site,$to_year,$to_month);

		$this->load->view('gender_view',$data);
	}

	function site_justification($year=null,$month=null,$site=null,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes'] = $this->sites_model->justification($year,$month,$site,$to_year,$to_month);

    	$this->load->view('justification_view',$data);
	}

	function justificationbreakdown($year=null,$month=null,$site=null,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes'] = $this->sites_model->justification_breakdown($year,$month,$site,$to_year,$to_month);
		
		$this->load->view('justification_breakdown_view',$data);
	}

	function get_patients($year=null,$month=null,$site=null,$to_year=NULL,$to_month=NULL){
		$data['trends'] = $this->sites_model->get_patients($site,$year,$month,$to_year,$to_month);
		$data['div_name'] = "unique_patients";

		$this->load->view('longitudinal_view',$data);
	}

	function get_current_suppresion($year=null,$month=null,$site=null,$to_year=NULL,$to_month=NULL){
		$data['outcomes'] = $this->sites_model->get_current_suppresion($site,$year,$month,$to_year,$to_month);
		$data['div_name'] = "current_suppression_pie";
		$this->load->view('pie_chart_view',$data);
	}

	function current_suppression($site=NULL)
	{
		$data['outcomes'] = $this->sites_model->current_suppression($site);
		$data['div_name'] = "suppression_pie_chart";

    	$this->load->view('pie_chart_view',$data);
	}

	function get_patients_outcomes($year=null,$month=null,$site=null,$to_year=NULL,$to_month=NULL){
		$data = $this->sites_model->get_patients_outcomes($site,$year,$month,$to_year,$to_month);
		$this->load->view('patients_outcomes_graph',$data);
	}

	function get_patients_graph($year=null,$month=null,$site=null,$to_year=NULL,$to_month=NULL){
		$data = $this->sites_model->get_patients_graph($site,$year,$month,$to_year,$to_month);
		$this->load->view('patients_graph',$data);
	}

	function site_patients($site=null,$year=null,$month=null,$to_year=NULL,$to_month=NULL)
	{
		$data = $this->sites_model->site_patients($site,$year,$month,$to_year,$to_month);

		echo "<pre>";print_r($data);die();



		$this->load->view('sites_gender_view',$data);
	}

	
	
}
?>