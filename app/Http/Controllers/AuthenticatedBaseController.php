<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\FormatterHelper;
use Illuminate\Http\Request;
use DB;

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
        //$orderBy = $request->order[0][column]:"5"
        $query = DB::table($tableName . $group)
            ->select($select)
            ->where('client_id', $client_id)
            ->where('date', '<=', $max_date['year'] . '-' . $max_date['month'] . '-' . $max_date['day'])
            ->where('date', '>=', $min_date['year'] . '-' . $min_date['month'] . '-' . $min_date['day']);
        $orderByIndex = $request['order'][0]['column'];
        $orderByDir = $request['order'][0]['dir'];
        $orderBy = $request['columns'][$orderByIndex]['data'] ?: 'date';

        $total = $query->count();
        $data = $query->skip($request->start ?: 0)
            ->take($request->length ?: 10)
            ->orderBy($orderBy, $orderByDir)
            ->get();

        return [
            'draw' => $request->draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ];
    }

    protected function exportCSV(Request $request, $table, $select, $columns){
        
    }
}