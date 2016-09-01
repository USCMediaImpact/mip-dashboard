<?php

namespace App\Http\Controllers;

use DB;
use Cache;
use Illuminate\Http\Request;
use IntlCalendar;
use IntlDateFormatter;
use Google_Client;
use Google_Service_Bigquery;
use Google_Service_Bigquery_QueryRequest;

class DashboardController extends AuthenticatedBaseController{
    public static function caculateDateRange($date, $minDate, $maxDate, $count = 24){
        $result = [];
        $result[] = $date;
        $breakNext = false;
        $breakPrev = false;
        $added = 1;
        for ($index = 1; ; $index++){
            $key = $index * 7;
            $next = strtotime("+${key} days", $date);
            $prev = strtotime("-${key} days", $date);
            if($next <= $maxDate){
                $result[] = $next;
                $added++;
            }else{
                $breakNext = true;
            }
            if($prev >= $minDate){
                $result[] = $prev;
                $added++;
            }else{
                $breakPrev = true;
            }
            if(($breakNext && $breakPrev) || $added > $count){
                break;
            }
        }
        sort($result);
        return array_map(function($item){
            return date('Y-m-d', $item);
        }, $result);
    }

    /**
     * the date('W', time) is used monday as the week begin.
     * we need use intl to get right week number base week begin as sunday.
     * BTW. The month sequence is zero-based, i.e., January is represented by 0
     * And the Week Number of the Year is need fix to start at this year first sunday
     * the format string see more here http://www.icu-project.org/apiref/icu4c/classSimpleDateFormat.html#details
     */
    public static function caculateLastYearSameWeek($range){
        $cal = IntlCalendar::createInstance();
        $cal->setFirstDayOfWeek(IntlCalendar::DOW_SUNDAY);

        return array_map(function($date) use($cal){
            $dateArray = getdate(strtotime($date));
            //get current year January 1 is sunday
            $cal->set($dateArray['year'], 0, 1);
            $isSunday = IntlDateFormatter::formatObject($cal, 'e') == 1;
            //get current day week number of year
            $cal->set($dateArray['year'], $dateArray['mon'] - 1, $dateArray['mday']);
            $currentYearOfWeek = IntlDateFormatter::formatObject($cal, 'w');

            if(!$isSunday){
                $currentYearOfWeek--;
            }

            //get last year first sunday
            $cal->set($dateArray['year'] - 1, 0, 1);
            $lastYearWeekDay = IntlDateFormatter::formatObject($cal, 'e');
            $offset = (8 - $lastYearWeekDay) % 7;

            $cal->add(IntlCalendar::FIELD_DAY_OF_YEAR, $offset);
            $cal->add(IntlCalendar::FIELD_DAY_OF_YEAR, 7 * ($currentYearOfWeek - 1));

            return IntlDateFormatter::formatObject($cal, 'Y-MM-dd');
        }, $range);
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

        $range = self::caculateDateRange($min_date, $date_range_min, $date_range_max);
        $compareRange = self::caculateLastYearSameWeek($range);

        $thisWeekEnd = date('Y-m-d', $max_date);

        $dataBox1To4 = DB::table("${client_code}_data_users_${group}")
            ->select(DB::raw('Unduplicated_TotalUsersKPI, Unduplicated_Database_TotalUsersKPI, IFNULL(Unduplicated_TotalUsersKPI, 0) / IFNULL(Unduplicated_Database_TotalUsersKPI, 0) as Loyal_Users_On_Site, KPI_TotalEmailSubscribersKnownToMIP, KPI_TotalDonorsKnownToMIP'))
            ->where('date', '<=', $thisWeekEnd)
            ->where('date', '>=', date('Y-m-d', strtotime('-7 days', $min_date)))
            ->orderBy('date', 'desc')
            ->get();

        $dataBox5 = DB::table("${client_code}_data_users_${group}")
            ->select(DB::raw('date, TotalDonorsThisWeek, CameToSiteThroughEmail'))
            ->where('date', '>=', reset($range))
            ->where('date', '<=', end($range))
            ->orderBy('date')
            ->get();

        $dataBox5KV = [];
        foreach ($dataBox5 as $item){
            $dataBox5KV[$item->date] = [
                'TotalDonorsThisWeek' => $item->TotalDonorsThisWeek,
                'CameToSiteThroughEmail' => $item->CameToSiteThroughEmail
            ];
        }


        $dataBox5Compare = DB::table("${client_code}_data_users_${group}")
            ->select(DB::raw('date, IFNULL(TotalDonorsThisWeek, 0) + IFNULL(CameToSiteThroughEmail, 0) as lastYearTotal'))
            ->where('date', '>=', reset($compareRange))
            ->where('date', '<=', end($compareRange))
            ->orderBy('date')
            ->get();

        $dataBox5CompareKV = [];
        foreach ($dataBox5Compare as $item){
            $dataBox5CompareKV[$item->date] = $item->lastYearTotal;
        }

        $dataBox6 = DB::table("${client_code}_data_users_${group}")
            ->select(DB::raw('date, IFNULL(Unduplicated_Database_TotalUsersKPI, 0) as Unduplicated_Database_TotalUsersKPI'))
            ->where('date', '>=', reset($range))
            ->where('date', '<=', end($range))
            ->orderBy('date')
            ->get();

        $dataBox6KV = [];
        foreach ($dataBox6 as $item){
            $dataBox6KV[$item->date] = $item->Unduplicated_Database_TotalUsersKPI;
        }

        $dataBox6Compare = DB::table("${client_code}_data_users_${group}")
            ->select(DB::raw('date, IFNULL(Unduplicated_Database_TotalUsersKPI, 0) as Unduplicated_Database_TotalUsersKPI'))
            ->where('date', '>=', reset($compareRange))
            ->where('date', '<=', end($compareRange))
            ->orderBy('date')
            ->get();
        $dataBox6CompareKV = [];
        foreach ($dataBox6Compare as $item){
            $dataBox6CompareKV[$item->date] = $item->Unduplicated_Database_TotalUsersKPI;
        }



        $dataBox5Result = [];
        $dataBox6Result = [];
        for($i = 0; $i < count($range); $i++){
            $rangeKey = $range[$i];
            $compareKey = $compareRange[$i];

            $dataBox5Result[] = [
                'date' => $range[$i],
                'TotalDonorsThisWeek' => array_key_exists($rangeKey, $dataBox5KV) ? $dataBox5KV[$rangeKey]['TotalDonorsThisWeek'] : null,
                'CameToSiteThroughEmail' => array_key_exists($rangeKey, $dataBox5KV) ? $dataBox5KV[$rangeKey]['CameToSiteThroughEmail'] : null,
                'LastYearTotal' => array_key_exists($compareKey, $dataBox5CompareKV) ? $dataBox5CompareKV[$compareKey] : null,
                'LastYearDate' => $compareKey
            ];

            $changes = null;

            if (array_key_exists($rangeKey, $dataBox6KV)
                && array_key_exists($compareKey, $dataBox6CompareKV)) {
                if ($dataBox6KV[$rangeKey] == 0 || $dataBox6CompareKV[$compareKey] == 0){
                    $changes = 0;
                } else if ($dataBox6CompareKV[$compareKey] == 0){
                    $changes = 100;
                } else{
                    $changes = round(100 * ($dataBox6KV[$rangeKey] - $dataBox6CompareKV[$compareKey]) / $dataBox6CompareKV[$compareKey]);
                }

            }
            $dataBox6Result[] = [
                'date' => $range[$i],
                'changes' => $changes
            ];
        }

        return view("dashboard.users", [
            'max_date'=> $max_date,
            'min_date'=> $min_date,
            'date_range_min' => date('Y-m-d', $date_range_min),
            'date_range_max' => date('Y-m-d', $date_range_max),
            'default_date_range' => date('Y-m-d', $date_range_min) . ' - ' . date('Y-m-d', $date_range_max),
            'dataBox1To4' => $dataBox1To4,
            'dataBox5' => $dataBox5Result,
            'dataBox6' => $dataBox6Result
        ]);
    }
}