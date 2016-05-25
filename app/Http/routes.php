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
	$projectID = 'mip-dashboard';
    $query_str = "SELECT ". 
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

    $query = new Google_Service_Bigquery_QueryRequest();
    $query->setQuery($query_str);

    $result = $this->bigqueryService->jobs->query($projectID, $query);
        
        $fields = $result->getSchema()->getFields();
        $rows = $result->getRows();

        dump($fields);
        foreach ($rows as $row) {
            dump($row->getF());
        }

  //   if($type == 'chart'){
  //   	$chartPv = array_map(function($row) {
		//     return array('name' => $row->date, 'y' => $row->pv);
		// }, $pv);
		// $chartCategories = array_map(function($row){
		// 	return $row->date;
		// }, $pv);
  //   	return view('chart', [
	 //        'pv' => $chartPv,
	 //        'category' => $chartCategories,
	 //        'type' => 'chart'
	 //    ]);
  //   }
  //   return view('table', [
  //       'pv' => $pv,
  //       'type' => 'table'
  //   ]);
});
