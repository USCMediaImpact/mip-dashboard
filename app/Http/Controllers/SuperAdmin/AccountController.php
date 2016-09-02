<?php

namespace app\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use Mail;
use Config;
use DB;
use App\Models\User;
use App\Models\Role;
use App\Models\Client;
use App\Http\Controllers\AuthenticatedBaseController;
use Ramsey\Uuid\Uuid;
use Google\Cloud\Storage\StorageClient;

class AccountController extends AuthenticatedBaseController
{
    static $bucket = 'mip-dashboard-upload';

    public function __construct()
    {
        parent::__construct();
        $this->authorize('SuperAdmin');
    }


    public function showPage(){
        $roles = Role::get();
        $clients = Client::get();
        return view('superAdmin.accounts', [
            'roles' => $roles,
            'clients' => $clients
        ]);
    }

    public function loadAccount(Request $request){
        $search = $request->input('search.value');

        $total = User::count();
        $query = User::with('client');
        if($search !== null){
            $query = $query->where(function($q) use($search){
                $q->where('email', 'like', '%'.$search.'%')
                    ->orWhere('name', 'like', '%'.$search.'%');
            });
        }

        $filtered = $query->count();
        $data = $query->with('roles')
            ->skip($request->start ?: 0)
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

    public function loadClientInfo(Request $request){
        $client_id = $request['client_id'];
        return Client::where('id', $client_id)
            ->select('id', 'name', 'gtm', 'email_newsletter', 'ga', 'logo')
            ->first();
    }

    public function saveClientInfo(Request $request){
        $client_id = $request['client_id'];
        $client = Client::where('id', $client_id)->first();
        $client_code = $client->code;
        if($client !== null){
            $client->update([
                'name' => $request['name'],
                'gtm' => $request['gtm'],
                'email_newsletter' => $request['email_newsletter'],
                'ga' => $request['ga'],
            ]);
            if($_FILES['logo']['tmp_name']){
                $storage = new StorageClient([
                    'projectId' => 'mip-dashboard'
                ]);
                $bucket = $storage->bucket($this::$bucket);


                $name = $_FILES['logo']['name'];
                $extension = pathinfo($name)['extension'];
                $uploadFile = $_FILES['logo']['tmp_name'];
                $guid = Uuid::uuid4()->toString();
                $bucket = $this::$bucket;
                $path = "gs://${bucket}/${client_code}/${guid}.${extension}";
                move_uploaded_file($uploadFile, $path);
                $client->update([
                    'logo' => $path
                ]);
            }
        }
        return redirect(action('SuperAdmin\AccountController@showPage'));
    }

    public function invite(Request $request){
        $dbUser = User::where('email', $request['email'])->first();
        if($dbUser){
            return array('success'=>false, 'message'=>sprintf('email %s already been invited!', $dbUser->email));
        }

        $client = Client::where('id', $request['client_id'])
            ->select(['id'])
            ->first();

        $newUser = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'client_id' => $client['id'] ?: null
        ]);


        $requestRoles = $request->input('role') ?: [];
        foreach($requestRoles as $role){
            $dbRoleId = Role::where('id', $role)->value('id');
            if($dbRoleId !== null){
                $newUser->roles()->attach($dbRoleId);
            }
        }
        $newUser->save();

        $token = app('auth.password.tokens')->create($newUser);
        Mail::send('emails.welcome', [
            'user' => $newUser,
            'token' => $token
        ], function ($message) use ($newUser) {
            $message->to($newUser->email);
            $message->subject('You have been invited!');
        });

        return array('success'=>true);
    }

    public function getAccount(Request $request, $userId){
        return User::with('roles')->with('client')
            ->where('id', $userId)
            ->first();
    }

    public function saveAccount(Request $request){
        $user = User::where('id', $request['id'])->first();
        if($user === null){
            return array('success'=>false, 'message' => 'user not exist');
        }
        $client = Client::where('id', $request['client_id'])
            ->select(['id'])
            ->first();

        $user->update([
            'name' => $request['name'],
            'client_id' => $client['id'] ?: null
        ]);

        $roles = DB::table('roles')
            ->wherein('id', $request['role'] ?: [])
            ->get(['id']);
        $roles = array_map(function($row){
            return $row->id;
        }, $roles);
        $user->roles()->sync($roles);

        return array('success' => true, $user);
    }

    public function removeAccount(Request $request, $userId){
        $user = User::where('id', $userId)->first();
        if($user !== null) {
            $user->roles()->detach();
            $user->delete();
        }
        return array('success'=>true);
    }
}