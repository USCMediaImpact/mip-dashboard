<?php

namespace App\Http\Controllers;

use DB;
use Cache;
use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Bigquery;
use Google_Service_Bigquery_QueryRequest;
use App\Models\Analyses;

class AnalysesController extends AuthenticatedBaseController{

    static $bucket = 'mip-dashboard-upload';

    public function show(){
        $data = DB::table('analyses')->get();
        return view('analyses.index', ['data' => $data]);
    }

    public function display(Request $request, $guid){

    }

    public function upload(Request $request){
        $name = $_FILES['content']['name'];
        $extension = pathinfo($name)['extension'];
        $uploadFile = $_FILES['content']['tmp_name'];
        $guid = trim(com_create_guid(), '{}');
        $path = "gs://${bucket}/${guid}.${extension}";
        file_put_contents($path, $uploadFile);
        Analyses::create([
            'file_name' => $name,
            'description' => $request['description'],
            'screen_shot' => null,
            'path' => $path
        ]);
    }
}