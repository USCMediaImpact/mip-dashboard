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
use ZipArchive;

class AnalysesController extends AuthenticatedBaseController
{
    static $bucket = 'mip-dashboard-upload';

    public function show(Request $request)
    {
        $client_id = $request['client']['id'];
        $data = Analyses::where('client_id', $client_id)
            ->orderby('created_at', 'desc')
            ->get();
        return view('analyses.index', ['data' => $data]);
    }

    public function display(Request $request, $guid)
    {
        $file = Analyses::where('file_id', $guid)->get();
        if ($file) {
            $path = $file[0]->path;
            $name = $file[0]->file_name;
            $type = $file[0]->file_type;
            return response()->make(file_get_contents($path), 200, [
                'Content-Type' => $type,
                'Content-Disposition' => 'inline; filename="' . $name . '"'
            ]);
        }
    }

    public function download(Request $request)
    {
        $file_id_array = $request['file_id'];
        if (is_array($file_id_array) && count($file_id_array) > 0) {
            $client_id = $request['client']['id'];
            $data = Analyses::where('client_id', $client_id)
                ->whereIn('file_id', $file_id_array)
                ->get();

            if (count($data) > 0) {
                $bucket = 'dashboard-php-storage';
                $tmp_name = Uuid::uuid4()->toString();
                $path = "gs://${bucket}/download/${tmp_name}.zip";
                $zip = new ZipArchive();
                $zip_debug = $zip->open($path, ZipArchive::CREATE);
                dd($zip_debug);
                foreach ($data as $item) {
                    $zip->addFile($item->path, $item->file_name);
                }
                $zip->close();
                return response()->download($path, "Archive.zip");
            }
        }
    }

    public function upload(Request $request)
    {
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
        switch ($extension){
            case 'pdf':
                $screenshot = '/images/pdf.png';
                break;
            case 'xls':
            case 'xlsx':
                $screenshot = '/images/excel.png';
                break;
            case 'ppt':
            case 'pptx':
                $screenshot = '/images/ppt.png';
                break;
            case 'doc':
            case 'docx':
                $screenshot = '/images/word.png';
                break;
            default:
                $screenshot = '/images/file.png';
                break;
        }

//        $tmp_dir = sys_get_temp_dir();
//        $tmp = tempnam($dir, “foo”);
//        file_put_contents($tmp, “hello”)
//        $f = fopen($tmp, “a”);
//        fwrite($f, “ world”);
//        fclose($f)
//        echo file_get_contents($tmp);


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

    public function edit(Request $request)
    {
        $client_id = $request['client']['id'];
        $client_code = $request['client']['code'];
        $file_id = $request['file_id'];
        $description = $request['description'];
        $pdf = Analyses::where('client_id', $client_id)
            ->where('file_id', $file_id)
            ->first();
        if ($pdf !== null) {
            $pdf->update([
                'description' => $description
            ]);
            return [
                'success' => true,
                'file_id' => $file_id,
                'description' => $description
            ];
        }else{
            return ['success' => false, 'message' => 'file not exists'];
        }

    }

    public function delete(Request $request)
    {

        $client_id = $request['client']['id'];
        $client_code = $request['client']['code'];
        $file_id = $request['file_id'];
        $pdf = Analyses::where('client_id', $client_id)
            ->whereIn('file_id', $request['file_id'])
            ->delete();

        return ['success' => true, 'file_id' => $request['file_id']];
    }
}