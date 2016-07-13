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

    private static $DataUsersField = [
        'date, Duplicated_CameThroughEmailPlusDonors, Unduplicated_TotalUsersKPI, Duplicated_Database_CameThroughEmailPlusDonors, Unduplicated_Database_TotalUsersKPI, Unduplicated_TotalUsersKPI / Unduplicated_Database_TotalUsersKPI as Loyal_Users_On_Site',
        'date, CameToSiteThroughEmail, KPI_TotalEmailSubscribersKnownToMIP, KPI_PercentKnownSubsWhoCame, NewEmailSubscribers',
        'date, TotalDonorsThisWeek, KPI_TotalDonorsKnownToMIP, TotalDonorsThisWeek / KPI_TotalDonorsKnownToMIP as Donors_In_MIP'
    ];

    private static $DataUsersColumn = [
        ['Week of', 'Email Subscribers and Donors on Site', 'Email Subscribers or Donors on Site or Both Email Subscriber and Donor', 'Email Subscribers and Donors in MIP DB', 'Emails Subscribers or Donors or Both Email Subscriber and Donor in DB', '% of Loyal Users on Site'],
        ['Week of', '(Eloqua) Email Subscribers on Site', 'Total Email Subscribers in MIP DB', '% of Email Subscribers in MIP DB on Site', 'New Email Subscribers'],
        ['Week of', 'Donors Donating', 'Donors in MIP DB', '% of Donors in MIP DB Donating']
    ];

    public function showUsers(Request $request){
        $group = array_key_exists($request['group'], self::$groupDisplay) ? $request['group'] : 'weekly';
        $max_date = date_parse($request['max_date'] ?: date('Y-m-d', time()));
        $min_date = date_parse($request['min_date'] ?: date('Y-m-1', time()));
        $client_id = $request['client']['id'];

        $query = DB::table('data_users_' . $group)
            ->select(DB::raw('count(*)'))
            ->where('client_id', $client_id);

        $count = $query->count();

        return view('data.users', [
            'have_data' => $count > 0,
            'min_date' => mktime(0, 0, 0, $min_date['month'], $min_date['day'], $min_date['year']),
            'max_date' => mktime(0, 0, 0, $max_date['month'], $max_date['day'], $max_date['year']),
            'group' => $group,
            'displayGroupName' => self::$groupDisplay[$group]
        ]);
    }

    public function get_Users_Total_Known_Users(Request $request){
        return $this->dataTableQuery($request, 'data_users_', $this::$DataUsersField[0]);
    }

    public function download_Users_Total_Known_Users(Request $request){
        $this->exportCSV($request, 'data_users_', $this::$DataUsersField[0], $this::$DataUsersColumn[0]);
    }

    public function get_Users_Email_Newsletter_Subscribers(Request $request){
        return $this->dataTableQuery($request, 'data_users_', $this::$DataUsersField[1]);
    }

    public function download_Users_Email_Newsletter_Subscribers(Request $request){
        $this->exportCSV($request, 'data_users_', $this::$DataUsersField[1], $this::$DataUsersColumn[1]);
    }

    public function get_Users_Donors(Request $request){
        return $this->dataTableQuery($request, 'data_users_', $this::$DataUsersField[2]);
    }

    public function download_Users_Donors(Request $request){
        $this->exportCSV($request, 'data_users_', $this::$DataUsersField[2], $this::$DataUsersColumn[2]);
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


    private static $DataStoriesPercentField = [
        'Page_Path, Article, Pageviews, Scroll_Start/Pageviews as StartedScrolling, Scroll_25/Pageviews as Scroll25, Scroll_50/Pageviews as Scroll50, Scroll_75/Pageviews as Scroll75, Scroll_100/Pageviews as Scroll100, Scroll_Supplemental/Pageviews as RelatedContent, Scroll_End/Pageviews as EndOfPage',
        'Page_Path, Article, Pageviews, Scroll_Start as StartedScrolling, Scroll_25 as Scroll25, Scroll_50 as Scroll50, Scroll_75 as Scroll75, Scroll_100 as Scroll100, Scroll_Supplemental as RelatedContent, Scroll_End as EndOfPage',
        'Page_Path, Article, Pageviews, Time_15/Pageviews as Time15, Time_30/Pageviews as Time30, Time_45/Pageviews as Time45, Time_60/Pageviews as Time60, Time_75/Pageviews as Time75, Time_90/Pageviews as Time90',
        'Page_Path, Article, Pageviews, Time_15 as Time15, Time_30 as Time30, Time_45 as Time45, Time_60 as Time60, Time_75 as Time75, Time_90 as Time90',
        'Page_Path, Article, Pageviews, Comments, Emails, Tweets, Facebook_Recommendations, Comments + Emails + Tweets + Facebook_Recommendations as TotalShares, (Comments + Emails + Tweets + Facebook_Recommendations) / Pageviews as SahreRate, Related_Clicks, Related_Clicks / Scroll_Supplemental as ClickThroughRate'
    ];

    private static $DataStoriesColumn = [
        ['Article Title', 'Total Page Views', 'Started Scrolling', '25% Scroll', '50% Scroll', '75% Scroll', '100% Scroll', 'Related Content', 'End of Page'],
        ['Article Title', 'Total Page Views', '15 Seconds', '30 Seconds', '45 Seconds', '60 Seconds', '75 Seconds', '90 Seconds'],
        ['Article Title', 'Total Page Views', 'Comments', 'Email Shares', 'Tweets', 'FB Shares', 'Total Shares', 'Share Rate', 'Related Content Clicks', 'Click Through Rate']
    ];
    public function showStories(Request $request){
        $group = array_key_exists($request['group'], self::$groupDisplay) ? $request['group'] : 'weekly';
        $current_week_sunday = mktime(0,0,0,date('m'),date('d') - date('N', time()),date('Y'));
        $last_week_begin = $current_week_sunday - 60 * 60 * 24 * 7;
        $last_week_end = $current_week_sunday - 60 * 60 * 24 * 1;
        $max_date = date_parse($request['max_date'] ?: date('Y-m-d', $last_week_end));
        $min_date = date_parse($request['min_date'] ?: date('Y-m-d', $last_week_begin));

        $client_id = $request['client']['id'];

        $count = DB::table('data_stories_' . $group)
            ->where('client_id', $client_id)
            ->count();

        return view('data.stories', [
            'have_data' => $count > 0,
            'website' => $request['client']['website'],
            'min_date' => mktime(0, 0, 0, $min_date['month'], $min_date['day'], $min_date['year']),
            'max_date' => mktime(0, 0, 0, $max_date['month'], $max_date['day'], $max_date['year']),
            'group' => $group,
            'displayGroupName' => self::$groupDisplay[$group]
        ]);
    }

    public function get_Stories_Scroll_Depth(Request $request, $mode)
    {
        $index = $mode == 'count' ? 1 : 0;
        return $this->dataTableQuery($request, 'data_stories_', $this::$DataStoriesPercentField[$index]);
    }
    public function get_Stories_Time_On_Article(Request $request, $mode)
    {
        $index = $mode == 'count' ? 3 : 2;
        return $this->dataTableQuery($request, 'data_stories_', $this::$DataStoriesPercentField[$index]);
    }
    public function get_Stories_User_Interactions(Request $request)
    {
        return $this->dataTableQuery($request, 'data_stories_', $this::$DataStoriesPercentField[4]);
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