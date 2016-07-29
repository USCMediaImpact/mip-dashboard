<?php

namespace App\Http\Controllers;

use DB;
use Cache;
use Illuminate\Http\Request;

use Google_Client;
use Google_Service_Bigquery;
use Google_Service_Bigquery_QueryRequest;

class DashboardController extends AuthenticatedBaseController{
    
    public function show(Request $request){
        $group = array_key_exists($request['group'], self::$groupDisplay) ? $request['group'] : 'weekly';
        $max_date = date_parse($request['max_date'] ?: date('Y-m-d', time()));
        $min_date = date_parse($request['min_date'] ?: date('Y-m-1', time()));

        $client_id = $request['client']['id'];
        $client_code = $request['client']['code'];

        $date_range_min = '2014-06-27';
        $date_range_max = '2016-06-27';

        return view('dashboard.users', [
            'min_date' => mktime(0, 0, 0, $min_date['month'], $min_date['day'], $min_date['year']),
            'max_date' => mktime(0, 0, 0, $max_date['month'], $max_date['day'], $max_date['year']),
            'date_range_min' => $date_range_min,
            'date_range_max' => $date_range_max
        ]);
    }
}