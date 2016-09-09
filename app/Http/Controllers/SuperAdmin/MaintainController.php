<?php

namespace app\Http\Controllers\SuperAdmin;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthenticatedBaseController;

class MaintainController extends AuthenticatedBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->authorize('SuperAdmin');
    }

    public function showPage(Request $request){
        $displayCount = 10;

        $clients = DB::table('clients')
            ->select('name', 'code')
            ->where('ready', true)
            ->get();
        $result = [];
        foreach($clients as $client){
            $report = [
                'client' => $client->name,
                'code' => $client->code
            ];
            $db_quality = DB::table($client->code . '_data_quality_weekly')
                ->select('date', 'ready')
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->take($displayCount)
                ->get();
            $quality = [];
            foreach($db_quality as $item){
                $quality[$item->date] = $item->ready;
            }
            $report['quality'] = $quality;
            $db_stories = DB::table($client->code . '_data_stories_weekly')
                ->select('date', 'ready')
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->take($displayCount)
                ->get();
            $stories = [];
            foreach($db_stories as $item){
                $stories[$item->date] = $item->ready;
            }
            $report['stories'] = $stories;
            $db_users = DB::table($client->code . '_data_users_weekly')
                ->select('date', 'ready')
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->take($displayCount)
                ->get();
            $users = [];
            foreach ($db_users as $item){
                $users[$item->date] = $item->ready;
            }
            $report['users'] = $users;

            $result[] = $report;

        }

        $weeks = [];
        $thisWeek = $this::getFirstDayOfWeek(time());
        for($i = 1; $i <= 10; $i++){
            $days = $i * 7;
            $weeks[] = strtotime("-${days} days", $thisWeek);
        }

        return view('superAdmin.maintain', [
            'result' => $result,
            'weeks' => $weeks
        ]);
    }

    public function setDateReady(Request $request){
        $code = $request['code'];
        $table = $request['table'];
        $date = $request['date'];

        DB::table("${code}_${table}_weekly")
            ->where('date', $date)
            ->update(['ready' => true]);

        return ['success'=>true];
    }

}