<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('facility/search', 'FilterController@facility')->name('facility.search');

Route::prefix('filter')->name('filter.')->group(function(){
	Route::post('date', 'FilterController@filter_date')->name('date');
	Route::post('any', 'FilterController@filter_any')->name('any');
});

Route::middleware(['clear_session', ])->group(function(){
	Route::get('/', 'PagesController@summary')->name('summary');
	Route::get('county', 'PagesController@county')->name('county');
	Route::get('subcounty', 'PagesController@subcounty')->name('subcounty');
	Route::get('partner', 'PagesController@partner')->name('partner');
	Route::get('facility', 'PagesController@facility')->name('facility');
	Route::get('lab', 'PagesController@lab')->name('lab');
	Route::get('lab/poc', 'PagesController@poc')->name('poc');
	Route::get('live', 'PagesController@live')->name('live');
	Route::get('lab/covid', 'PagesController@covid')->name('covid');

	Route::get('current', 'PagesController@current')->name('current');
	Route::get('regimen', 'PagesController@regimen')->name('regimen');
	Route::get('age', 'PagesController@age')->name('age');
	Route::get('one-pager', 'PagesController@onepager')->name('one-pager');
});


Route::prefix('summary')->name('summary.')->group(function(){
	Route::get('turnaroundtime', 'SummaryController@turnaroundtime')->name('turnaroundtime');
	Route::get('vl_coverage', 'SummaryController@vl_coverage')->name('vl_coverage');
	Route::get('outcomes/{division?}/{second_division?}', 'SummaryController@outcomes')->name('outcomes');
	Route::get('vl_outcomes/{division?}', 'SummaryController@vl_outcomes')->name('vl_outcomes');
	Route::get('justification', 'SummaryController@justification')->name('justification');
	Route::get('age/{division?}', 'SummaryController@age')->name('age');
	Route::get('gender/{division?}', 'SummaryController@gender')->name('gender');
	Route::get('sample_types/{all?}', 'SummaryController@sample_types')->name('sample_types');
	Route::get('get_patients', 'SummaryController@get_patients')->name('get_patients');
	Route::get('get_current_suppresion', 'SummaryController@get_current_suppresion')->name('get_current_suppresion');
	Route::get('current_suppression', 'SummaryController@current_suppression')->name('current_suppression');
	Route::get('current_gender_chart/{type}', 'SummaryController@current_gender_chart')->name('current_gender_chart');
	Route::get('current_age_chart/{type}', 'SummaryController@current_age_chart')->name('current_age_chart');
	Route::get('county_partner_table', 'SummaryController@county_partner_table')->name('county_partner_table');

	Route::get('suppression_listings/{type}', 'SummaryController@suppression_listings')->name('suppression_listings');
	Route::get('suppression_age_listings/{suppressed}/{type}', 'SummaryController@suppression_age_listings')->name('suppression_age_listings');
	Route::get('suppression_gender_listings/{type}', 'SummaryController@suppression_gender_listings')->name('suppression_gender_listings');
});


Route::prefix('county')->name('county.')->group(function(){
	Route::get('division_table/{division?}/{second_division?}', 'CountyController@division_table')->name('division_table');
	Route::get('subcounty_outcomes/{division?}/{ageGroup?}', 'CountyController@subcounty_outcomes')->name('subcounty_outcomes');
	Route::get('county_outcome_table/{subcounty?}', 'CountyController@county_outcome_table')->name('county_outcome_table');

});


Route::prefix('pmtct')->name('pmtct.')->group(function(){
	Route::get('outcomes', 'PmtctController@outcomes')->name('outcomes');
});

Route::prefix('tat')->name('tat.')->group(function(){
	Route::get('outcomes/{type?}', 'TatController@outcomes')->name('outcomes');
	Route::get('details/{type?}', 'TatController@details')->name('details');
});

Route::prefix('trends')->name('trends.')->group(function(){
	Route::get('monthly_trends/{division}', 'TrendController@monthly_trends')->name('monthly_trends');
	Route::get('monthly_sample_types/{division}', 'TrendController@monthly_sample_types')->name('monthly_sample_types');
});

Route::prefix('suppression')->name('suppression.')->group(function(){
	Route::get('breakdowns/{division}/{second_division}', 'SuppressionController@breakdowns')->name('breakdowns');

	Route::get('regimen_age', 'SuppressionController@regimen_age')->name('regimen_age');
	Route::get('regimen_gender', 'SuppressionController@regimen_gender')->name('regimen_gender');

	Route::get('age_gender', 'SuppressionController@age_gender')->name('age_gender');
});

Route::prefix('lab')->name('lab.')->group(function(){
	Route::get('lab_performance_stat', 'LabController@lab_performance_stat')->name('lab_performance_stat');
	Route::get('labs_turnaround', 'LabController@labs_turnaround')->name('labs_turnaround');
	Route::get('labs_outcomes', 'LabController@labs_outcomes')->name('labs_outcomes');
	Route::get('lab_site_rejections', 'LabController@lab_site_rejections')->name('lab_site_rejections');
	Route::get('rejections', 'LabController@rejections')->name('rejections');
	Route::get('test_trends', 'LabController@test_trends')->name('lab_testing_trends');
	Route::get('rejection_trends', 'LabController@rejection_trends')->name('lab_rejection_trends');

	// POC Routes
	Route::get('poc_performance_stat', 'LabController@poc_performance_stat')->name('poc_performance_stat');
	Route::get('poc_performance_details/{facility_id}', 'LabController@poc_performance_details')->name('poc_performance_details');
});


Route::prefix('live')->name('live.')->group(function(){
	Route::get('get_dropdown', 'LiveController@get_dropdown')->name('get_dropdown');
	Route::get('get_data/{type?}/{lab?}', 'LiveController@get_data')->name('get_data');
});

Route::any('contact-us/{email?}', 'PagesController@contactus')->name('contact-us');

Route::get('vlapi.php', 'PagesController@vlapi');
