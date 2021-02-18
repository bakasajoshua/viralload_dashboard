<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
// use DB;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');


Artisan::command('test', function () {
	// $encoded_string = "";
	// $first_decode = base64_decode($encoded_string);
	// $uncompressed = gzuncompress($first_decode);
	// print_r(($uncompressed));
	// $retrived = eval(base64_decode(gzuncompress()));

	// print_r(gettype($retrived));
	// 
})->describe('Testing all sort of things');
