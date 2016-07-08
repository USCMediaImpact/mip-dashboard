<?php

namespace App\Http\Controllers;

use DB;
use Cache;
use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Bigquery;
use Google_Service_Bigquery_QueryRequest;
use App\Models\User;

class DataController extends AuthenticatedBaseController{
    
    public function showContent(){

        return view('data.content');
    }

    public function showUsers(Request $request){
        $group = array_key_exists($request['group'], self::$groupDisplay) ? $request['group'] : 'weekly';
        $max_date = date_parse($request['max_date'] ?: date('Y-m-d', time()));
        $min_date = date_parse($request['min_date'] ?: date('Y-m-1', time()));
        $client_id = $request['client']['id'];
        
        $query = DB::table('data_users_' . $group)
            ->select('date', 'TotalMembersThisWeek','KPI_TotalMembersKnownToMIP','CameToSiteThroughEmail','KPI_TotalEmailSubscribersKnownToMIP','KPI_PercentKnownSubsWhoCame','NewEmailSubscribers','TotalDonorsThisWeek','KPI_TotalDonorsKnownToMIP','Duplicated_CameThroughEmailPlusDonors','Unduplicated_TotalUsersKPI','Duplicated_Database_CameThroughEmailPlusDonors','Unduplicated_Database_TotalUsersKPI')
            ->where('client_id', $client_id);

        $count = $query->count();
        $report = $query->where('date', '<=', $max_date['year'] . '-' . $max_date['month'] . '-' . $max_date['day'])
            ->where('date', '>=', $min_date['year'] . '-' . $min_date['month'] . '-' . $min_date['day'])
            ->orderBy('date', 'desc')
            ->get();
        $report = array_map(function($row){
            return get_object_vars($row);
        }, $report);

        return view('data.users', [
            'have_data' => $count > 0,
            'report' => $report,
            'min_date' => mktime(0, 0, 0, $min_date['month'], $min_date['day'], $min_date['year']),
            'max_date' => mktime(0, 0, 0, $max_date['month'], $max_date['day'], $max_date['year']),
            'group' => $group,
            'displayGroupName' => self::$groupDisplay[$group]
        ]);
    }

    public function showDonations(Request $request){
        $group = array_key_exists($request['group'], self::$groupDisplay) ? $request['group'] : 'weekly';
        $max_date = date_parse($request['max_date'] ?: date('Y-m-d', time()));
        $min_date = date_parse($request['min_date'] ?: date('Y-m-1', time()));
        $client_id = $request['client']['id'];

        $query = DB::table('data_stories_' . $group)
            ->select('date', 'page_path', 'pageviews', 'scroll_start', 'scroll_25', 'scroll_50', 'scroll_75', 'scroll_100', 'scroll_supplemental', 'scroll_end', 'time_15', 'time_30', 'time_45', 'time_60', 'time_75', 'time_90', 'comments', 'emails', 'tweets', 'facebook_recommendations', 'related_clicks' )
            ->where('client_id', $client_id)
            ->limit(10);

        $count = $query->count();
        $report = $query->where('date', '<=', $max_date['year'] . '-' . $max_date['month'] . '-' . $max_date['day'])
            ->where('date', '>=', $min_date['year'] . '-' . $min_date['month'] . '-' . $min_date['day'])
            ->orderBy('date', 'desc')
            ->get();
        $report = array_map(function($row){
            return get_object_vars($row);
        }, $report);

        return view('data.donations', [
            'have_data' => $count > 0,
            'report' => $report,
            'min_date' => mktime(0, 0, 0, $min_date['month'], $min_date['day'], $min_date['year']),
            'max_date' => mktime(0, 0, 0, $max_date['month'], $max_date['day'], $max_date['year']),
            'group' => $group,
            'displayGroupName' => self::$groupDisplay[$group]
        ]);
    }

    public function showStories(Request $request){
        $group = array_key_exists($request['group'], self::$groupDisplay) ? $request['group'] : 'weekly';
        $current_week_sunday = mktime(0,0,0,date('m'),date('d') - date('N', time()),date('Y'));
        $last_week_begin = $current_week_sunday - 60 * 60 * 24 * 7;
        $last_week_end = $current_week_sunday - 60 * 60 * 24 * 1;
        $max_date = date_parse($request['max_date'] ?: date('Y-m-d', $last_week_end));
        $min_date = date_parse($request['min_date'] ?: date('Y-m-d', $last_week_begin));
        $client_id = $request['client']['id'];

        $query = DB::table('data_stories_' . $group)
            ->select('date', 'Page_Path', 'Pageviews', 'Scroll_Start', 'Scroll_25', 'Scroll_50', 'Scroll_75', 'Scroll_100', 'Scroll_Supplemental', 'Scroll_End', 'Time_15', 'Time_30', 'Time_45', 'Time_60', 'Time_75', 'Time_90', 'Comments', 'Emails', 'Tweets', 'Facebook_Recommendations', 'Related_Clicks' )
            ->where('client_id', $client_id);

        $count = $query->count();
        $report = $query->where('date', '<=', $max_date['year'] . '-' . $max_date['month'] . '-' . $max_date['day'])
            ->where('date', '>=', $min_date['year'] . '-' . $min_date['month'] . '-' . $min_date['day'])
            ->orderBy('date', 'desc')
            ->orderBy('Pageviews', 'desc')
            ->limit(10)
            ->get();
        $report = array_map(function($row){
            return get_object_vars($row);
        }, $report);

        return view('data.stories', [
            'have_data' => $count > 0,
            'report' => $report,
            'min_date' => mktime(0, 0, 0, $min_date['month'], $min_date['day'], $min_date['year']),
            'max_date' => mktime(0, 0, 0, $max_date['month'], $max_date['day'], $max_date['year']),
            'group' => $group,
            'displayGroupName' => self::$groupDisplay[$group]
        ]);
    }

    public function showQuality(Request $request){
        $group = array_key_exists($request['group'], self::$groupDisplay) ? $request['group'] : 'weekly';
        $max_date = date_parse($request['max_date'] ?: date('Y-m-d', time()));
        $min_date = date_parse($request['min_date'] ?: date('Y-m-1', time()));
        $client_id = $request['client']['id'];

        $query = DB::table('data_quality_new_' . $group)
            ->select('date','GA_Users', 'MIP_Users', 'Came_To_Site_Subscribed_And_Came_Through_Email', 'Came_To_Site_Came_Through_Email_For_First_Time', 'Came_To_Site_Came_Through_Email_Again', 'Came_To_Site_Total_Came_To_Site_Through_Email', 'New_Subscribers_Subscribed_And_Came_Through_Email', 'New_Subscribers_Subscribed_Only', 'New_Subscribers_KPI_New_Email_Subscribers', 'Known_To_MIP_New_Email_Subscribers', 'Known_To_MIP_Came_Through_Email_For_First_Time', 'Known_To_MIP_Came_Through_Email_Again', 'Known_To_MIP_Subscribers_Who_Did_Not_Come_Through_Email', 'Known_To_MIP_KPI_Total_Email_Subscribers_Known_To_MIP', 'Known_To_MIP_KPI_Percent_Known_Subs_Who_Came', 'Donated_This_Week_New_Donors', 'Donated_This_Week_Donated_Again', 'Donated_This_Week_Total_Donors_This_Week', 'Known_To_MIP_New_Donors', 'Known_To_MIP_Donated_Again', 'Known_To_MIP_Database_Donors_Who_Did_Not_Donate_This_Week', 'Known_To_MIP_KPI_Total_Donors_Known_To_MIP', 'Known_To_MIP_KPI_Percent_Known_Donors_Who_Donated', 'Email_Newsletter_Clicks', 'Totol_Donors_This_Week', 'Logged_In_This_Week_New_Logins', 'Logged_In_This_Week_Logged_In_Again', 'Logged_In_This_Week_Total_Logins_This_Week', 'Known_To_MIP_New_Logins', 'Known_To_MIP_Logged_In_Again', 'Known_To_MIP_Database_Members_Who_Did_Not_Login_This_Week', 'Known_To_MIP_KPI_Total_Members_Known_To_MIP', 'Known_To_MIP_KPI_Percent_Known_Members_Who_Logged_In')
            ->where('client_id', $client_id);

        $count = $query->count();
        $report = $query->where('date', '<=', $max_date['year'] . '-' . $max_date['month'] . '-' . $max_date['day'])
            ->where('date', '>=', $min_date['year'] . '-' . $min_date['month'] . '-' . $min_date['day'])
            ->orderBy('date', 'desc')
            ->get();
        $report = array_map(function($row){
            return get_object_vars($row);
        }, $report);

        return view('data.quality', [
            'have_data' => $count > 0,
            'report' => $report,
            'min_date' => mktime(0, 0, 0, $min_date['month'], $min_date['day'], $min_date['year']),
            'max_date' => mktime(0, 0, 0, $max_date['month'], $max_date['day'], $max_date['year']),
            'group' => $group,
            'displayGroupName' => self::$groupDisplay[$group]
        ]);
    }
}