<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\FormatterHelper;
use Illuminate\Http\Request;
use DB;
use Google_Client;
use Google_Service_Storage;

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
            ->where('client_id', $client_id)
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

    protected function exportCSV(Request $request, $tableName, $select, $columns){
        $group = array_key_exists($request['group'], self::$groupDisplay) ? $request['group'] : 'weekly';
        $max_date = date_parse($request['max_date'] ?: date('Y-m-d', time()));
        $min_date = date_parse($request['min_date'] ?: date('Y-m-1', time()));
        $client_id = $request['client']['id'];
        $query = DB::table($tableName . $group)
            ->select(DB::raw($select))
            ->where('client_id', $client_id)
            ->where('date', '<=', $max_date['year'] . '-' . $max_date['month'] . '-' . $max_date['day'])
            ->where('date', '>=', $min_date['year'] . '-' . $min_date['month'] . '-' . $min_date['day']);
        $data = $query->get();

        $client = new Google_Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google_Service_Storage::DEVSTORAGE_FULL_CONTROL);

        $storage = new Google_Service_Storage($client);
        $buckets = $storage->buckets->listBuckets('mip-dashboard');
        dd($buckets);

    }
}