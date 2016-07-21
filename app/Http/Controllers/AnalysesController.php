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
        $file = Analyses::where('file_id', $guid)->get();
        if($file){
            $path = $file[0]->path;
            $name = $file[0]->file_name;
            return response()->download($path, $name);
        }
    }

    public function upload(Request $request){
        $name = $_FILES['content']['name'];
        $extension = pathinfo($name)['extension'];
        $uploadFile = $_FILES['content']['tmp_name'];
        $guid = time();
        $bucket = $this::$bucket;
        $path = "gs://${bucket}/${guid}.${extension}";
        file_put_contents($path, $uploadFile);
        Analyses::create([
            'file_id' => $guid,
            'file_name' => $name,
            'description' => $request['description'],
            'screen_shot' => null,
            'path' => $path
        ]);
    }
}