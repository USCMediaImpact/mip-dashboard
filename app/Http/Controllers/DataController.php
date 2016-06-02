<?php

namespace App\Http\Controllers;

use DB;
use Cache;
use Google_Client;
use Google_Service_Bigquery;
use Google_Service_Bigquery_QueryRequest;
use App\User;
use App\Http\Controllers\Controller;

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

    public function showQuality(){
        return view('data.quality');
    }
}