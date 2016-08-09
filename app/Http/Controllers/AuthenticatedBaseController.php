<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\FormatterHelper;
use Illuminate\Http\Request;
use DB;
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

    protected static $groupDisplay = [
        'daily' => 'By Date',
        'weekly' => 'By Week',
        'monthly' => 'By Month',
    ];

    protected function dataTableQuery(Request $request, $tableName, $select){
        $group = array_key_exists($request['group'], self::$groupDisplay) ? $request['group'] : 'weekly';
        $max_date = date_parse($request['max_date'] ?: date('Y-m-d', time()));
        $min_date = date_parse($request['min_date'] ?: date('Y-m-1', time()));
        $client_id = $request['client']['id'];
        $query = DB::table($tableName . $group)
            ->select(DB::raw($select))
            ->where('date', '<=', $max_date['year'] . '-' . $max_date['month'] . '-' . $max_date['day'])
            ->where('date', '>=', $min_date['year'] . '-' . $min_date['month'] . '-' . $min_date['day']);


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

    protected function exportCSV(Request $request, $tableName, $select, $columns, $downloadName){
        $group = array_key_exists($request['group'], self::$groupDisplay) ? $request['group'] : 'weekly';
        $max_date = date_parse($request['max_date'] ?: date('Y-m-d', time()));
        $min_date = date_parse($request['min_date'] ?: date('Y-m-1', time()));
        $client_id = $request['client']['id'];
        $query = DB::table($tableName . $group)
            ->select(DB::raw($select))
            ->where('date', '<=', $max_date['year'] . '-' . $max_date['month'] . '-' . $max_date['day'])
            ->where('date', '>=', $min_date['year'] . '-' . $min_date['month'] . '-' . $min_date['day']);
        $data = $query->get();

        $bucket = 'dashboard-php-storage';
        $fileName = md5(uniqid()) . '.csv';
        $fullName = "download/${fileName}";

        $fp = fopen("gs://${bucket}/${fullName}", 'w');
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($fp, $columns);
        foreach($data as $row){
            fputcsv($fp, array_values(get_object_vars($row)));
        }
        fclose($fp);
        
        return response()->download("gs://${bucket}/${fullName}", "${downloadName}.csv", [
            'Content-type' => 'text/csv'
        ]);
    }
}