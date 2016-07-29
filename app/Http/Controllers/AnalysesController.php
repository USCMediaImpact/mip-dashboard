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
use Imagick;

class AnalysesController extends AuthenticatedBaseController{

    static $bucket = 'mip-dashboard-upload';

    public function show(Request $request){
        $client_id = $request['client']['id'];
        $data = DB::table('analyses')
            ->where('client_id', $client_id)
            ->orderby('created_at', 'desc')
            ->get();
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

    public function download(Request $request){
        $file_id_array = $request['file_id'];
        if(is_array($file_id_array) && count($file_id_array) > 0){
            $client_id = $request['client']['id'];
            $data = DB::table('analyses')
                ->where('client_id', $client_id)
                ->whereIn('file_id', $file_id_array)
                ->get();

            if(count($data) > 0){
                $bucket = 'dashboard-php-storage';
                $tmp_name = Uuid::uuid4()->toString();
                $path = "gs://${bucket}/download/${$tmp_name}.zip";
                $zip = new ZipArchive();
                $zip->open($filename, ZipArchive::CREATE);

                if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
                    exit("cannot open <$filename>\n");
                }
                foreach($data as $item){
                    $zip->addFile($item->path, $item->file_name);
                }
                $zip->close();
                return response()->download($path, "Archive.zip");
            }
        }
    }

    public function upload(Request $request){
        $client_id = $request['client']['id'];
        $client_code = $request['client']['code'];
        $user = $request->user();
        $user_id = $user->id;

        $name = $_FILES['content']['name'];
        $extension = pathinfo($name)['extension'];
        $uploadFile = $_FILES['content']['tmp_name'];
        $guid = Uuid::uuid4()->toString();
        $bucket = $this::$bucket;
        $path = "gs://${bucket}/${client_code}/${guid}.${extension}";
        $file_type = $_FILES['content']['type'];

        move_uploaded_file($uploadFile, $path);
        $screenshot = '';
//        try {
//            $screenshot = $path . '.png';
//            $fs = fopen($path, 'rb');
//            $im = new Imagick();
//            $im->setResolution(400,400);
//            $im->readImage($path);
//            $im->setImageFormat('jpeg');
//            $im->writeImage($screenshot);
//            $im->clear();
//            $im->destroy();
//
//        }catch(Exception $e){
//            $screenshot = '';
//        }

        Analyses::create([
            'user_id' => $user_id,
            'client_id' => $client_id,
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