<?php

namespace App\Http\Controllers;

use DB;
use Cache;
use Google_Client;
use Google_Service_Bigquery;
use Google_Service_Bigquery_QueryRequest;
use App\User;
use App\Http\Controllers\Controller;

class ReportsController extends AuthenticatedBaseController{

    public function showContent(){
        return view('reports.content');
    }

    public function showUsers(){
        return view('reports.users');
    }
	
    public function showDonations(){
        return view('reports.donations');
    }
}