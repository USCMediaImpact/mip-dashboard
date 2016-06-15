<?php

namespace App\Http\Controllers;

use DB;
use Cache;
use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Bigquery;
use Google_Service_Bigquery_QueryRequest;
use App\Models\User;

class DataController extends AuthenticatedBaseController{

    public function showContent(){

        return view('data.content');
    }

    public function showUsers(){
        return view('data.users');
    }
	
    public function showDonations(){
        return view('data.donations');
    }

    public function showQuality(Request $request){
        $group = in_array($request['group'], ['daily', 'weekly', 'monthly']) ? $request['group'] : 'daily';
        $max_date = date_parse($request['max_date'] ?: date('Y-m-d', time()));
        $min_date = date_parse($request['min_date'] ?: date('Y-m-1', time()));

        $report = DB::table('data_quanlity_' . $group)
            ->select('date', 'events', 'ga_users', 'mip_users', 'user_variance', 'identified_emailsubscribers', 'known_emailsubscribers', 'total_database_emails', 'identified_newemailsubscribers', 'email_newsletter_clicks', 'eloqua_email_newsletter_clicks', 'email_newsletter_clicks_variance', 'identified_donors', 'known_donors', 'eloqua_known_donors', 'donors_variance', 'total_known_donors', 'total_known__unique_email')
//            ->where('date', '<=', $max_date)
//            ->where('date', '>=', $min_date)
            ->get();

        $report = array_map(function($row){
            return get_object_vars($row);
        }, $report);

        return view('data.quality', [
            'report' => $report,
            'min_date' => $min_date,
            'max_date' => $max_date,
            'group' => $group
        ]);
    }
}