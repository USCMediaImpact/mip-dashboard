<?php

namespace App\Http\Controllers;

use DB;
use Cache;
use Google_Client;
use Google_Service_Bigquery;
use Google_Service_Bigquery_QueryRequest;
use App\Models\User;

class MetricsController extends AuthenticatedBaseController{

    public function showContent(){
        return view('metrics.content');
    }

    public function showUsers(){
        return view('metrics.users');
    }
	
    public function showDonations(){
        return view('metrics.donations');
    }
}