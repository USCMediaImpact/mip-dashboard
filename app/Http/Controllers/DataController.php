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
        $client_id = $request['client.id'];

        $query = DB::table('data_users_' . $group)
            ->select('date', 'totalmembersthisweek', 'kpi_totalmembersknowntomip', 'cametositethroughemail', 'kpi_totalemailsubscribersknowntomip', 'kpi_percentknownsubswhocame', 'kpi_newemailsubscribers', 'totaldonorsthisweek', 'kpi_totaldonorsknowntomip', 'duplicated_memberspluscamethroughemailplusdonors', 'unduplicated_totaluserskpi', 'duplicated_database_memberspluscamethroughemailplusdonors', 'unduplicated_database_totaluserskpi')
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
        $client_id = $request['client.id'];

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
        $max_date = date_parse($request['max_date'] ?: date('Y-m-d', time()));
        $min_date = date_parse($request['min_date'] ?: date('Y-m-1', time()));
        $client_id = $request['client.id'];

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
        $client_id = $request['client.id'];
        $query = DB::table('data_quality_' . $group)
            ->select('date', 'events', 'ga_users', 'mip_users', 'user_variance', 'identified_emailsubscribers', 'known_emailsubscribers', 'total_database_emails', 'identified_newemailsubscribers', 'email_newsletter_clicks', 'eloqua_email_newsletter_clicks', 'email_newsletter_clicks_variance', 'identified_donors', 'known_donors', 'eloqua_known_donors', 'donors_variance', 'total_known_donors', 'total_known__unique_email')
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