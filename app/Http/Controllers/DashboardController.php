<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Http\Controllers\Controller;

class DashboardController extends Controller{
	/**
	 * This controller need auth
	 */
	public function __construct()
	{
	    $this->middleware('auth');
	}
	/**
	 * demo for load data from mysql
	 * @return [type] [description]
	 */
	public function showDataFromMySql()
    {
    	$report = DB::select('select date, pv from page_views');
    	$report = array_map(function($row){
			return array('date' => date('Ymd', strtotime($row->date)), 'value' => $row->pv);
    	}, $report);
        return view('dashboard', ['report' => $report]);
    }

    public function showDataFromBigQuery(){
    	$projectId = 'tonal-studio-119521';
	    $queryString =
			'SELECT ' .
			'  date, ' .
			'  SUM(IF(Hit_Type="PAGE", 1, NULL)) AS Pageviews '.
			'FROM ( '.
			'  SELECT '.
			'    date, '.
			'    hits.type AS Hit_Type '.
			'  FROM ( TABLE_DATE_RANGE([116430105.ga_sessions_tz_], TIMESTAMP("2016-04-01"), TIMESTAMP("2016-05-01")  ) ) ) '.
			'GROUP BY '.
			'  date '.
			'ORDER BY '.
			'  date';

		$client = new Google_Client();
		$client->useApplicationDefaultCredentials();
		$client->addScope(Google_Service_Bigquery::BIGQUERY);
	    $bigQuery = new Google_Service_Bigquery($client);

	    $request = new Google_Service_Bigquery_QueryRequest();
	    $request->setQuery($queryString);
	    $response = $bigQuery->jobs->query($projectId, $request);
	    $rows = $response->getRows() ?: array();
		$report = array();
	    foreach ($rows as $row) {
	    	$report[] = array('date' => $row['f']['0']['v'], 'value' => $row['f']['1']['v']);
	    }
	    return view('dashboard', ['report' => $report]);
	}
}