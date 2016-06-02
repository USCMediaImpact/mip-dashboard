<?php

namespace App\Http\Controllers;

use DB;
use Cache;
use Google_Client;
use Google_Service_Bigquery;
use Google_Service_Bigquery_QueryRequest;
use App\User;
use App\Http\Controllers\Controller;

class DataController extends Controller{
	/**
	 * This controller need auth
	 */
	public function __construct()
	{
	    $this->middleware('auth');
	}

    public function content(){
        return view('reports.content');
    }

    public function users(){
        return view('reports.users');
    }
	
    public function donations(){
        return view('reports.donations');
    }

    public function quality(){
        return view('reports.quality');
    }
}