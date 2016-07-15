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
        return $this->exportCSV($request, 'data_users_', $this::$DataUsersField[0], $this::$DataUsersColumn[0], 'Total Known Users.csv');
    }

    public function get_Users_Email_Newsletter_Subscribers(Request $request){
        return $this->dataTableQuery($request, 'data_users_', $this::$DataUsersField[1]);
    }

    public function download_Users_Email_Newsletter_Subscribers(Request $request){
        return $this->exportCSV($request, 'data_users_', $this::$DataUsersField[1], $this::$DataUsersColumn[1], 'Email Newsletter Subscribers.csv');
    }

    public function get_Users_Donors(Request $request){
        return $this->dataTableQuery($request, 'data_users_', $this::$DataUsersField[2]);
    }

    public function download_Users_Donors(Request $request){
        return $this->exportCSV($request, 'data_users_', $this::$DataUsersField[2], $this::$DataUsersColumn[2], 'Donors.csv');
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


    private static $DataStoriesField = [
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
        return $this->dataTableQuery($request, 'data_stories_', $this::$DataStoriesField[$index]);
    }
    public function get_Stories_Time_On_Article(Request $request, $mode)
    {
        $index = $mode == 'count' ? 3 : 2;
        return $this->dataTableQuery($request, 'data_stories_', $this::$DataStoriesField[$index]);
    }
    public function get_Stories_User_Interactions(Request $request)
    {
        return $this->dataTableQuery($request, 'data_stories_', $this::$DataStoriesField[4]);
    }

    private static $DataQualityField = [
        'date, \'\' as Events, GA_Users, MIP_Users, (GA_Users - MIP_Users) / MIP_Users as Variance',
        'date, I_inDatabaseCameToSite, K_inDatabaseCameToSite, I_notInDatabaseCameToSite, K_notInDatabaseCameToSite, I_newSubscriberCameThroughEmail, K_newSubscriberCameThroughEmail, I_SubscribersThisWeek, K_SubscribersThisWeek, I_NewSubscribers, K_NewSubscribers, I_TotalDatabaseSubscribers, K_TotalDatabaseSubscribers, K_PercentDatabaseSubscribersWhoCame, EmailNewsletterClicks',
        'date, I_databaseDonorsWhoVisited, K_databaseDonorsWhoVisited, I_donatedOnSiteForFirstTime, K_donatedOnSiteForFirstTime, I_totalDonorsOnSiteThisWeek, K_totalDonorsOnSiteThisWeek, I_totalDonorsInDatabase, K_totalDonorsInDatabase, K_percentDatabaseDonorsWhoCame',
        'date, K_individualsWhoCameThisWeek, K_individualsInDatabase, K_percentDatabaseIndividualsWhoCame'
    ];

    private static $DataQualityColumn = [
        ['Week of', 'Events', 'GA Users', 'MIP GTM Users', 'Variance'],
        ['Week of', 'Identified: Subscribers already in MIP database who came to the site this week', 'Known: Subscribers already in MIP database who came to the site this week', 'Identified: Subscribers who came to the site through an e-mail this week for the first time since MIP started collecting data', 'Known: Subscribers who came to the site through an e-mail this week for the first time since MIP started collecting data', 'Identified: New subscribers this week who also clicked on an e-mail this week', 'Known: New subscribers this week who also clicked on an e-mail this week', 'Identified e-mail newsletter subscribers THIS WEEK', 'Known e-mail newsletter subscribers THIS WEEK (unique ELQs)', 'Identified: New e-mail subscribers this week', 'Known: New e-mail subscribers this week', 'Identified: Total identified e-mail newsletter subscribers in the MIP database', 'Known: Total number of known e-mail newsletter subscribers in the MIP database', 'Known: Percent of subscribers in the MIP database who clicked on an e-mail this week', 'E-mail newsletter clicks per week'],
        ['Week', 'Identified: Donors already in MIP database who came to the site this week', 'Known: Donors already in MIP database who came to the site this week', 'Identified: Users who donated on the site for the first time since MIP started collecting data', 'Known: Users who donated on the site for the first time since MIP started collecting data', 'Identified donors on the site THIS WEEK', 'Known donors on the site THIS WEEK', 'Identified: Total identified donors in the MIP database', 'Known: Total known donors in the MIP database', 'Known: Percent of subscribers in the MIP database who clicked on an e-mail this week'],
        ['Week of', 'Known: Total known donors and/or e-mail newsletter subscribers who came to the site THIS WEEK', 'Known: Total known donors and/or e-mail newsletter subscribers in the MIP database', 'Known: Percent of known individuals in the MIP database who came to the site this week']
    ];

    public function showQuality(Request $request){
        $group = array_key_exists($request['group'], self::$groupDisplay) ? $request['group'] : 'weekly';
        $max_date = date_parse($request['max_date'] ?: date('Y-m-d', time()));
        $min_date = date_parse($request['min_date'] ?: date('Y-m-1', time()));
        $client_id = $request['client']['id'];

        $count = DB::table('data_quality_' . $group)
            ->where('client_id', $client_id)
            ->count();

        return view('data.quality', [
            'have_data' => $count > 0,
            'min_date' => mktime(0, 0, 0, $min_date['month'], $min_date['day'], $min_date['year']),
            'max_date' => mktime(0, 0, 0, $max_date['month'], $max_date['day'], $max_date['year']),
            'group' => $group,
            'displayGroupName' => self::$groupDisplay[$group]
        ]);
    }

    public function get_Quality_GA_VS_GTM(Request $request){
        return $this->dataTableQuery($request, 'data_quality_', $this::$DataQualityField[0]);
    }

    public function get_Quality_Email_Subscribers(Request $request){
        return $this->dataTableQuery($request, 'data_quality_', $this::$DataQualityField[1]);
    }

    public function get_Quality_Donors(Request $request){
        return $this->dataTableQuery($request, 'data_quality_', $this::$DataQualityField[2]);
    }

    public function get_Quality_Total_Known_Users(Request $request){
        return $this->dataTableQuery($request, 'data_quality_', $this::$DataQualityField[3]);
    }




}