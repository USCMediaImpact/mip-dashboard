<?php

namespace app\Http\Controllers\SuperAdmin;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthenticatedBaseController;

use App\Models\Client;
use App\Models\Setting;

class SettingController extends AuthenticatedBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->authorize('SuperAdmin');
    }

    public function showPage(Request $request, $id)
    {
        $client = Client::find($id);
        $setting = $client->setting ?: new Setting();

        if ($setting->values !== null) {
            $values = json_decode($setting->values, true);
        } else {
            $values = [];
        }

        return view('superAdmin.settings', [
            'client' => $client,
            'enable_sync' => $setting->enable_sync ?: null,
            'values' => $values
        ]);
    }

    public function save(Request $request){
        $clientId = $request['client_id'];
        $client = Client::find($clientId);
        if($client === null) {
            return redirect('/admin/client');
        }

        $setting = $client->setting;
        $prepare = [];
        if($request['bq_prepare']){
            foreach($request['bq_prepare'] as $row){
                $prepare[] = $row;
            }
        }
        $data = [
            'enable_sync' => $request['enable_sync'] ? DB::raw(1) : DB::raw(0),
            'values' => json_encode([
                'ga_id' => $request['ga_id'],
                'bq_id' => $request['bq_id'],
                'bq_prepare' => $prepare,
                'data_users_dimension' =>  $request['data_users_dimension'],
                'bq_data_users' => $request['bq_data_users'],
                'data_stories_dimension' =>  $request['data_stories_dimension'],
                'bq_data_stories' => $request['bq_data_stories'],
                'data_quality_dimension' =>  $request['data_quality_dimension'],
                'bq_data_quality' => $request['bq_data_quality'],
            ])
        ];
        if($setting === null) {
            $data['client_id'] = $clientId;
            $setting = Setting::create($data);
        }else {
            $setting->update($data);
        }
        $setting->save();
        return redirect('/admin/client/setting/' . $clientId);
    }

}