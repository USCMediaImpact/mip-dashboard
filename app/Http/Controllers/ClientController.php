<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\User;
use App\Client;

class ClientController extends AuthenticatedBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->authorize('SuperAdmin');
    }

    public function showPage(){
        return view('superAdmin.clients');
    }

    public function loadClient(Request $request){
        $search = $request->input('search.value');

        $total = Client::count();
        $query = Client::whereRaw("1=1");
        if($search !== null){
            $query = $query->where(function($q) use($search){
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('website', 'like', '%'.$search.'%')
                    ->orWhere('code', 'like', '%'.$search.'%');

            });
        }
        $filtered = $query->count();
        $data = $query->skip($request->start ?: 0)
            ->take($request->length ?: 25)
            ->orderby('created_at')
            ->get();

        return [
            'draw' => $request->draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data
        ];
    }

    public function getClient(Request $request, $clientId){
        return Client::where('id', $clientId)
            ->first();
    }

    public function saveClient(Request $request){
        $client = Client::where('id', $request['id'])->first();
        if($client === null){
            $client = Client::create([
                'name' => $request['name'],
                'website' => $request['website'],
                'code' => $request['code']
            ]);
            $client->save();
        } else {
            $client->update([
                'name' => $request['name'],
                'website' => $request['website'],
                'code' => $request['code']
            ]);
        }

        return array('success' => true, $client);
    }

    public function removeClient(Request $request, $clientId){
        $client = Client::where('id', $clientId)->first();
        if($client !== null) {
            $client->delete();
        }
        return array('success'=>true);
    }
}