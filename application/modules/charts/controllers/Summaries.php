<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Summaries extends MY_Controller {

	function __construct()
	{
		parent:: __construct();
		$this->load->model('summaries_model');
		$this->load->model('sites_model');
		$this->load->model('currentSuppressionModel');
	}

	function turnaroundtime($year=NULL,$month=NULL,$county=NULL,$to_year=NULL,$to_month=NULL,$nat=NULL)
	{
		$data['outcomes'] = $this->summaries_model->turnaroundtime($year,$month,$county,$to_year,$to_month,$nat);

		$this->load->view('turnaroundtime_view',$data);
	}

	function vl_coverage($year=null,$month=null,$to_year=null,$to_month=null,$type=null,$id=null)
	{
		$data['outcomes'] = $this->summaries_model->vl_coverage($year,$month,$to_year,$to_month,$type,$id);

		$this->load->view('vl_coverage_view',$data);
	}
	
	function county_outcomes($year=NULL,$month=NULL,$pfil=NULL,$partner=NULL,$county=NULL,$to_year=NULL,$to_month=NULL)
	{
		// echo "die";die();
		$data['trends'] = $this->summaries_model->county_outcomes($year,$month,$pfil,$partner,$county,$to_year,$to_month);
		$data['div_name'] = "summary_county_outcomes";		
		
		$this->load->view('trends_outcomes_view', $data);
	}

	function vl_outcomes($year=NULL,$month=NULL,$county=NULL,$partner=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes'] = $this->summaries_model->vl_outcomes($year,$month,$county,$partner,$to_year,$to_month);

    	$this->load->view('vl_outcomes_view',$data);
	}
	

	function justification($year=NULL,$month=NULL,$county=NULL,$partner=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes'] = $this->summaries_model->justification($year,$month,$county,$partner,$to_year,$to_month);

    	$this->load->view('justification_view',$data);
	}

	function justificationbreakdown($year=NULL,$month=NULL,$county=NULL,$partner=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes'] = $this->summaries_model->justification_breakdown($year,$month,$county,$partner,$to_year,$to_month);
		
		$this->load->view('justification_breakdown_view',$data);
	}

	function age($year=NULL,$month=NULL,$county=NULL,$partner=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes'] = $this->summaries_model->age($year,$month,$county,$partner,$to_year,$to_month);
		
    	$this->load->view('agegroup_view',$data);
	}

	function agebreakdown($year=NULL,$month=NULL,$county=NULL,$partner=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes'] = $this->summaries_model->age_breakdown($year,$month,$county,$partner,$to_year,$to_month);
		
		$this->load->view('agegroupBreakdown',$data);
	}

	function gender($year=NULL,$month=NULL,$county=NULL,$partner=NULL,$to_year=NULL,$to_month=NULL)
	{
		$data['outcomes'] = $this->summaries_model->gender($year,$month,$county,$partner,$to_year,$to_month);

    	$this->load->view('gender_view',$data);
	}

	function sample_types($year=NULL,$month=NULL,$to_year=NULL,$to_month=NULL,$type=NULL,$id=NULL,$all=NULL)
	{
		if ($type == 7)
			$id = $this->split_ages($id);
		
		$data['outcomes'] = $this->summaries_model->sample_types($year,$month,$to_year,$to_month,$type,$id,$all);
		// $link = $year . '/' . $county . '/' . $partner;

		// $data['link'] = base_url('charts/summaries/download_sampletypes/' . $link);
		$data['link'] = "#";

    	$this->load->view('national_sample_types',$data);
	}

	function download_sampletypes($year=NULL,$county=NULL,$partner=NULL)
	{
		$this->summaries_model->download_sampletypes($year,$county,$partner);
	}

	function get_patients($year=NULL,$month=NULL,$county=NULL,$partner=NULL,$to_year=NULL,$to_month=NULL)
	{		
		$data['trends'] = $this->summaries_model->get_patients($year,$month,$county,$partner,$to_year,$to_month);
		$data['div_name'] = "unique_patients";

		$this->load->view('longitudinal_view',$data);
	}

	function get_current_suppresion($year=NULL,$month=NULL,$county=NULL,$partner=NULL,$to_year=NULL,$to_month=NULL)
	{		
		$data['outcomes'] = $this->summaries_model->get_current_suppresion($year,$month,$county,$partner,$to_year,$to_month);
		$data['div_name'] = "current_suppression_pie";
		$this->load->view('pie_chart_view',$data);
	}

	function current_suppression($county=NULL,$partner=NULL,$annual=NULL)
	{
		$data['outcomes'] = $this->summaries_model->current_suppression($county,$partner,$annual);
		$data['div_name'] = "suppression_pie";
    	$this->load->view('pie_chart_view',$data);
	}

	function current_age($type, $param_type=1, $param=NULL,$annual=NULL){
		$data['outcomes'] = $this->summaries_model->current_age_chart($type,$param_type,$param,$annual);		
    	$this->load->view('agegroup_view',$data);
	}

	function current_gender($type, $param_type=1, $param=NULL,$annual=NULL){
		$data['outcomes'] = $this->summaries_model->current_gender_chart($type,$param_type,$param,$annual);
    	$this->load->view('gender_view',$data);
	}

	/** 
	**Current listings sorted by county
	*/
	function county_listing($type, $param_type=1, $param=NULL,$annual=NULL){
		$data['cont'] = $this->summaries_model->suppression_listings($type,$param_type,$param,$annual);
		$data['cont']['div'] = 'county_sup_listings';
		$data['cont']['title'] = 'County Listing';
		$data['cont']['table_div'] = 'county_sup_listings_table';

		$this->load->view('current_suppression_listing',$data);
	}

	function subcounty_listing($type, $param_type=1, $param=NULL,$annual=NULL){
		$data['cont'] = $this->summaries_model->suppression_listings($type,$param_type,$param,$annual);
		$data['cont']['div'] = 'subcounty_sup_listings';
		$data['cont']['title'] = 'Sub-County Listing';
		$data['cont']['table_div'] = 'subcounty_sup_listings_table';

		$this->load->view('current_suppression_listing',$data);
	}

	function partner_listing($type, $param_type=1, $param=NULL,$annual=NULL){
		$data['cont'] = $this->summaries_model->suppression_listings($type,$param_type,$param,$annual);
		$data['cont']['div'] = 'partner_sup_listings';
		$data['cont']['title'] = 'Partner Listing';
		$data['cont']['table_div'] = 'partner_sup_listings_table';

		$this->load->view('current_suppression_listing',$data);
	}

	function site_listing($type, $param_type=1, $param=NULL,$annual=NULL){
		$data['cont'] = $this->summaries_model->suppression_listings($type,$param_type,$param,$annual);
		$data['cont']['div'] = 'site_sup_listings';
		$data['cont']['title'] = 'Facility Listing';
		$data['cont']['table_div'] = 'site_sup_listings_table';

		$this->load->view('current_suppression_listing',$data);
	}


	/** 
	**Current age listings sorted by county (suppressed)
	*/
	function county_listing_age($type, $param_type=1, $param=NULL,$annual=NULL){
		$data['cont'] = $this->summaries_model->suppression_age_listings(1, $type,$param_type,$param,$annual);
		$data['cont']['div'] = 'county_sup_listings_age';
		$data['cont']['title'] = 'County Listing Age';
		$data['cont']['table_div'] = 'county_sup_listings_table_age';

		$this->load->view('current_age_suppression_listing_sup',$data);
	}

	function subcounty_listing_age($type, $param_type=1, $param=NULL,$annual=NULL){
		$data['cont'] = $this->summaries_model->suppression_age_listings(1, $type,$param_type,$param,$annual);
		$data['cont']['div'] = 'subcounty_sup_listings_age';
		$data['cont']['title'] = 'Sub-County Listing Age';
		$data['cont']['table_div'] = 'subcounty_sup_listings_table_age';

		$this->load->view('current_age_suppression_listing_sup',$data);
	}

	function partner_listing_age($type, $param_type=1, $param=NULL,$annual=NULL){
		$data['cont'] = $this->summaries_model->suppression_age_listings(1, $type,$param_type,$param,$annual);
		$data['cont']['div'] = 'partner_sup_listings_age';
		$data['cont']['title'] = 'Partner Listing Age';
		$data['cont']['table_div'] = 'partner_sup_listings_table_age';

		$this->load->view('current_age_suppression_listing_sup',$data);
	}

	function site_listing_age($type, $param_type=1, $param=NULL,$annual=NULL){
		$data['cont'] = $this->summaries_model->suppression_age_listings(1, $type,$param_type,$param,$annual);
		$data['cont']['div'] = 'site_sup_listings_age';
		$data['cont']['title'] = 'Facility Listing Age';
		$data['cont']['table_div'] = 'site_sup_listings_table_age';

		$this->load->view('current_age_suppression_listing_sup',$data);
	}

	/** 
	**Current age listings sorted by county (non suppressed)
	*/

	function county_listing_age_n($type, $param_type=1, $param=NULL,$annual=NULL){
		$data['cont'] = $this->summaries_model->suppression_age_listings(0, $type,$param_type,$param,$annual);
		$data['cont']['div'] = 'county_sup_listings_age_n';
		$data['cont']['title'] = 'County Listing Age Non Suppressed';
		$data['cont']['table_div'] = 'county_sup_listings_table_age_n';

		$this->load->view('current_age_suppression_listing',$data);
	}

	function subcounty_listing_age_n($type, $param_type=1, $param=NULL,$annual=NULL){
		$data['cont'] = $this->summaries_model->suppression_age_listings(0, $type,$param_type,$param,$annual);
		$data['cont']['div'] = 'subcounty_sup_listings_age_n';
		$data['cont']['title'] = 'Sub-County Listing Age Non Suppressed';
		$data['cont']['table_div'] = 'subcounty_sup_listings_table_age_n';

		$this->load->view('current_age_suppression_listing',$data);
	}

	function partner_listing_age_n($type, $param_type=1, $param=NULL,$annual=NULL){
		$data['cont'] = $this->summaries_model->suppression_age_listings(0, $type,$param_type,$param,$annual);
		$data['cont']['div'] = 'partner_sup_listings_age_n';
		$data['cont']['title'] = 'Partner Listing Age Non Suppressed';
		$data['cont']['table_div'] = 'partner_sup_listings_table_age_n';

		$this->load->view('current_age_suppression_listing',$data);
	}

	function site_listing_age_n($type, $param_type=1, $param=NULL,$annual=NULL){
		$data['cont'] = $this->summaries_model->suppression_age_listings(0, $type,$param_type,$param,$annual);
		$data['cont']['div'] = 'site_sup_listings_age_n';
		$data['cont']['title'] = 'Facility Listing Age Non Suppressed';
		$data['cont']['table_div'] = 'site_sup_listings_table_age_n';

		$this->load->view('current_age_suppression_listing',$data);
	}

	/** 
	**Current gender listings sorted by county
	*/
	function county_listing_gender($type, $param_type=1, $param=NULL,$annual=NULL){
		$data['cont'] = $this->summaries_model->suppression_gender_listings($type,$param_type,$param,$annual);
		$data['cont']['div'] = 'county_sup_listings_gender';
		$data['cont']['title'] = 'County Listing Gender';
		$data['cont']['table_div'] = 'county_sup_listings_table_gender';

		$this->load->view('current_gender_suppression_listing',$data);
	}

	function subcounty_listing_gender($type, $param_type=1, $param=NULL,$annual=NULL){
		$data['cont'] = $this->summaries_model->suppression_gender_listings($type,$param_type,$param,$annual);
		$data['cont']['div'] = 'subcounty_sup_listings_gender';
		$data['cont']['title'] = 'Sub-County Listing Gender';
		$data['cont']['table_div'] = 'subcounty_sup_listings_table_gender';

		$this->load->view('current_gender_suppression_listing',$data);
	}

	function partner_listing_gender($type, $param_type=1, $param=NULL,$annual=NULL){
		$data['cont'] = $this->summaries_model->suppression_gender_listings($type,$param_type,$param,$annual);
		$data['cont']['div'] = 'partner_sup_listings_gender';
		$data['cont']['title'] = 'Partner Listing Gender';
		$data['cont']['table_div'] = 'partner_sup_listings_table_gender';

		$this->load->view('current_gender_suppression_listing',$data);
	}

	function site_listing_gender($type, $param_type=1, $param=NULL,$annual=NULL){
		$data['cont'] = $this->summaries_model->suppression_gender_listings($type,$param_type,$param,$annual);
		$data['cont']['div'] = 'site_sup_listings_gender';
		$data['cont']['title'] = 'Facility Listing Gender';
		$data['cont']['table_div'] = 'site_sup_listings_table_gender';

		$this->load->view('current_gender_suppression_listing',$data);
	}

	

	function display_date()
	{
		echo "(".$this->session->userdata('filter_year')." ".$this->summaries_model->resolve_month($this->session->userdata('filter_month')).")";
	}

	function display_range()
	{
		echo "(".($this->session->userdata('filter_year')-1)." - ".$this->session->userdata('filter_year').")";
	}

	function county_partner_outcomes($year=null,$month=null,$partner=null,$county=null,$to_year=null,$to_month=null)
	{
		$data['partners'] = true;
		$data['county'] = $county;
		$data['trends'] = $this->sites_model->sites_outcomes($year,$month,$partner,$county,$to_year,$to_month,$data);
		$data['div_name'] = "county_partner_outcomes";
		$this->load->view('trends_outcomes_view', $data);
	}

	function county_partner_table($year=NULL,$month=NULL,$to_year=NULL,$to_month=NULL,$county=null)
	{
		$data['county'] = $county;
		$data['outcomes']= $this->summaries_model->county_partner_table($year,$month,$to_year,$to_month,$county,$data);
		$data['partner'] = TRUE;		

		$link = $year . '/' . $month . '/' . $to_year . '/' . $to_month;

		$data['link'] =  base_url('charts/county/download_county_table/' . $link);
		$data['table_div'] = "county_partner_datatable";

    	$this->load->view('counties_table_view',$data);
	}

	function current_summary_age($type=null,$id=null) {
		$data['outcomes'] = $this->currentSuppressionModel->current_age($type,$id);
		$data['div_name'] = 'current_age_pie';
    	$this->load->view('justification_view',$data);
	}

	function current_summary_age_breakdown($type=null,$id=null) {
		$data = $this->currentSuppressionModel->current_age_breakdown($type,$id);
    	$this->load->view('currentAgeBreakdown',$data);
	}

	function current_summary_suppression_age($type=null,$id=null,$first=TRUE) {
		$data['outcomes'] = $this->currentSuppressionModel->current_suppression_age($type,$id,$first);
		$data['div_name'] = (!$first || $first == 'false') ? 'current_age_suppression_pie_second' : 'current_age_suppression_pie_first';
    	$this->load->view('justification_view',$data);
	}

}
?>