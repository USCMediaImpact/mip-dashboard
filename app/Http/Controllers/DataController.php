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
        'SCPR' => [
            'date, Duplicated_CameThroughEmailPlusDonors, Unduplicated_TotalUsersKPI, Duplicated_Database_CameThroughEmailPlusDonors, Unduplicated_Database_TotalUsersKPI, IFNULL(Unduplicated_TotalUsersKPI, 0) / IFNULL(Unduplicated_Database_TotalUsersKPI, 0) as Loyal_Users_On_Site',
            'date, CameToSiteThroughEmail, KPI_TotalEmailSubscribersKnownToMIP, IFNULL(CameToSiteThroughEmail, 0) / IFNULL(KPI_TotalEmailSubscribersKnownToMIP, 0) as KPI_PercentKnownSubsWhoCame, NewEmailSubscribers',
            'date, TotalDonorsThisWeek, KPI_TotalDonorsKnownToMIP, IFNULL(TotalDonorsThisWeek, 0) / IFNULL(KPI_TotalDonorsKnownToMIP, 0) as Donors_In_MIP',
        ],
        'TT' => [
            'date, Duplicated_MembersPlusCameThroughEmailPlusDonors, Unduplicated_TotalUsersKPI, Duplicated_Database_MembersPlusCameThroughEmailPlusDonors, Unduplicated_Database_TotalUsersKPI, IFNULL(Unduplicated_TotalUsersKPI, 0) / IFNULL(Unduplicated_Database_TotalUsersKPI, 0) as Loyal_Users_On_Site',
            'date, CameToSiteThroughEmail, KPI_TotalEmailSubscribersKnownToMIP, IFNULL(CameToSiteThroughEmail, 0) / IFNULL(KPI_TotalEmailSubscribersKnownToMIP, 0) as KPI_PercentKnownSubsWhoCame, KPI_NewEmailSubscribers',
            'date, TotalDonorsThisWeek, KPI_TotalDonorsKnownToMIP, IFNULL(TotalDonorsThisWeek, 0) / IFNULL(KPI_TotalDonorsKnownToMIP, 0) as Donors_In_MIP',
            'date, TotalMembersThisWeek, KPI_TotalMembersKnownToMIP, IFNULL(TotalMembersThisWeek, 0) / IFNULL(KPI_TotalMembersKnownToMIP, 0) as Members_In_MIP'
        ],
        'WW' => [
            'date, Duplicated_MembersPlusCameThroughEmailPlusDonors, Unduplicated_TotalUsersKPI, Duplicated_Database_MembersPlusCameThroughEmailPlusDonors, Unduplicated_Database_TotalUsersKPI, IFNULL(Unduplicated_TotalUsersKPI, 0) / IFNULL(Unduplicated_Database_TotalUsersKPI, 0) as Loyal_Users_On_Site',
            'date, CameToSiteThroughEmail, KPI_TotalEmailSubscribersKnownToMIP, IFNULL(CameToSiteThroughEmail, 0) / IFNULL(KPI_TotalEmailSubscribersKnownToMIP, 0) as KPI_PercentKnownSubsWhoCame, KPI_NewEmailSubscribers',
            'date, TotalDonorsThisWeek, KPI_TotalDonorsKnownToMIP, IFNULL(TotalDonorsThisWeek, 0) / IFNULL(KPI_TotalDonorsKnownToMIP, 0) as Donors_In_MIP',
            'date, TotalMembersThisWeek, KPI_TotalMembersKnownToMIP, IFNULL(TotalMembersThisWeek, 0) / IFNULL(KPI_TotalMembersKnownToMIP, 0) as Members_In_MIP'
        ]
    ];

    private static $DataUsersColumn = [
        'SCPR' => [
            ['Week of', 'Email Subscribers and Donors on Site', 'Email Subscribers or Donors on Site or Both Email Subscriber and Donor', 'Email Subscribers and Donors in MIP DB', 'Emails Subscribers or Donors or Both Email Subscriber and Donor in DB', '% of Loyal Users on Site'],
            ['Week of', '(Eloqua) Email Subscribers on Site', 'Total Email Subscribers in MIP DB', '% of Email Subscribers in MIP DB on Site', 'New Email Subscribers'],
            ['Week of', 'Donors Donating', 'Donors in MIP DB', '% of Donors in MIP DB Donating'],
        ],
        'TT' => [
            ['Week of', 'Email Subscribers and Donors on Site', 'Email Subscribers or Donors on Site or Both Email Subscriber and Donor', 'Email Subscribers and Donors in MIP DB', 'Emails Subscribers or Donors or Both Email Subscriber and Donor in DB', '% of Loyal Users on Site'],
            ['Week of', '(Eloqua) Email Subscribers on Site', 'Total Email Subscribers in MIP DB', '% of Email Subscribers in MIP DB on Site', 'New Email Subscribers'],
            ['Week of', 'Donors Donating', 'Donors in MIP DB', '% of Donors in MIP DB Donating'],
            ['Week of', 'Known members on the site THIS WEEK', 'Known: Total known members in the MIP database', 'Known: Percent of members in the MIP database who logged in this week']
        ],
        'WW' => [
            ['Week of', 'Email Subscribers and Donors on Site', 'Email Subscribers or Donors on Site or Both Email Subscriber and Donor', 'Email Subscribers and Donors in MIP DB', 'Emails Subscribers or Donors or Both Email Subscriber and Donor in DB', '% of Loyal Users on Site'],
            ['Week of', '(Eloqua) Email Subscribers on Site', 'Total Email Subscribers in MIP DB', '% of Email Subscribers in MIP DB on Site', 'New Email Subscribers'],
            ['Week of', 'Donors Donating', 'Donors in MIP DB', '% of Donors in MIP DB Donating'],
            ['Week of', 'Known members on the site THIS WEEK', 'Known: Total known members in the MIP database', 'Known: Percent of members in the MIP database who logged in this week']
        ]
    ];

    public function showUsers(Request $request){
        $client_id = $request['client']['id'];
        $client_code = $request['client']['code'];
        $group = array_key_exists($request['group'], self::$groupDisplay) ? $request['group'] : 'weekly';
        $isSuperAdmin = $request['isSuperAdmin'];
        if($isSuperAdmin){
            $query = DB::table($client_code . '_data_users_' . $group);
        }else {
            $query = DB::table($client_code . '_data_users_' . $group)->where('ready', 1);
        }

        $count = $query->count();
        $date_range_min = strtotime($query->min('date'));
        $date_range_max = strtotime($query->max('date'));
        $date_range_max = strtotime('6 days', $date_range_max);
        $max_date = $date_range_max;
        $min_date = strtotime('-27 days', $max_date);

        return view('data.' . $client_code . '.users', [
            'have_data' => $count > 0,
            'min_date' => $min_date,
            'max_date' => $max_date,
            'date_range_min' => date('Y-m-d', $date_range_min),
            'date_range_max' => date('Y-m-d', $date_range_max),
            'group' => $group,
            'displayGroupName' => self::$groupDisplay[$group],
            'default_date_range' => date('M d, Y', $min_date). ' - ' . date('M d, Y', $max_date)
        ]);
    }

    public function get_Users_Total_Known_Users(Request $request){
        $client_code = $request['client']['code'];
        return $this->dataTableQuery($request,
            $client_code.'_data_users_',
            $this::$DataUsersField[$client_code][0]);
    }

    public function download_Users_Total_Known_Users(Request $request){
        $client_code = $request['client']['code'];
        return $this->exportCSV($request,
            $client_code.'_data_users_',
            $this::$DataUsersField[$client_code][0],
            $this::$DataUsersColumn[$client_code][0],
            'Total Known Users');
    }

    public function download_All_Users_Total_Known_Users(Request $request){
        $client_code = $request['client']['code'];
        return $this->exportCSV($request,
            $client_code.'_data_users_',
            $this::$DataUsersField[$client_code][3],
            $this::$DataUsersColumn[$client_code][3],
            'Total Known Users');
    }

    public function get_Users_Email_Newsletter_Subscribers(Request $request){
        $client_code = $request['client']['code'];
        return $this->dataTableQuery($request, $client_code.'_data_users_', $this::$DataUsersField[$client_code][1]);
    }

    public function download_Users_Email_Newsletter_Subscribers(Request $request){
        $client_code = $request['client']['code'];
        return $this->exportCSV($request,
            $client_code.'_data_users_',
            $this::$DataUsersField[$client_code][1],
            $this::$DataUsersColumn[$client_code][1],
            'Email Newsletter Subscribers');
    }

    public function download_All_Users_Email_Newsletter_Subscribers(Request $request){
        $client_code = $request['client']['code'];
        return $this->exportCSV($request,
            $client_code.'_data_users_',
            $this::$DataUsersField[$client_code][1],
            $this::$DataUsersColumn[$client_code][1],
            'Email Newsletter Subscribers');
    }

    public function get_Users_Donors(Request $request){
        $client_code = $request['client']['code'];
        return $this->dataTableQuery($request,
            $client_code.'_data_users_',
            $this::$DataUsersField[$client_code][2]);
    }

    public function download_Users_Donors(Request $request){
        $client_code = $request['client']['code'];
        return $this->exportCSV($request,
            $client_code.'_data_users_',
            $this::$DataUsersField[$client_code][2],
            $this::$DataUsersColumn[$client_code][2],
            'Donors');
    }

    public function download_All_Users_Donors(Request $request){
        $client_code = $request['client']['code'];
        return $this->exportCSV($request,
            $client_code.'_data_users_',
            $this::$DataUsersField[$client_code][2],
            $this::$DataUsersColumn[$client_code][2],
            'Donors');
    }

    public function get_Users_Members(Request $request){
        $client_code = $request['client']['code'];
        return $this->dataTableQuery($request,
            $client_code.'_data_users_',
            $this::$DataUsersField[$client_code][3]);
    }

    public function download_Users_Members(Request $request){
        $client_code = $request['client']['code'];
        return $this->exportCSV($request,
            $client_code.'_data_users_',
            $this::$DataUsersField[$client_code][3],
            $this::$DataUsersColumn[$client_code][3],
            'Members');
    }

    public function download_All_Users_Members(Request $request){
        $client_code = $request['client']['code'];
        return $this->exportCSV($request,
            $client_code.'_data_users_',
            $this::$DataUsersField[$client_code][3],
            $this::$DataUsersColumn[$client_code][3],
            'Members');
    }

    public function showDonations(Request $request){
        $group = array_key_exists($request['group'], self::$groupDisplay) ? $request['group'] : 'weekly';
        $max_date = date_parse($request['max_date'] ?: date('Y-m-d', time()));
        $min_date = date_parse($request['min_date'] ?: date('Y-m-1', time()));
        $client_id = $request['client']['id'];
        $client_code = $request['client']['code'];
        $isSuperAdmin = $request['isSuperAdmin'];
        if($isSuperAdmin){
            $query = DB::table($client_code . '_data_donations_' . $group);
        }else {
            $query = DB::table($client_code . '_data_donations_' . $group)->where('ready', 1);
        }

        $count = $query->count();
        $date_range_min = $query->min('date');
        $date_range_max = $query->max('date');

        return view('data.' . $client_code . '.users', [
            'have_data' => $count > 0,
            'min_date' => mktime(0, 0, 0, $min_date['month'], $min_date['day'], $min_date['year']),
            'max_date' => mktime(0, 0, 0, $max_date['month'], $max_date['day'], $max_date['year']),
            'date_range_min' => $date_range_min,
            'date_range_max' => $date_range_max,
            'group' => $group,
            'displayGroupName' => self::$groupDisplay[$group]
        ]);
    }

    private static $DataStoriesField = [
        'SCPR' =>[
            'Page_Path, IFNULL(Article, Page_Path) AS Article, Pageviews, IFNULL(Scroll_Start, 0) / Pageviews as StartedScrolling, IFNULL(Scroll_25, 0) / IFNULL(Pageviews, 0) as Scroll25, IFNULL(Scroll_50, 0) / IFNULL(Pageviews, 0) as Scroll50, IFNULL(Scroll_75, 0) / IFNULL(Pageviews, 0) as Scroll75, IFNULL(Scroll_100, 0) / IFNULL(Pageviews, 0) as Scroll100, IFNULL(Scroll_Supplemental, 0) / IFNULL(Pageviews, 0) as RelatedContent, IFNULL(Scroll_End, 0) / IFNULL(Pageviews, 0) as EndOfPage',
            'Page_Path, IFNULL(Article, Page_Path) AS Article, Pageviews, Scroll_Start as StartedScrolling, Scroll_25 as Scroll25, Scroll_50 as Scroll50, Scroll_75 as Scroll75, Scroll_100 as Scroll100, Scroll_Supplemental as RelatedContent, Scroll_End as EndOfPage',
            'Page_Path, IFNULL(Article, Page_Path) AS Article, Pageviews, IFNULL(Time_15, 0) / IFNULL(Pageviews, 0) as Time15, IFNULL(Time_30, 0) / IFNULL(Pageviews, 0) as Time30, IFNULL(Time_45, 0) / IFNULL(Pageviews, 0) as Time45, IFNULL(Time_60, 0) / IFNULL(Pageviews, 0) as Time60, IFNULL(Time_75, 0) / IFNULL(Pageviews, 0) as Time75, IFNULL(Time_90, 0) / IFNULL(Pageviews, 0) as Time90',
            'Page_Path, IFNULL(Article, Page_Path) AS Article, Pageviews, Time_15 as Time15, Time_30 as Time30, Time_45 as Time45, Time_60 as Time60, Time_75 as Time75, Time_90 as Time90',
            'Page_Path, IFNULL(Article, Page_Path) AS Article, Pageviews, Comments, Emails, Tweets, Facebook_Recommendations, IFNULL(Comments, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0) as TotalShares, (IFNULL(Comments, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0)) / IFNULL(Pageviews, 0) as SahreRate, Related_Clicks, IFNULL(Related_Clicks, 0) / IFNULL(Scroll_Supplemental, 0) as ClickThroughRate',
        ],
        'TT' => [
            'Combo_URL, IFNULL(Article, Combo_URL) AS Article, Pageviews, IFNULL(Scroll_Start, 0) / IFNULL(Pageviews, 0) as StartedScrolling, IFNULL(Scroll_25, 0) / IFNULL(Pageviews, 0) as Scroll25, IFNULL(Scroll_50, 0) / IFNULL(Pageviews, 0) as Scroll50, IFNULL(Scroll_75, 0) / IFNULL(Pageviews, 0) as Scroll75, IFNULL(Scroll_100, 0) / IFNULL(Pageviews, 0) as Scroll100, IFNULL(Scroll_Supplemental, 0) / IFNULL(Pageviews, 0) as RelatedContent, IFNULL(Scroll_End, 0) / IFNULL(Pageviews, 0) as EndOfPage',
            'Combo_URL, IFNULL(Article, Combo_URL) AS Article, Pageviews, Scroll_Start as StartedScrolling, Scroll_25 as Scroll25, Scroll_50 as Scroll50, Scroll_75 as Scroll75, Scroll_100 as Scroll100, Scroll_Supplemental as RelatedContent, Scroll_End as EndOfPage',
            'Combo_URL, IFNULL(Article, Combo_URL) AS Article, Pageviews, IFNULL(Time_15, 0) / IFNULL(Pageviews, 0) as Time15, IFNULL(Time_30, 0) / IFNULL(Pageviews, 0) as Time30, IFNULL(Time_45, 0) / IFNULL(Pageviews, 0) as Time45, IFNULL(Time_60, 0) / IFNULL(Pageviews, 0) as Time60, IFNULL(Time_75, 0) / IFNULL(Pageviews, 0) as Time75, IFNULL(Time_90, 0) / IFNULL(Pageviews, 0) as Time90',
            'Combo_URL, IFNULL(Article, Combo_URL) AS Article, Pageviews, Time_15 as Time15, Time_30 as Time30, Time_45 as Time45, Time_60 as Time60, Time_75 as Time75, Time_90 as Time90',
            'Combo_URL, IFNULL(Article, Combo_URL) AS Article, Pageviews, Comments, Republish, Emails, Tweets, Facebook_Recommendations, IFNULL(Comments, 0) + IFNULL(Republish, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0) as TotalShares, (IFNULL(Comments, 0) + IFNULL(Republish, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0)) / IFNULL(Pageviews, 0) as SahreRate, Tribpedia_Related_Clicks, Related_Clicks, IFNULL(Related_Clicks, 0) + IFNULL(Tribpedia_Related_Clicks, 0) as Total_Related_Clicks, (IFNULL(Related_Clicks, 0) + IFNULL(Tribpedia_Related_Clicks, 0)) / IFNULL(Scroll_Supplemental, 0) as ClickThroughRate'
        ],
        'WW' => [
            'Combo_URL, IFNULL(Article, Combo_URL) AS Article, Pageviews, IFNULL(Scroll_Start, 0) / IFNULL(Pageviews, 0) as StartedScrolling, IFNULL(Scroll_25, 0) / IFNULL(Pageviews, 0) as Scroll25, IFNULL(Scroll_50, 0) / IFNULL(Pageviews, 0) as Scroll50, IFNULL(Scroll_75, 0) / IFNULL(Pageviews, 0) as Scroll75, IFNULL(Scroll_100, 0) / IFNULL(Pageviews, 0) as Scroll100, IFNULL(Scroll_Supplemental, 0) / IFNULL(Pageviews, 0) as RelatedContent, IFNULL(Scroll_End, 0) / IFNULL(Pageviews, 0) as EndOfPage',
            'Combo_URL, IFNULL(Article, Combo_URL) AS Article, Pageviews, Scroll_Start as StartedScrolling, Scroll_25 as Scroll25, Scroll_50 as Scroll50, Scroll_75 as Scroll75, Scroll_100 as Scroll100, Scroll_Supplemental as RelatedContent, Scroll_End as EndOfPage',
            'Combo_URL, IFNULL(Article, Combo_URL) AS Article, Pageviews, IFNULL(Time_15, 0) / IFNULL(Pageviews, 0) as Time15, IFNULL(Time_30, 0) / IFNULL(Pageviews, 0) as Time30, IFNULL(Time_45, 0) / IFNULL(Pageviews, 0) as Time45, IFNULL(Time_60, 0) / IFNULL(Pageviews, 0) as Time60, IFNULL(Time_75, 0) / IFNULL(Pageviews, 0) as Time75, IFNULL(Time_90, 0) / IFNULL(Pageviews, 0) as Time90',
            'Combo_URL, IFNULL(Article, Combo_URL) AS Article, Pageviews, Time_15 as Time15, Time_30 as Time30, Time_45 as Time45, Time_60 as Time60, Time_75 as Time75, Time_90 as Time90',
            'Combo_URL, IFNULL(Article, Combo_URL) AS Article, Pageviews, Comments, Republish, Emails, Tweets, Facebook_Recommendations, IFNULL(Comments, 0) + IFNULL(Republish, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0) as TotalShares, (IFNULL(Comments, 0) + IFNULL(Republish, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0)) / IFNULL(Pageviews, 0) as SahreRate, Tribpedia_Related_Clicks, Related_Clicks, IFNULL(Related_Clicks, 0) + IFNULL(Tribpedia_Related_Clicks, 0) as Total_Related_Clicks, (IFNULL(Related_Clicks, 0) + IFNULL(Tribpedia_Related_Clicks, 0)) / IFNULL(Scroll_Supplemental, 0) as ClickThroughRate'
        ],
    ];

    private static $DataStoriesExportField = [
        'SCPR' => [
            'IFNULL(Article, Page_Path) AS Article, Page_Path, Pageviews, IFNULL(Scroll_Start, 0) / IFNULL(Pageviews, 0) as StartedScrolling, IFNULL(Scroll_25, 0) / IFNULL(Pageviews, 0) as Scroll25, IFNULL(Scroll_50, 0) / IFNULL(Pageviews, 0) as Scroll50, IFNULL(Scroll_75, 0) / IFNULL(Pageviews, 0) as Scroll75, IFNULL(Scroll_100, 0) / IFNULL(Pageviews, 0) as Scroll100, IFNULL(Scroll_Supplemental, 0) / IFNULL(Pageviews, 0) as RelatedContent, IFNULL(Scroll_End, 0) / IFNULL(Pageviews, 0) as EndOfPage',
            'IFNULL(Article, Page_Path) AS Article, Page_Path, Pageviews, Scroll_Start as StartedScrolling, Scroll_25 as Scroll25, Scroll_50 as Scroll50, Scroll_75 as Scroll75, Scroll_100 as Scroll100, Scroll_Supplemental as RelatedContent, Scroll_End as EndOfPage',
            'IFNULL(Article, Page_Path) AS Article, Page_Path, Pageviews, IFNULL(Time_15, 0) / IFNULL(Pageviews, 0) as Time15, IFNULL(Time_30, 0) / IFNULL(Pageviews, 0) as Time30, IFNULL(Time_45, 0) / IFNULL(Pageviews, 0) as Time45, IFNULL(Time_60, 0) / IFNULL(Pageviews, 0) as Time60, IFNULL(Time_75, 0) / IFNULL(Pageviews, 0) as Time75, IFNULL(Time_90, 0) / IFNULL(Pageviews, 0) as Time90',
            'IFNULL(Article, Page_Path) AS Article, Page_Path, Pageviews, Time_15 as Time15, Time_30 as Time30, Time_45 as Time45, Time_60 as Time60, Time_75 as Time75, Time_90 as Time90',
            'IFNULL(Article, Page_Path) AS Article, Page_Path, Pageviews, Comments, Emails, Tweets, Facebook_Recommendations, IFNULL(Comments, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0) as TotalShares, (IFNULL(Comments, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0)) / IFNULL(Pageviews, 0) as SahreRate, Related_Clicks, IFNULL(Related_Clicks, 0) / IFNULL(Scroll_Supplemental, 0) as ClickThroughRate',
            'IFNULL(Article, Page_Path) AS Article, Page_Path, Pageviews, IFNULL(Scroll_Start, 0) / IFNULL(Pageviews, 0) as StartedScrolling, IFNULL(Scroll_25, 0) / IFNULL(Pageviews, 0) as Scroll25, IFNULL(Scroll_50, 0) / IFNULL(Pageviews, 0) as Scroll50, IFNULL(Scroll_75, 0) / IFNULL(Pageviews, 0) as Scroll75, IFNULL(Scroll_100, 0) / IFNULL(Pageviews, 0) as Scroll100, IFNULL(Scroll_Supplemental, 0) / IFNULL(Pageviews, 0) as RelatedContent, IFNULL(Scroll_End, 0) / IFNULL(Pageviews, 0) as EndOfPage, IFNULL(Time_15, 0) / IFNULL(Pageviews, 0) as Time15, IFNULL(Time_30, 0) / IFNULL(Pageviews, 0) as Time30, IFNULL(Time_45, 0) / IFNULL(Pageviews, 0) as Time45, IFNULL(Time_60, 0) / IFNULL(Pageviews, 0) as Time60, IFNULL(Time_75, 0) / IFNULL(Pageviews, 0) as Time75, IFNULL(Time_90, 0) / IFNULL(Pageviews, 0) as Time90, Comments, Emails, Tweets, Facebook_Recommendations, IFNULL(Comments, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0) as TotalShares, (IFNULL(Comments, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0)) / IFNULL(Pageviews, 0) as SahreRate, Related_Clicks, IFNULL(Related_Clicks, 0) / IFNULL(Scroll_Supplemental, 0) as ClickThroughRate',
            'IFNULL(Article, Page_Path) AS Article, Page_Path, Pageviews, Scroll_Start as StartedScrolling, Scroll_25 as Scroll25, Scroll_50 as Scroll50, Scroll_75 as Scroll75, Scroll_100 as Scroll100, Scroll_Supplemental as RelatedContent, Scroll_End as EndOfPage, Time_15 as Time15, Time_30 as Time30, Time_45 as Time45, Time_60 as Time60, Time_75 as Time75, Time_90 as Time90, Comments, Emails, Tweets, Facebook_Recommendations, IFNULL(Comments, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0) as TotalShares, (IFNULL(Comments, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0)) / IFNULL(Pageviews, 0) as SahreRate, Related_Clicks, IFNULL(Related_Clicks, 0) / IFNULL(Scroll_Supplemental, 0) as ClickThroughRate',
        ],
        'TT' => [
            'IFNULL(Article, Combo_URL) AS Article, Combo_URL, Pageviews, IFNULL(Scroll_Start, 0) / IFNULL(Pageviews, 0) as StartedScrolling, IFNULL(Scroll_25, 0) / IFNULL(Pageviews, 0) as Scroll25, IFNULL(Scroll_50, 0) / IFNULL(Pageviews, 0) as Scroll50, IFNULL(Scroll_75, 0) / IFNULL(Pageviews, 0) as Scroll75, IFNULL(Scroll_100, 0) / IFNULL(Pageviews, 0) as Scroll100, IFNULL(Scroll_Supplemental, 0) / IFNULL(Pageviews, 0) as RelatedContent, IFNULL(Scroll_End, 0) / IFNULL(Pageviews, 0) as EndOfPage',
            'IFNULL(Article, Combo_URL) AS Article, Combo_URL, Pageviews, Scroll_Start as StartedScrolling, Scroll_25 as Scroll25, Scroll_50 as Scroll50, Scroll_75 as Scroll75, Scroll_100 as Scroll100, Scroll_Supplemental as RelatedContent, Scroll_End as EndOfPage',
            'IFNULL(Article, Combo_URL) AS Article, Combo_URL, Pageviews, IFNULL(Time_15, 0) / IFNULL(Pageviews, 0) as Time15, IFNULL(Time_30, 0) / IFNULL(Pageviews, 0) as Time30, IFNULL(Time_45, 0) / IFNULL(Pageviews, 0) as Time45, IFNULL(Time_60, 0) / IFNULL(Pageviews, 0) as Time60, IFNULL(Time_75, 0) / IFNULL(Pageviews, 0) as Time75, IFNULL(Time_90, 0) / IFNULL(Pageviews, 0) as Time90',
            'IFNULL(Article, Combo_URL) AS Article, Combo_URL, Pageviews, Time_15 as Time15, Time_30 as Time30, Time_45 as Time45, Time_60 as Time60, Time_75 as Time75, Time_90 as Time90',
            'IFNULL(Article, Combo_URL) AS Article, Combo_URL, Pageviews, Comments, Republish, Emails, Tweets, Facebook_Recommendations, IFNULL(Comments, 0) + IFNULL(Republish, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0) as TotalShares, (IFNULL(Comments, 0) + IFNULL(Republish, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0)) / IFNULL(Pageviews, 0) as SahreRate, Tribpedia_Related_Clicks, Related_Clicks, IFNULL(Related_Clicks, 0) + IFNULL(Tribpedia_Related_Clicks, 0) as Total_Related_Clicks, (IFNULL(Related_Clicks, 0) + IFNULL(Tribpedia_Related_Clicks, 0)) / IFNULL(Scroll_Supplemental, 0) as ClickThroughRate',
            'IFNULL(Article, Combo_URL) AS Article, Combo_URL, Pageviews, IFNULL(Scroll_Start, 0) / IFNULL(Pageviews, 0) as StartedScrolling, IFNULL(Scroll_25, 0) / IFNULL(Pageviews, 0) as Scroll25, IFNULL(Scroll_50, 0) / IFNULL(Pageviews, 0) as Scroll50, IFNULL(Scroll_75, 0) / IFNULL(Pageviews, 0) as Scroll75, IFNULL(Scroll_100, 0) / IFNULL(Pageviews, 0) as Scroll100, IFNULL(Scroll_Supplemental, 0) / IFNULL(Pageviews, 0) as RelatedContent, IFNULL(Scroll_End, 0) / IFNULL(Pageviews, 0) as EndOfPage, IFNULL(Time_15, 0) / IFNULL(Pageviews, 0) as Time15, IFNULL(Time_30, 0) / IFNULL(Pageviews, 0) as Time30, IFNULL(Time_45, 0) / IFNULL(Pageviews, 0) as Time45, IFNULL(Time_60, 0) / IFNULL(Pageviews, 0) as Time60, IFNULL(Time_75, 0) / IFNULL(Pageviews, 0) as Time75, IFNULL(Time_90, 0) / IFNULL(Pageviews, 0) as Time90, Comments, Republish, Emails, Tweets, Facebook_Recommendations, IFNULL(Comments, 0) + IFNULL(Republish, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0) as TotalShares, (IFNULL(Comments, 0) + IFNULL(Republish, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0)) / IFNULL(Pageviews, 0) as SahreRate, Tribpedia_Related_Clicks, Related_Clicks, IFNULL(Related_Clicks, 0) + IFNULL(Tribpedia_Related_Clicks, 0) as Total_Related_Clicks, (IFNULL(Related_Clicks, 0) + IFNULL(Tribpedia_Related_Clicks, 0)) / IFNULL(Scroll_Supplemental, 0) as ClickThroughRate',
            'IFNULL(Article, Combo_URL) AS Article, Combo_URL, Pageviews, Scroll_Start as StartedScrolling, Scroll_25 as Scroll25, Scroll_50 as Scroll50, Scroll_75 as Scroll75, Scroll_100 as Scroll100, Scroll_Supplemental as RelatedContent, Scroll_End as EndOfPage, Time_15 as Time15, Time_30 as Time30, Time_45 as Time45, Time_60 as Time60, Time_75 as Time75, Time_90 as Time90, Comments, Republish, Emails, Tweets, Facebook_Recommendations, IFNULL(Comments, 0) + IFNULL(Republish, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0) as TotalShares, (IFNULL(Comments, 0) + IFNULL(Republish, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0)) / IFNULL(Pageviews, 0) as SahreRate, Tribpedia_Related_Clicks, Related_Clicks, IFNULL(Related_Clicks, 0) + IFNULL(Tribpedia_Related_Clicks, 0) as Total_Related_Clicks, (IFNULL(Related_Clicks, 0) + IFNULL(Tribpedia_Related_Clicks, 0)) / IFNULL(Scroll_Supplemental, 0) as ClickThroughRate'
        ],
        'WW' => [
            'IFNULL(Article, Combo_URL) AS Article, Combo_URL, Pageviews, IFNULL(Scroll_Start, 0) / IFNULL(Pageviews, 0) as StartedScrolling, IFNULL(Scroll_25, 0) / IFNULL(Pageviews, 0) as Scroll25, IFNULL(Scroll_50, 0) / IFNULL(Pageviews, 0) as Scroll50, IFNULL(Scroll_75, 0) / IFNULL(Pageviews, 0) as Scroll75, IFNULL(Scroll_100, 0) / IFNULL(Pageviews, 0) as Scroll100, IFNULL(Scroll_Supplemental, 0) / IFNULL(Pageviews, 0) as RelatedContent, IFNULL(Scroll_End, 0) / IFNULL(Pageviews, 0) as EndOfPage',
            'IFNULL(Article, Combo_URL) AS Article, Combo_URL, Pageviews, Scroll_Start as StartedScrolling, Scroll_25 as Scroll25, Scroll_50 as Scroll50, Scroll_75 as Scroll75, Scroll_100 as Scroll100, Scroll_Supplemental as RelatedContent, Scroll_End as EndOfPage',
            'IFNULL(Article, Combo_URL) AS Article, Combo_URL, Pageviews, IFNULL(Time_15, 0) / IFNULL(Pageviews, 0) as Time15, IFNULL(Time_30, 0) / IFNULL(Pageviews, 0) as Time30, IFNULL(Time_45, 0) / IFNULL(Pageviews, 0) as Time45, IFNULL(Time_60, 0) / IFNULL(Pageviews, 0) as Time60, IFNULL(Time_75, 0) / IFNULL(Pageviews, 0) as Time75, IFNULL(Time_90, 0) / IFNULL(Pageviews, 0) as Time90',
            'IFNULL(Article, Combo_URL) AS Article, Combo_URL, Pageviews, Time_15 as Time15, Time_30 as Time30, Time_45 as Time45, Time_60 as Time60, Time_75 as Time75, Time_90 as Time90',
            'IFNULL(Article, Combo_URL) AS Article, Combo_URL, Pageviews, Comments, Republish, Emails, Tweets, Facebook_Recommendations, IFNULL(Comments, 0) + IFNULL(Republish, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0) as TotalShares, (IFNULL(Comments, 0) + IFNULL(Republish, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0)) / IFNULL(Pageviews, 0) as SahreRate, Related_Clicks, IFNULL(Related_Clicks, 0) as Total_Related_Clicks, (IFNULL(Related_Clicks, 0)) / IFNULL(Scroll_Supplemental, 0) as ClickThroughRate',
            'IFNULL(Article, Combo_URL) AS Article, Combo_URL, Pageviews, IFNULL(Scroll_Start, 0) / IFNULL(Pageviews, 0) as StartedScrolling, IFNULL(Scroll_25, 0) / IFNULL(Pageviews, 0) as Scroll25, IFNULL(Scroll_50, 0) / IFNULL(Pageviews, 0) as Scroll50, IFNULL(Scroll_75, 0) / IFNULL(Pageviews, 0) as Scroll75, IFNULL(Scroll_100, 0) / IFNULL(Pageviews, 0) as Scroll100, IFNULL(Scroll_Supplemental, 0) / IFNULL(Pageviews, 0) as RelatedContent, IFNULL(Scroll_End, 0) / IFNULL(Pageviews, 0) as EndOfPage, IFNULL(Time_15, 0) / IFNULL(Pageviews, 0) as Time15, IFNULL(Time_30, 0) / IFNULL(Pageviews, 0) as Time30, IFNULL(Time_45, 0) / IFNULL(Pageviews, 0) as Time45, IFNULL(Time_60, 0) / IFNULL(Pageviews, 0) as Time60, IFNULL(Time_75, 0) / IFNULL(Pageviews, 0) as Time75, IFNULL(Time_90, 0) / IFNULL(Pageviews, 0) as Time90, Comments, Republish, Emails, Tweets, Facebook_Recommendations, IFNULL(Comments, 0) + IFNULL(Republish, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0) as TotalShares, (IFNULL(Comments, 0) + IFNULL(Republish, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0)) / IFNULL(Pageviews, 0) as SahreRate, Related_Clicks, IFNULL(Related_Clicks, 0) as Total_Related_Clicks, (IFNULL(Related_Clicks, 0)) / IFNULL(Scroll_Supplemental, 0) as ClickThroughRate',
            'IFNULL(Article, Combo_URL) AS Article, Combo_URL, Pageviews, Scroll_Start as StartedScrolling, Scroll_25 as Scroll25, Scroll_50 as Scroll50, Scroll_75 as Scroll75, Scroll_100 as Scroll100, Scroll_Supplemental as RelatedContent, Scroll_End as EndOfPage, Time_15 as Time15, Time_30 as Time30, Time_45 as Time45, Time_60 as Time60, Time_75 as Time75, Time_90 as Time90, Comments, Republish, Emails, Tweets, Facebook_Recommendations, IFNULL(Comments, 0) + IFNULL(Republish, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0) as TotalShares, (IFNULL(Comments, 0) + IFNULL(Republish, 0) + IFNULL(Emails, 0) + IFNULL(Tweets, 0) + IFNULL(Facebook_Recommendations, 0)) / IFNULL(Pageviews, 0) as SahreRate, Related_Clicks, IFNULL(Related_Clicks, 0) as Total_Related_Clicks, (IFNULL(Related_Clicks, 0)) / IFNULL(Scroll_Supplemental, 0) as ClickThroughRate'
        ],
    ];

    private static $DataStoriesColumn = [
        'SCPR' => [
            ['Article Title', 'Page Path', 'Total Page Views', 'Started Scrolling', '25% Scroll', '50% Scroll', '75% Scroll', '100% Scroll', 'Related Content', 'End of Page'],
            ['Article Title', 'Page Path', 'Total Page Views', '15 Seconds', '30 Seconds', '45 Seconds', '60 Seconds', '75 Seconds', '90 Seconds'],
            ['Article Title', 'Page Path', 'Total Page Views', 'Comments', 'Email Shares', 'Tweets', 'FB Shares', 'Total Shares', 'Share Rate', 'Related Content Clicks', 'Click Through Rate'],
            ['Article Title', 'Page Path', 'Total Page Views', 'Started Scrolling', '25% Scroll', '50% Scroll', '75% Scroll', '100% Scroll', 'Related Content', 'End of Page', '15 Seconds', '30 Seconds', '45 Seconds', '60 Seconds', '75 Seconds', '90 Seconds', 'Comments', 'Email Shares', 'Tweets', 'FB Shares', 'Total Shares', 'Share Rate', 'Related Content Clicks', 'Click Through Rate']
        ],
        'TT' => [
            ['Article Title', 'Page Path', 'Total Page Views', 'Started Scrolling', '25% Scroll', '50% Scroll', '75% Scroll', '100% Scroll', 'Related Content', 'End of Page'],
            ['Article Title', 'Page Path', 'Total Page Views', '15 Seconds', '30 Seconds', '45 Seconds', '60 Seconds', '75 Seconds', '90 Seconds'],
            ['Article Title', 'Page Path', 'Total Page Views', 'Comments', 'Republish', 'Email Shares', 'Tweets', 'FB Shares', 'Total Shares', 'Share Rate', 'Tribpedia Clicks', 'Related Content Clicks', 'Total Related Clicks', 'Click Through Rate'],
            ['Article Title', 'Page Path', 'Total Page Views', 'Started Scrolling', '25% Scroll', '50% Scroll', '75% Scroll', '100% Scroll', 'Related Content', 'End of Page', '15 Seconds', '30 Seconds', '45 Seconds', '60 Seconds', '75 Seconds', '90 Seconds', 'Comments', 'Republish', 'Email Shares', 'Tweets', 'FB Shares', 'Total Shares', 'Share Rate', 'Tribpedia Clicks', 'Related Content Clicks', 'Total Related Clicks', 'Click Through Rate']
        ],
        'WW' => [
            ['Article Title', 'Page Path', 'Total Page Views', 'Started Scrolling', '25% Scroll', '50% Scroll', '75% Scroll', '100% Scroll', 'Related Content', 'End of Page'],
            ['Article Title', 'Page Path', 'Total Page Views', '15 Seconds', '30 Seconds', '45 Seconds', '60 Seconds', '75 Seconds', '90 Seconds'],
            ['Article Title', 'Page Path', 'Total Page Views', 'Comments', 'Republish', 'Email Shares', 'Tweets', 'FB Shares', 'Total Shares', 'Share Rate', 'Related Content Clicks', 'Total Related Clicks', 'Click Through Rate'],
            ['Article Title', 'Page Path', 'Total Page Views', 'Started Scrolling', '25% Scroll', '50% Scroll', '75% Scroll', '100% Scroll', 'Related Content', 'End of Page', '15 Seconds', '30 Seconds', '45 Seconds', '60 Seconds', '75 Seconds', '90 Seconds', 'Comments', 'Republish', 'Email Shares', 'Tweets', 'FB Shares', 'Total Shares', 'Share Rate', 'Related Content Clicks', 'Total Related Clicks', 'Click Through Rate']
        ],
    ];

    public function showStories(Request $request){
        $client_id = $request['client']['id'];
        $client_code = $request['client']['code'];
        $group = array_key_exists($request['group'], self::$groupDisplay) ? $request['group'] : 'weekly';
        $isSuperAdmin = $request['isSuperAdmin'];
        if($isSuperAdmin){
            $query = DB::table($client_code . '_data_stories_top100_' . $group);
        }else {
            $query = DB::table($client_code . '_data_stories_top100_' . $group)->where('ready', 1);
        }

        $count = $query->count();
        $date_range_min = $query->min('date');
        $last_week_begin = strtotime($query->max('date'));
        $last_week_end = strtotime('6 days', $last_week_begin);
        $date_range_max = date('Y-m-d', $last_week_end);

        $max_date = strtotime($request['max_date']) ?: $last_week_end;
        $min_date = strtotime($request['min_date']) ?: $last_week_begin;

        return view('data.' . $client_code . '.stories', [
            'have_data' => $count > 0,
            'website' => $request['client']['website'],
            'min_date' => $min_date,
            'max_date' => $max_date,
            'date_range_min' => $date_range_min,
            'date_range_max' => $date_range_max,
            'group' => $group,
            'displayGroupName' => self::$groupDisplay[$group],
            'default_date_range' => date('m/d/Y', $min_date). ' - ' . date('m/d/Y', $max_date)
        ]);
    }

    public function get_Stories_Scroll_Depth(Request $request, $mode)
    {
        $client_code = $request['client']['code'];
        $index = $mode == 'count' ? 1 : 0;
        return $this->dataTableQuery($request,
            $client_code.'_data_stories_top100_',
            $this::$DataStoriesField[$client_code][$index]);
    }

    public function get_Stories_Time_On_Article(Request $request, $mode)
    {
        $client_code = $request['client']['code'];
        $index = $mode == 'count' ? 3 : 2;
        return $this->dataTableQuery($request,
            $client_code.'_data_stories_top100_',
            $this::$DataStoriesField[$client_code][$index]);
    }

    public function get_Stories_User_Interactions(Request $request)
    {
        $client_code = $request['client']['code'];
        return $this->dataTableQuery($request,
            $client_code.'_data_stories_top100_',
            $this::$DataStoriesField[$client_code][4]);
    }

    private function prepare_scpr_stories($ftarget, $csvPath, $columns){
        $fsource = fopen($csvPath, 'r');
        fprintf($ftarget, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($ftarget, $columns);
        while (($s = fgetcsv($fsource, 1000, ',')) !== FALSE) {
            if(count($s) == 1 && $s[0] == null) {
                continue;
            }
            $row = array_merge(
                array($s[1] ?: $s[0]),
                array($s[0]),
                array_slice($s, 2, 19),
                array($s[16] + $s[17] + $s[18] + $s[19]),
                array($s[2] ? ($s[16] + $s[17] + $s[18] + $s[19]) / $s[2] : ''),
                array($s[20]),
                array($s[8] ? $s[20] / $s[8] : '')
            );

            fputcsv($ftarget, $row);
        }
    }

    private function prepare_tt_stories($ftarget, $csvPath, $columns){
        $fsource = fopen($csvPath, 'r');
        fprintf($ftarget, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($ftarget, $columns);
        while (($s = fgetcsv($fsource, 1000, ',')) !== FALSE) {
            if(count($s) == 1 && $s[0] == null) {
                continue;
            }
            $row = array_merge(
                array($s[1] ?: $s[0]),
                array($s[0]),
                array_slice($s, 2, 19),
                array($s[16] + $s[17] + $s[18] + $s[19] + $s[20]),
                array($s[2] ? ($s[16] + $s[17] + $s[18] + $s[19] + $s[20]) / $s[2] : ''),
                [$s[21], $s[22]],
                array($s[21] + $s[22]),
                array($s[8] ? ($s[21] + $s[22]) / $s[8] : '')
            );

            fputcsv($ftarget, $row);
        }
    }

    private function prepare_ww_stories($ftarget, $csvPath, $columns){
        $fsource = fopen($csvPath, 'r');
        fprintf($ftarget, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($ftarget, $columns);
        while (($s = fgetcsv($fsource, 1000, ',')) !== FALSE) {
            if(count($s) == 1 && $s[0] == null) {
                continue;
            }
            $row = array_merge(
                array($s[1] ?: $s[0]),
                array($s[0]),
                array_slice($s, 2, 19),
                array($s[16] + $s[17] + $s[18] + $s[19] + $s[20]),
                array($s[2] ? ($s[16] + $s[17] + $s[18] + $s[19] + $s[20]) / $s[2] : ''),
                [$s[22]],
                array($s[22]),
                array($s[8] ? $s[22] / $s[8] : '')
            );

            fputcsv($ftarget, $row);
        }
    }

    public function download_All_Stories(Request $request)
    {
        $client_code = $request['client']['code'];
        $index = 6;
        $mode = 'count';
        $min_date = strtotime($request['min_date']);
        $max_date = strtotime($request['max_date']);
        $min_date = date('Y-m-d', $min_date);
        $max_date = date('Y-m-d', $max_date);
        $columns = $this::$DataStoriesColumn[$client_code][3];
        $bucket = 'mip-stories-stage';
        $csvPath = "gs://${bucket}/${client_code}/${min_date}.csv";

        if (file_exists($csvPath)) {
            return $this->responseFile(function ($ftarget) use ($csvPath, $columns, $client_code) {
                switch ($client_code){
                    case 'SCPR':
                        $this->prepare_scpr_stories($ftarget, $csvPath, $columns);
                        break;
                    case 'TT':
                        $this->prepare_tt_stories($ftarget, $csvPath, $columns);
                    case 'WW':
                        $this->prepare_ww_stories($ftarget, $csvPath, $columns);
                }
            }, "${min_date}_${max_date}_stories_full_report.csv");
        } else {
            return $this->exportCSV($request,
                $client_code . '_data_stories_top100_',
                $this::$DataStoriesExportField[$client_code][$index],
                $this::$DataStoriesColumn[$client_code][3],
                "stories_full_report",
                'Pageviews');
        }
    }

    private static $DataNewsLetterField = [
        'SCPR' => 'List, AVG(Total_Recipients) AS Total_Recipients, AVG(Successful_Deliveries) AS Successful_Deliveries, AVG(Total_Opens) AS Total_Opens, AVG(Unique_Opens) AS Unique_Opens, AVG(Total_Clicks) AS Total_Clicks, AVG(Open_Rate) AS Open_Rate, AVG(Total_Clicks / Successful_Deliveries) AS Cick_To_Delivery_Rate, AVG(Total_Clicks / Unique_Opens) AS Average_Total_Clicks_Per_Unique_Open',
        'TT' => 'List, AVG(Total_Recipients) AS Total_Recipients, AVG(Successful_Deliveries) AS Successful_Deliveries, AVG(Total_Opens) AS Total_Opens, AVG(Unique_Opens) AS Unique_Opens, AVG(Total_Clicks) AS Total_Clicks, AVG(Open_Rate) AS Open_Rate, AVG(Total_Clicks / Successful_Deliveries) AS Cick_To_Delivery_Rate, AVG(Total_Clicks / Unique_Opens) AS Average_Total_Clicks_Per_Unique_Open',
        'WW' => 'List, AVG(Total_Recipients) AS Total_Recipients, AVG(Successful_Deliveries) AS Successful_Deliveries, AVG(Total_Opens) AS Total_Opens, AVG(Unique_Opens) AS Unique_Opens, AVG(Total_Clicks) AS Total_Clicks, AVG(Open_Rate) AS Open_Rate, AVG(Total_Clicks / Successful_Deliveries) AS Cick_To_Delivery_Rate, AVG(Total_Clicks / Unique_Opens) AS Average_Total_Clicks_Per_Unique_Open',
    ];

    private static $DataNewsLetterColumn = [
        'SCPR' => ['Newsletter', 'Frequency', 'Deliveries', 'Opens', 'Unique Opens', 'Clicks', 'Open Rate', 'Click to Delivery Rate', 'Avg Total Clicks per Unique Opens'],
        'TT' => ['Newsletter', 'Frequency', 'Deliveries', 'Opens', 'Unique Opens', 'Clicks', 'Open Rate', 'Click to Delivery Rate', 'Avg Total Clicks per Unique Opens'],
        'WW' => ['Newsletter', 'Frequency', 'Deliveries', 'Opens', 'Unique Opens', 'Clicks', 'Open Rate', 'Click to Delivery Rate', 'Avg Total Clicks per Unique Opens']
    ];

    public function showNewsLetter(Request $request){
        $client_id = $request['client']['id'];
        $client_code = $request['client']['code'];
        $group = array_key_exists($request['group'], self::$groupDisplay) ? $request['group'] : 'weekly';
        $isSuperAdmin = $request['isSuperAdmin'];
        if($isSuperAdmin){
            $query = DB::table($client_code . '_data_newsletter_' . $group);
        }else {
            $query = DB::table($client_code . '_data_newsletter_' . $group)->where('ready', 1);
        }

        $count = $query->count();

        $date_range_min = $query->min('date');
        $date_range_max = $query->max('date');

        $date_range_min = $this::getFirstDayOfWeek(strtotime($date_range_min));
        $date_range_max_begin = $this::getFirstDayOfWeek(strtotime($date_range_max));
        $date_range_max = strtotime('6 days', $date_range_max_begin);

        $max_date = strtotime($request['max_date']) ?: $date_range_max;
        $min_date = strtotime($request['min_date']) ?: $date_range_max_begin;

        return view('data.' . $client_code . '.newsletter', [
            'have_data' => $count > 0,
            'min_date' => $min_date,
            'max_date' => $max_date,
            'date_range_min' => date('Y-m-d', $date_range_min),
            'date_range_max' => date('Y-m-d', $date_range_max),
            'group' => $group,
            'displayGroupName' => self::$groupDisplay[$group]
        ]);
    }

    public function get_NewsLetter(Request $request){
        $client_code = $request['client']['code'];
        return $this->dataTableQuery($request,
            $client_code.'_data_newsletter_',
            $this::$DataNewsLetterField[$client_code],
            function($query){
                return $query->groupby('List');
            });
    }

    public function download_NewsLetter(Request $request){
        $client_code = $request['client']['code'];
        return $this->exportCSV($request,
            $client_code.'_data_newsletter_',
            $this::$DataNewsLetterField[$client_code],
            $this::$DataNewsLetterColumn[$client_code],
            'newsletter',
            'date',
            function($query){
                return $query->groupby('List');
            });
    }

    private static $DataQualityField = [
        'SCPR' => [
            'date, \'\' as Events, GA_Users, MIP_Users, (IFNULL(GA_Users, 0) - IFNULL(MIP_Users, 0)) / IFNULL(MIP_Users, 0) as Variance',
            'date, I_inDatabaseCameToSite, K_inDatabaseCameToSite, I_notInDatabaseCameToSite, K_notInDatabaseCameToSite, I_newSubscriberCameThroughEmail, K_newSubscriberCameThroughEmail, I_SubscribersThisWeek, K_SubscribersThisWeek, I_NewSubscribers, K_NewSubscribers, I_TotalDatabaseSubscribers, K_TotalDatabaseSubscribers, K_PercentDatabaseSubscribersWhoCame, EmailNewsletterClicks',
            'date, I_databaseDonorsWhoVisited, K_databaseDonorsWhoVisited, I_donatedOnSiteForFirstTime, K_donatedOnSiteForFirstTime, I_totalDonorsOnSiteThisWeek, K_totalDonorsOnSiteThisWeek, I_totalDonorsInDatabase, K_totalDonorsInDatabase, K_percentDatabaseDonorsWhoCame',
            'date, K_individualsWhoCameThisWeek, K_individualsInDatabase, K_percentDatabaseIndividualsWhoCame'
        ],
        'TT' => [
            'date, \'\' as Events, GA_Users, MIP_Users, (IFNULL(GA_Users, 0) - IFNULL(MIP_Users, 0)) / IFNULL(MIP_Users, 0) as Variance',
            'date, I_inDatabaseCameToSite, K_inDatabaseCameToSite, I_notInDatabaseCameToSite, K_notInDatabaseCameToSite, I_newSubscriberCameThroughEmail, K_newSubscriberCameThroughEmail, I_SubscribersThisWeek, K_SubscribersThisWeek, I_NewSubscribers, K_NewSubscribers, I_TotalDatabaseSubscribers, K_TotalDatabaseSubscribers, K_PercentDatabaseSubscribersWhoCame, EmailNewsletterClicks',
            'date, I_databaseDonorsWhoVisited, K_databaseDonorsWhoVisited, I_donatedOnSiteForFirstTime, K_donatedOnSiteForFirstTime, I_totalDonorsOnSiteThisWeek, K_totalDonorsOnSiteThisWeek, I_totalDonorsInDatabase, K_totalDonorsInDatabase, K_percentDatabaseDonorsWhoCame',
            'date, K_individualsWhoCameThisWeek, K_individualsInDatabase, K_percentDatabaseIndividualsWhoCame',
            'date, I_databaseMembersWhoVisited, K_databaseMembersWhoVisited, I_loggedInOnSiteForFirstTime, K_loggedInOnSiteForFirstTime, I_totalMembersOnSiteThisWeek, K_totalMembersOnSiteThisWeek, I_totalMembersInDatabase, K_totalMembersInDatabase, K_percentDatabaseMembersWhoCame'
        ],
        'WW' => [
            'date, \'\' as Events, GA_Users, MIP_Users, (IFNULL(GA_Users, 0) - IFNULL(MIP_Users, 0)) / IFNULL(MIP_Users, 0) as Variance',
            'date, I_inDatabaseCameToSite, K_inDatabaseCameToSite, I_notInDatabaseCameToSite, K_notInDatabaseCameToSite, I_newSubscriberCameThroughEmail, K_newSubscriberCameThroughEmail, I_SubscribersThisWeek, K_SubscribersThisWeek, I_NewSubscribers, K_NewSubscribers, I_TotalDatabaseSubscribers, K_TotalDatabaseSubscribers, K_PercentDatabaseSubscribersWhoCame, EmailNewsletterClicks',
            'date, I_databaseDonorsWhoVisited, K_databaseDonorsWhoVisited, I_donatedOnSiteForFirstTime, K_donatedOnSiteForFirstTime, I_totalDonorsOnSiteThisWeek, K_totalDonorsOnSiteThisWeek, I_totalDonorsInDatabase, K_totalDonorsInDatabase, K_percentDatabaseDonorsWhoCame',
            'date, K_individualsWhoCameThisWeek, K_individualsInDatabase, K_percentDatabaseIndividualsWhoCame',
            'date, I_databaseMembersWhoVisited, K_databaseMembersWhoVisited, I_loggedInOnSiteForFirstTime, K_loggedInOnSiteForFirstTime, I_totalMembersOnSiteThisWeek, K_totalMembersOnSiteThisWeek, I_totalMembersInDatabase, K_totalMembersInDatabase, K_percentDatabaseMembersWhoCame'
        ],
    ];

    private static $DataQualityColumn = [
        'SCPR' => [
            ['Week of', 'Events', 'GA Users', 'MIP GTM Users', 'Variance'],
            ['Week of', 'Identified: Subscribers already in MIP database who came to the site this week', 'Known: Subscribers already in MIP database who came to the site this week', 'Identified: Subscribers who came to the site through an e-mail this week for the first time since MIP started collecting data', 'Known: Subscribers who came to the site through an e-mail this week for the first time since MIP started collecting data', 'Identified: New subscribers this week who also clicked on an e-mail this week', 'Known: New subscribers this week who also clicked on an e-mail this week', 'Identified e-mail newsletter subscribers THIS WEEK', 'Known e-mail newsletter subscribers THIS WEEK (unique ELQs)', 'Identified: New e-mail subscribers this week', 'Known: New e-mail subscribers this week', 'Identified: Total identified e-mail newsletter subscribers in the MIP database', 'Known: Total number of known e-mail newsletter subscribers in the MIP database', 'Known: Percent of subscribers in the MIP database who clicked on an e-mail this week', 'E-mail newsletter clicks per week'],
            ['Week of', 'Identified: Donors already in MIP database who came to the site this week', 'Known: Donors already in MIP database who came to the site this week', 'Identified: Users who donated on the site for the first time since MIP started collecting data', 'Known: Users who donated on the site for the first time since MIP started collecting data', 'Identified donors on the site THIS WEEK', 'Known donors on the site THIS WEEK', 'Identified: Total identified donors in the MIP database', 'Known: Total known donors in the MIP database', 'Known: Percent of subscribers in the MIP database who clicked on an e-mail this week'],
            ['Week of', 'Known: Total known donors and/or e-mail newsletter subscribers who came to the site THIS WEEK', 'Known: Total known donors and/or e-mail newsletter subscribers in the MIP database', 'Known: Percent of known individuals in the MIP database who came to the site this week']
        ],
        'TT' => [
            ['Week of', 'Events', 'GA Users', 'MIP GTM Users', 'Variance'],
            ['Week of', 'Identified: Subscribers already in MIP database who came to the site this week', 'Known: Subscribers already in MIP database who came to the site this week', 'Identified: Subscribers who came to the site through an e-mail this week for the first time since MIP started collecting data', 'Known: Subscribers who came to the site through an e-mail this week for the first time since MIP started collecting data', 'Identified: New subscribers this week who also clicked on an e-mail this week', 'Known: New subscribers this week who also clicked on an e-mail this week', 'Identified e-mail newsletter subscribers THIS WEEK', 'Known e-mail newsletter subscribers THIS WEEK (unique ELQs)', 'Identified: New e-mail subscribers this week', 'Known: New e-mail subscribers this week', 'Identified: Total identified e-mail newsletter subscribers in the MIP database', 'Known: Total number of known e-mail newsletter subscribers in the MIP database', 'Known: Percent of subscribers in the MIP database who clicked on an e-mail this week', 'E-mail newsletter clicks per week'],
            ['Week of', 'Identified: Donors already in MIP database who came to the site this week', 'Known: Donors already in MIP database who came to the site this week', 'Identified: Users who donated on the site for the first time since MIP started collecting data', 'Known: Users who donated on the site for the first time since MIP started collecting data', 'Identified donors on the site THIS WEEK', 'Known donors on the site THIS WEEK', 'Identified: Total identified donors in the MIP database', 'Known: Total known donors in the MIP database', 'Known: Percent of subscribers in the MIP database who clicked on an e-mail this week'],
            ['Week of', 'Known: Total known donors and/or e-mail newsletter subscribers who came to the site THIS WEEK', 'Known: Total known donors and/or e-mail newsletter subscribers in the MIP database', 'Known: Percent of known individuals in the MIP database who came to the site this week'],
            ['Week of', 'Identified: Members already in MIP database who came to the site this week', 'Known: Members already in MIP database who came to the site this week', 'Identified: Users who logged in on the site for the first time since MIP started collecting data', 'Known: Users who logged in on the site for the first time since MIP started collecting data', 'Identified members on the site THIS WEEK', 'Known members on the site THIS WEEK', 'Identified: Total identified members in the MIP database', 'Known: Total known members in the MIP database', 'Known: Percent of members in the MIP database who logged in this week']
        ],
        'WW' => [
            ['Week of', 'Events', 'GA Users', 'MIP GTM Users', 'Variance'],
            ['Week of', 'Identified: Subscribers already in MIP database who came to the site this week', 'Known: Subscribers already in MIP database who came to the site this week', 'Identified: Subscribers who came to the site through an e-mail this week for the first time since MIP started collecting data', 'Known: Subscribers who came to the site through an e-mail this week for the first time since MIP started collecting data', 'Identified: New subscribers this week who also clicked on an e-mail this week', 'Known: New subscribers this week who also clicked on an e-mail this week', 'Identified e-mail newsletter subscribers THIS WEEK', 'Known e-mail newsletter subscribers THIS WEEK (unique ELQs)', 'Identified: New e-mail subscribers this week', 'Known: New e-mail subscribers this week', 'Identified: Total identified e-mail newsletter subscribers in the MIP database', 'Known: Total number of known e-mail newsletter subscribers in the MIP database', 'Known: Percent of subscribers in the MIP database who clicked on an e-mail this week', 'E-mail newsletter clicks per week'],
            ['Week of', 'Identified: Donors already in MIP database who came to the site this week', 'Known: Donors already in MIP database who came to the site this week', 'Identified: Users who donated on the site for the first time since MIP started collecting data', 'Known: Users who donated on the site for the first time since MIP started collecting data', 'Identified donors on the site THIS WEEK', 'Known donors on the site THIS WEEK', 'Identified: Total identified donors in the MIP database', 'Known: Total known donors in the MIP database', 'Known: Percent of subscribers in the MIP database who clicked on an e-mail this week'],
            ['Week of', 'Known: Total known donors and/or e-mail newsletter subscribers who came to the site THIS WEEK', 'Known: Total known donors and/or e-mail newsletter subscribers in the MIP database', 'Known: Percent of known individuals in the MIP database who came to the site this week'],
            ['Week of', 'Identified: Members already in MIP database who came to the site this week', 'Known: Members already in MIP database who came to the site this week', 'Identified: Users who logged in on the site for the first time since MIP started collecting data', 'Known: Users who logged in on the site for the first time since MIP started collecting data', 'Identified members on the site THIS WEEK', 'Known members on the site THIS WEEK', 'Identified: Total identified members in the MIP database', 'Known: Total known members in the MIP database', 'Known: Percent of members in the MIP database who logged in this week']
        ],
    ];

    public function showQuality(Request $request){
        $client_id = $request['client']['id'];
        $client_code = $request['client']['code'];
        $group = array_key_exists($request['group'], self::$groupDisplay) ? $request['group'] : 'weekly';
        $isSuperAdmin = $request['isSuperAdmin'];
        if($isSuperAdmin){
            $query = DB::table($client_code . '_data_quality_' . $group);
        }else {
            $query = DB::table($client_code . '_data_quality_' . $group)->where('ready', 1);
        }

        $count = $query->count();
        $date_range_min = strtotime($query->min('date'));
        $date_range_max = strtotime($query->max('date'));
        $date_range_max = strtotime('6 days', $date_range_max);
        $max_date = $date_range_max;
        $min_date = strtotime('-27 days', $max_date);

        return view('data.' . $client_code . '.quality', [
            'have_data' => $count > 0,
            'min_date' => $min_date,
            'max_date' => $max_date,
            'date_range_min' => date('Y-m-d', $date_range_min),
            'date_range_max' => date('Y-m-d', $date_range_max),
            'group' => $group,
            'displayGroupName' => self::$groupDisplay[$group],
            'default_date_range' => date('M d, Y', $min_date). ' - ' . date('M d, Y', $max_date)
        ]);
    }

    public function get_Quality_GA_VS_GTM(Request $request){
        $client_code = $request['client']['code'];
        return $this->dataTableQuery($request,
            $client_code.'_data_quality_',
            $this::$DataQualityField[$client_code][0]);
    }

    public function download_Quality_GA_VS_GTM(Request $request){
        $client_code = $request['client']['code'];
        return $this->exportCSV($request,
            $client_code.'_data_quality_',
            $this::$DataQualityField[$client_code][0],
            $this::$DataQualityColumn[$client_code][0],
            'GA vs GTM');
    }

    public function download_All_Quality_GA_VS_GTM(Request $request){
        $client_code = $request['client']['code'];
        return $this->exportCSV($request,
            $client_code.'_data_quality_',
            $this::$DataQualityField[$client_code][0],
            $this::$DataQualityColumn[$client_code][0],
            'GA vs GTM');
    }

    public function get_Quality_Email_Subscribers(Request $request){
        $client_code = $request['client']['code'];
        return $this->dataTableQuery($request,
            $client_code.'_data_quality_',
            $this::$DataQualityField[$client_code][1]);
    }

    public function download_Quality_Email_Subscribers(Request $request){
        $client_code = $request['client']['code'];
        return $this->exportCSV($request,
            $client_code.'_data_quality_',
            $this::$DataQualityField[$client_code][1],
            $this::$DataQualityColumn[$client_code][1],
            'Email Subscribers');
    }

    public function download_All_Quality_Email_Subscribers(Request $request){
        $client_code = $request['client']['code'];
        return $this->exportCSV($request,
            $client_code.'_data_quality_',
            $this::$DataQualityField[$client_code][1],
            $this::$DataQualityColumn[$client_code][1],
            'Email Subscribers');
    }

    public function get_Quality_Donors(Request $request){
        $client_code = $request['client']['code'];
        return $this->dataTableQuery($request,
            $client_code.'_data_quality_',
            $this::$DataQualityField[$client_code][2]);
    }

    public function download_Quality_Donors(Request $request){
        $client_code = $request['client']['code'];
        return $this->exportCSV($request,
            $client_code.'_data_quality_',
            $this::$DataQualityField[$client_code][2],
            $this::$DataQualityColumn[$client_code][2],
            'Donors');
    }

    public function download_All_Quality_Donors(Request $request){
        $client_code = $request['client']['code'];
        return $this->exportCSV($request,
            $client_code.'_data_quality_',
            $this::$DataQualityField[$client_code][2],
            $this::$DataQualityColumn[$client_code][2],
            'Donors');
    }

    public function get_Quality_Total_Known_Users(Request $request){
        $client_code = $request['client']['code'];
        return $this->dataTableQuery($request,
            $client_code.'_data_quality_',
            $this::$DataQualityField[$client_code][3]);
    }

    public function download_Quality_Total_Known_Users(Request $request){
        $client_code = $request['client']['code'];
        return $this->exportCSV($request,
            $client_code.'_data_quality_',
            $this::$DataQualityField[$client_code][3],
            $this::$DataQualityColumn[$client_code][3],
            'Total Known Users');
    }

    public function download_All_Quality_Total_Known_Users(Request $request){
        $client_code = $request['client']['code'];
        return $this->exportCSV($request,
            $client_code.'_data_quality_',
            $this::$DataQualityField[$client_code][3],
            $this::$DataQualityColumn[$client_code][3],
            'Total Known Users');
    }

    public function get_Quality_Members(Request $request){
        $client_code = $request['client']['code'];
        return $this->dataTableQuery($request,
            $client_code.'_data_quality_',
            $this::$DataQualityField[$client_code][4]);
    }

    public function download_Quality_Members(Request $request){
        $client_code = $request['client']['code'];
        return $this->exportCSV($request,
            $client_code.'_data_quality_',
            $this::$DataQualityField[$client_code][4],
            $this::$DataQualityColumn[$client_code][4],
            'Members');
    }

    public function download_All_Quality_Members(Request $request){
        $client_code = $request['client']['code'];
        return $this->exportCSV($request,
            $client_code.'_data_quality_',
            $this::$DataQualityField[$client_code][4],
            $this::$DataQualityColumn[$client_code][4],
            'Members');
    }
}