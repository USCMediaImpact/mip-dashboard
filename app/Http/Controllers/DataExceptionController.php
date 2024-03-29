<?php

namespace App\Http\Controllers;

use DB;
use PDO;
use Illuminate\Http\Request;
use App\Models\DataException;

class DataExceptionController extends AuthenticatedBaseController
{
    public function show(Request $request){
        $client_id = $request['client']['id'];
        $client_code = $request['client']['code'];
        $isSuperAdmin = $request['isSuperAdmin'];
        if($isSuperAdmin) {
            $default_min_date = DB::table("${client_code}_data_users_weekly")
                ->max('date');
        } else {
            $default_min_date = DB::table("${client_code}_data_users_weekly")
                ->where('ready', 1)
                ->max('date');
        }

        $default_min_date = strtotime($default_min_date);
        $default_max_date = strtotime('+6 days', $default_min_date);

        $max_date = strtotime($request['max_date']) ?: $default_max_date;
        $min_date = strtotime($request['min_date']) ?: $default_min_date;

        $query = DataException::with('reporter')
            ->where('client_id', $client_id)
            ->orderby('created_at', 'desc');

        $query = $query->where('begin_date', '<=', date('Y-m-d', $max_date))
            ->where('end_date', '>=', date('Y-m-d', $min_date));

        $default_date_range = date('m/d/Y', $min_date). ' - ' . date('m/d/Y', $max_date);

        $data = $query->get();
        return view('dataException.index', [
            'data' => $data,
            'max_date' => $max_date,
            'min_date' => $min_date,
            'default_date_range' => $default_date_range
        ]);
    }

    public function get(Request $request, $id)
    {
        $client_id = $request['client']['id'];
        return DataException::with('reporter')
            ->where('client_id', $client_id)
            ->where('id', $id)
            ->first();
    }

    public function create(Request $request)
    {
        $client_id = $request['client']['id'];
        $user = $request->user();
        $user_id = $user->id;
        return DataException::create([
            'client_id' => $client_id,
            'report_user_id' => $user_id,
            'title' => $request['title'],
            'data_impact' => $request['data_impact'],
            'resolution' => $request['resolution'],
            'begin_date' => $request['begin_date'] ?: null,
            'end_date' => $request['end_date'] ?: null
        ]);
    }

    public function edit(Request $request)
    {

        $client_id = $request['client']['id'];
        $user = $request->user();
        $user_id = $user->id;
        $dataException = DataException::where('id', $request['id'])
            ->where('client_id', $client_id)
            ->first();
        if ($dataException !== null) {
            $dataException->update([
                'title' => $request['title'],
                'data_impact' => $request['data_impact'],
                'resolution' => $request['resolution'],
                'begin_date' => $request['begin_date'] ?: null,
                'end_date' => $request['end_date'] ?: null,
                'resolved' => $request['resolved'] == 'true' ? true : false
            ]);
            return [
                'success' => true,
            ];
        }else{
            return ['success' => false, 'message' => 'not exists'];
        }
    }

    public function delete(Request $request){
        $client_id = $request['client']['id'];
        $user = $request->user();
        $user_id = $user->id;
        DataException::where('id', $request['id'])
            ->where('client_id', $client_id)
            ->delete();
        return ['success' => true, 'id' => $request['id']];
    }

    public function download(Request $request){
        $client_id = $request['client']['id'];
        $bucket = 'dashboard-php-storage';
        $fileName = md5(uniqid()) . '.csv';
        $fullName = "download/${fileName}";

        $pdo = DB::connection(env('DB_CONNECTION'))->getPdo();

        $stmt = $pdo->prepare('SELECT data_exceptions.title , data_exceptions.data_impact , data_exceptions.resolution , data_exceptions.begin_date , data_exceptions.end_date , data_exceptions.resolved , users.`name` AS reporter_name , users.email AS reporter_email FROM data_exceptions JOIN users ON data_exceptions.report_user_id = users.id WHERE data_exceptions.client_id = ? AND data_exceptions.deleted_at IS NULL ORDER BY data_exceptions.created_at DESC');

        $stmt->execute([$client_id]);

        $fp = fopen("gs://${bucket}/${fullName}", 'w');
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($fp, ['Issue Description', 'Data Impact', 'Resolution', 'Impacted Begin Date', 'Impacted End Date', 'Reporter Name', 'Reporter Email', 'Resolved']);

        while($row = $stmt->fetch(PDO::FETCH_OBJ)){
            fputcsv($fp, [
                $row->title,
                $row->data_impact,
                $row->resolution,
                date('Y-m-d', strtotime($row->begin_date)),
                date('Y-m-d', strtotime($row->end_date)),
                $row->reporter_name,
                $row->reporter_email,
                $row->resolved ? 'Yes' : 'No'
            ]);
        }
        fclose($fp);

        return response()->download(
            "gs://${bucket}/${fullName}",
            "data exceptions.csv", [
            'Content-type' => 'text/csv'
        ]);

    }
}