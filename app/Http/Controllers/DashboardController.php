<?php

namespace App\Http\Controllers;

use DB;
use Cache;
use Illuminate\Http\Request;

use Google_Client;
use Google_Service_Bigquery;
use Google_Service_Bigquery_QueryRequest;

class DashboardController extends AuthenticatedBaseController{
    public static function caculateDateRange($date, $maxDate, $minDate){
        
    }
    public function show(Request $request){
        $client_id = $request['client']['id'];
        $client_code = $request['client']['code'];
        $group = array_key_exists($request['group'], parent::$groupDisplay) ? $request['group'] : 'weekly';

        $query = DB::table("${client_code}_data_users_${group}");
        $date_range_min = strtotime($query->min('date'));
        $date_range_max = strtotime($query->max('date'));
        $default_min_date = $date_range_max;
        $date_range_max = strtotime('6 days', $date_range_max);

        $max_date = $request['max_date'] ? strtotime($request['max_date']) : $date_range_max;
        $min_date = $request['min_date'] ? strtotime($request['min_date']) : $default_min_date;

        $thisWeekEnd = date('Y-m-d', $max_date);
        $thisWeekBegin = date('Y-m-d', $min_date);
        $firstDayOfThisYear = date('Y-01-01', $max_date);
        $lastYearThisWeekEnd = date('Y-m-d', strtotime('-1 years', $max_date));
        $lastYearThisWeekBegin = date('Y-m-d', strtotime('-1 years', $min_date));
        $firstDayOfLastYear = date('Y-01-01', strtotime('-1 years', $min_date));

        $dataBox1To4 = DB::table("${client_code}_data_users_${group}")
            ->select(DB::raw('Unduplicated_TotalUsersKPI, Unduplicated_Database_TotalUsersKPI, Unduplicated_TotalUsersKPI / Unduplicated_Database_TotalUsersKPI as Loyal_Users_On_Site, KPI_TotalEmailSubscribersKnownToMIP, KPI_TotalDonorsKnownToMIP'))
            ->where('date', '<=', $thisWeekEnd)
            ->where('date', '>=', date('Y-m-d', strtotime('-7 days', $min_date)))
            ->orderBy('date', 'desc')
            ->get();


        $dataBox5 = DB::table("${client_code}_data_users_${group}")
            ->select(DB::raw('date, year(date) as year, week(date) as week, TotalDonorsThisWeek, CameToSiteThroughEmail'))
            ->where('date', '<=', $thisWeekEnd)
            ->orderBy('date', 'desc')
            ->take(24)
            ->get();
        $dataBox5LastYear = DB::table("${client_code}_data_users_${group}")
            ->select(DB::raw('date, year(date) as year, week(date) as week, TotalDonorsThisWeek, CameToSiteThroughEmail'))
            ->where('date', '<=', $lastYearThisWeekEnd)
            ->where('date', '>=', $firstDayOfLastYear)
            ->orderBy('date', 'desc')
            ->get();

        $dataBox5Temp = [];
        foreach ($dataBox5LastYear as $row){
            $key = ($row->year + 1) . '-' . $row->week;
            if($row->TotalDonorsThisWeek === null && $row->TotalDonorsThisWeek === null){
                $dataBox5Temp[$key] = null;
            }else{
                $dataBox5Temp[$key] = ($row->TotalDonorsThisWeek ?: 0) + ($row->TotalDonorsThisWeek ?: 0);
            }
        }
        foreach($dataBox5 as $row){
            $key = $row->year . '-' . $row->week;
            $row->lastYear = array_key_exists($key, $dataBox5Temp) ? $dataBox5Temp[$key] : null;
        }


        $dataBox6 = DB::table("${client_code}_data_users_${group}")
            ->select(DB::raw('date, year(date) as year, week(date) as week, Unduplicated_Database_TotalUsersKPI'))
            ->where('date', '<=', $thisWeekEnd)
            ->where('date', '>=', $firstDayOfThisYear)
            ->orderBy('date', 'desc')
            ->get();

        $dataBox6LastYear = DB::table("${client_code}_data_users_${group}")
            ->select(DB::raw('date, year(date) as year, week(date) as week, Unduplicated_Database_TotalUsersKPI'))
            ->where('date', '<=', $lastYearThisWeekEnd)
            ->where('date', '>=', $firstDayOfLastYear)
            ->orderBy('date', 'desc')
            ->get();
        $dataBox6Temp = [];
        foreach($dataBox6LastYear as $row){
            $key = $row->week;
            $dataBox6Temp[$key] = $row->Unduplicated_Database_TotalUsersKPI;
        }
        foreach ($dataBox6 as $row){
            $key = $row->week;
            if(array_key_exists($key, $dataBox6Temp)){
                $row->change = ($row->Unduplicated_Database_TotalUsersKPI - $dataBox6Temp[$key]) / $dataBox6Temp[$key];
            }
        }

        return view("dashboard.users", [
            'max_date'=> $max_date,
            'min_date'=> $min_date,
            'date_range_min' => date('Y-m-d', $date_range_min),
            'date_range_max' => date('Y-m-d', $date_range_max),
            'default_date_range' => date('Y-m-d', $date_range_min) . ' - ' . date('Y-m-d', $date_range_max),
            'dataBox1To4' => $dataBox1To4,
            'dataBox5' => $dataBox5,
            'dataBox6' => $dataBox6
        ]);
    }
}