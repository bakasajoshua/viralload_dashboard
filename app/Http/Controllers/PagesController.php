<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Lookup;
use App\Mail\SupportContact;
use App\Facility;
use DB;

class PagesController extends Controller
{

	public function summary()
	{
		$data = Lookup::get_dropdown('County');
		return view('base.summary', $data);
	}

	public function current()
	{
		$data = Lookup::get_dropdown('County');
		return view('base.current', $data);
	}

	public function county()
	{
		$data = Lookup::get_dropdown('County');
		return view('base.county', $data);
	}

	public function subcounty()
	{
		$data = Lookup::get_dropdown('Sub County');
		return view('base.subcounty', $data);
	}

	public function partner()
	{
		$data = Lookup::get_dropdown('Partner');
		return view('base.partner', $data);
	}

	public function facility()
	{
		$data = Lookup::get_dropdown('Facility');
		return view('base.facility', $data);
	}

	public function lab()
	{
		$data = Lookup::get_dropdown('Lab');
		return view('base.lab', $data);
	}

	public function poc()
	{
		$data = Lookup::get_dropdown('County');
		return view('base.poc', $data);
	}

	public function live()
	{
		return view('base.live', []);
	}

	public function covid()
	{
		return view('base.covid', []);
	}

	public function regimen()
	{
		$data = Lookup::get_dropdown('Regimen');
		return view('base.regimen', $data);
	}

	public function age()
	{
		$data = Lookup::get_dropdown('Age Category');
		return view('base.age', $data);
	}
	
	public function onepager()
	{
		$data = [
			'county' => Lookup::get_dropdown('County'),
			'subcounty' => Lookup::get_dropdown('Sub County'),
			'partner' => Lookup::get_dropdown('Partner'),
			'facility' => Lookup::get_dropdown('Facility')
		];
		return view('base.one-pager', $data);
	}

	public function contactus(Request $request, $email = null)
	{
		if (isset($email)) {
			if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false)
			  return response()->json(true);
			else 
			  return response()->json(false);
		}

		if ($request->method() == "POST") {
			Mail::to('nascop3d@gmail.com')
			    ->bcc(['tngugi@clintonhealthaccess.org','baksajoshua09@gmail.com','joelkith@gmail.com'])
			    ->send(new SupportContact($request->input('cname'), $request->input('cemail'),
			    						$request->input('csubject'), $request->input('cmessage')));
		}
		return view('base.contactus');
	}

	public function vlapi(Request $request)
	{
		if (!$request->has('mfl'))
			return response()->json(['error' => 'Bad Request: MFL Code required. Please provide one.'], 400);
		
		$data = Facility::where('facilitycode', $request->input('mfl'))->get();
		if ($data->isEmpty())
			return response()->json(['error' => 'MFL Code provided is invalid please confirm and try again.'], 400);

		$facility = $data->first();

		$data = DB::connection('national')->table('viralsample_complete_view')
					->select('viralsample_complete_view.id as ID', 'patient as Patient', 'facilitycode as MFLCode', 'datecollected', 'datetested as DateTested', 'result as Result', 'justification_name as Justification')
					->join('facilitys', 'facilitys.ID', '=', 'viralsample_complete_view.facility_id')
					->where('viralsample_complete_view.facility_id', $facility->id)
					->where('viralsample_complete_view.repeatt', 0)->where('viralsample_complete_view.flag', 1)
					->orderBy('viralsample_complete_view.datetested', 'desc')
					->get()->toArray();
		$data_item = [];
		foreach($data as $item){
               		 $data_item[] = ['post' => $item];
	        }
	

		return response()->json(['posts' => $data_item], 200);
	}
}
