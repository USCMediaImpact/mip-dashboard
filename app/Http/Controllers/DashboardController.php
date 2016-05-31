<?php

namespace App\Http\Controllers;

use DB;
use Google_Client;
use Google_Service_Bigquery;
use Google_Service_Bigquery_QueryRequest;
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

    public function showDashboard(){
        $report = $this->mockChartFromBigQuery();
        return view('dashboard', ['report' => $report]);
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
	    $queryString = '
			SELECT
			  date,
			  SUM(totals.visits) AS visits,
			  SUM(totals.hits) AS hits,
			  SUM(totals.pageviews) AS pageviews
			FROM
			  TABLE_DATE_RANGE([116430105.ga_sessions_tz_], TIMESTAMP("2016-05-01"), TIMESTAMP("2016-06-01"))
			GROUP BY
			  date
			ORDER BY
			  date';

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
	    	$report[] = array(
                'date' => $row['f']['0']['v'],
                'visits' => $row['f']['1']['v'],
                'hits' => $row['f']['1']['v'],
                'pageviews' => $row['f']['1']['v']
            );
	    }
	    return view('dashboard', ['report' => $report]);
	}

    public function showChartFromBigQuery(){
        $projectId = 'tonal-studio-119521';
        $queryString = '
			SELECT
			  date,
			  SUM(totals.visits) AS visits,
			  SUM(totals.hits) AS hits,
			  SUM(totals.pageviews) AS pageviews
			FROM
			  TABLE_DATE_RANGE([116430105.ga_sessions_tz_], TIMESTAMP("2016-05-01"), TIMESTAMP("2016-06-01"))
			GROUP BY
			  date
			ORDER BY
			  date';

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
            $report[] = array(
                'date' => $row['f']['0']['v'],
                'visits' => $row['f']['1']['v'],
                'hits' => $row['f']['2']['v'],
                'pageviews' => $row['f']['3']['v']
            );
        }
        return $report;
    }

    public function mockChartFromBigQuery(){
        return [
            array(
                'date' => '20160501',
                'visits' => '24526',
                'hits' => '340093',
                'pageviews' => '66534'
            ),
            array(
                'date' => '20160502',
                'visits' => '47042',
                'hits' => '673029',
                'pageviews' => '128949'
            ),
            array(
                'date' => '20160503',
                'visits' => '51481',
                'hits' => '721708',
                'pageviews' => '138107'
            ),
            array(
                'date' => '20160504',
                'visits' => '91404',
                'hits' => '1131268',
                'pageviews' => '187993'
            ),
            array(
                'date' => '20160505',
                'visits' => '103058',
                'hits' => '1170603',
                'pageviews' => '199053'
            ),
            array(
                'date' => '20160506',
                'visits' => '41100',
                'hits' => '590747',
                'pageviews' => '124243'
            ),
            array(
                'date' => '20160507',
                'visits' => '29682',
                'hits' => '379989',
                'pageviews' => '70874'
            ),
            array(
                'date' => '20160508',
                'visits' => '37125',
                'hits' => '453912',
                'pageviews' => '78380'
            ),
            array(
                'date' => '20160509',
                'visits' => '48897',
                'hits' => '671048',
                'pageviews' => '137162'
            )
        ];
    }
}