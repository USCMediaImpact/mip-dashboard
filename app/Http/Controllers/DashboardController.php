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
        return redirect('/data/users');
    }
}