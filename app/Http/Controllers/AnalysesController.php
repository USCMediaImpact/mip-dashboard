<?php

namespace App\Http\Controllers;

use DB;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Google_Client;
use Google_Service_Bigquery;
use Google_Service_Bigquery_QueryRequest;
use App\Models\Analyses;
use Ramsey\Uuid\Uuid;

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
            $type = $file[0]->file_type;
            return response()->make(file_get_contents($path), 200, [
                'Content-Type' => $type,
                'Content-Disposition' => 'inline; filename="'.$name.'"'
            ]);
        }
    }

    public function upload(Request $request){
        dd($_FILES['content']);
        $name = $_FILES['content']['name'];
        $extension = pathinfo($name)['extension'];
        $uploadFile = $_FILES['content']['tmp_name'];
        $guid = Uuid::uuid4()->toString();
        $bucket = $this::$bucket;
        $path = "gs://${bucket}/${guid}.${extension}";
        $file_type = $_FILES['content']['type'];
        
        move_uploaded_file($uploadFile, $path);

        $screenshot = "gs://${bucket}/${guid}_screenshot.${extension}";
        $im = new imagick($path);
        $im->setImageFormat('jpg');
        $im->writeImageFile(fopen($screenshot, 'w'));

        Analyses::create([
            'file_id' => $guid,
            'file_name' => $name,
            'file_type' => $file_type,
            'description' => $request['description'],
            'screen_shot' => $screenshot,
            'path' => $path
        ]);
        return redirect()->action('AnalysesController@show');
    }
}