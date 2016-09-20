<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\FormatterHelper;
use Illuminate\Http\Request;
use DB;
use PDO;
use Google_Client;
use Google_Service_Storage;
use Google_Service_Storage_ObjectAccessControl;

class AuthenticatedBaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('routeInfo');
        $this->middleware('auth');
        $this->middleware('clientInfo');
    }

    protected static function getFirstDayOfWeek($date){
        $weekOfNumber = date('w', $date);
        return strtotime("-${weekOfNumber} days", $date);

    }

    protected static $groupDisplay = [
        'daily' => 'By Date',
        'weekly' => 'By Week',
        'monthly' => 'By Month',
    ];

    protected function dataTableQuery(Request $request, $tableName, $select, $callback = null){
        $group = array_key_exists($request['group'], self::$groupDisplay) ? $request['group'] : 'weekly';
        $max_date = date_parse($request['max_date'] ?: date('Y-m-d', time()));
        $min_date = date_parse($request['min_date'] ?: date('Y-m-1', time()));
        $client_id = $request['client']['id'];
        $isSuperAdmin = $request['isSuperAdmin'];
        $query = DB::table($tableName . $group)
            ->select(DB::raw($select));

        if($callback){
            $query = $callback($query);
        }
        $max_date = $max_date['year'] . '-' . $max_date['month'] . '-' . $max_date['day'];
        $min_date = $min_date['year'] . '-' . $min_date['month'] . '-' . $min_date['day'];
        $query = $query->where('date', '<=', $max_date)
            ->where('date', '>=', $min_date);
        if(!$isSuperAdmin){
            $query = $query->where('ready', 1);
        }

        $orderByIndex = $request['order'][0]['column'];
        $orderBy = $request['columns'][$orderByIndex]['data'] ?: 'date';
        $orderByDir = $request['order'][0]['dir'];


        $total = $query->count();
        $data = $query->skip($request->start ?: 0)
            ->take($request->length ?: 10)
            ->orderBy($orderBy, $orderByDir ?: 'desc')
            ->get();

        return [
            'draw' => $request->draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ];
    }

    protected function responseFile($fputCsv, $displayName)
    {
        $bucket = 'dashboard-php-storage';
        $fileName = md5(uniqid()) . '.csv';
        $fullName = "download/${fileName}";

        $fp = fopen("gs://${bucket}/${fullName}", 'w');

        if ($fputCsv != null) {
            $fputCsv($fp);
        }

        fclose($fp);

        return response()->download(
            "gs://${bucket}/${fullName}",
            $displayName, [
            'Content-type' => 'text/csv'
        ]);
    }
    protected function exportCSV(Request $request, $tableName, $select, $columns, $downloadName, $sort = 'date', $callback = null){
        $group = array_key_exists($request['group'], self::$groupDisplay) ? $request['group'] : 'weekly';
        $max_date = date_parse($request['max_date'] ?: date('Y-m-d', time()));
        $min_date = date_parse($request['min_date'] ?: date('Y-m-1', time()));
        $client_id = $request['client']['id'];
        $isSuperAdmin = $request['isSuperAdmin'];
        $max_date = $max_date['year'] . '-' . $max_date['month'] . '-' . $max_date['day'];
        $min_date = $min_date['year'] . '-' . $min_date['month'] . '-' . $min_date['day'];

        $query = DB::table($tableName . $group)
            ->select(DB::raw($select))
            ->orderBy($sort, 'desc');

        if($callback){
            $query = $callback($query);
        }

        $query = $query->where('date', '<=', $max_date)
            ->where('date', '>=', $min_date);

        if(!$isSuperAdmin){
            $query = $query->where('ready', 1);
        }

        $bucket = 'dashboard-php-storage';
        $fileName = md5(uniqid()) . '.csv';
        $fullName = "download/${fileName}";



        $fp = fopen("gs://${bucket}/${fullName}", 'w');
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($fp, $columns);

//        return $this->responseFile(function($fp) use($query, $max_date, $min_date, $columns){
//            $pdo = DB::connection(env('DB_CONNECTION'))->getPdo();
//            $stmt = $pdo->prepare($query->toSql());
//            $stmt->execute([$max_date, $min_date, 1]);
//            fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
//            fputcsv($fp, $columns);
//            while($row = $stmt->fetch(PDO::FETCH_OBJ)){
//                fputcsv($fp, array_values(get_object_vars($row)));
//            }
//        }, "${min_date}_${max_date}_${downloadName}.csv");

        fclose($fp);

        return response()->download(
            "gs://${bucket}/${fullName}",
            "${min_date}_${max_date}_${downloadName}.csv", [
            'Content-type' => 'text/csv'
        ]);
    }
}