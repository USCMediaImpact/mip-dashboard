<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StorageController extends Controller
{
    public function __construct()
    {

    }

    public function showLogo(Request $request, $client_code)
    {
        $client = Client::where('code', $client_code)->first();
        
        if ($client) {
            return response()->make(file_get_contents($client->logo), 200, [
                'Content-Type' => 'image/png'
            ]);
        }else{
            return abort(404);
        }
    }
}