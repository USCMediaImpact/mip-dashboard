<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
	return redirect('mysql');
});

Route::get('mysql', function () {
	return redirect('mysql/table');
});

Route::get('/mysql/{type}', function ($type) {
    $pv = DB::select('select date, pv from page_views');
    if($type == 'chart'){
    	$chartPv = array_map(function($row) {
		    return array('name' => $row->date, 'y' => $row->pv);
		}, $pv);
		$chartCategories = array_map(function($row){
			return $row->date;
		}, $pv);
    	return view('chart', [
	        'pv' => $chartPv,
	        'category' => $chartCategories,
	        'type' => 'chart'
	    ]);
    }
    return view('table', [
        'pv' => $pv,
        'type' => 'table'
    ]);
});

Route::get('/bigquery', function () {
	$projectId = 'tonal-studio-119521';
    $queryString = "SELECT ". 
		"  date, ". 
		"  SUM(IF(Hit_Type='PAGE', 1, NULL)) AS Pageviews ". 
		"FROM ( ". 
		"  SELECT ". 
		"    date, ". 
		"    hits.type AS Hit_Type ". 
		"  FROM ( TABLE_DATE_RANGE([116430105.ga_sessions_tz_], TIMESTAMP('2016-04-01'), TIMESTAMP('2016-05-01')  ) ) ) ". 
		"GROUP BY ". 
		"  date ". 
		"ORDER BY ". 
		"  date";

	$client = new Google_Client();
	$client->useApplicationDefaultCredentials();
	$client->addScope(Google_Service_Bigquery::BIGQUERY);
    $bigquery = new Google_Service_Bigquery($client);

    $request = new Google_Service_Bigquery_QueryRequest();
    $request->setQuery($queryString);
    $response = $bigquery->jobs->query($projectId, $request);
    $rows = $response->getRows() ? $response->getRows() : array();
	$pv = array();
    foreach ($rows as $row) {
    	$pv[] = array('date' => $row['f'][0]['v'], 'pv' => $row['f'][1]['v'])
	}
	
	if($type == 'chart'){
    	$chartPv = array_map(function($row) {
		    return array('name' => $row->date, 'y' => $row->pv);
		}, $pv);
		$chartCategories = array_map(function($row){
			return $row->date;
		}, $pv);
    	return view('chart', [
	        'pv' => $chartPv,
	        'category' => $chartCategories,
	        'type' => 'chart'
	    ]);
    }
    return view('table', [
        'pv' => $pv,
        'type' => 'table'
    ]);
});
