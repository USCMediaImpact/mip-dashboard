<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Mail;
use Config;
use DB;
use Ramsey\Uuid\Uuid;
use App\Models\User;
use App\Models\Role;
use App\Models\Client;
use App\Http\Controllers\AuthenticatedBaseController;

class AccountController extends AuthenticatedBaseController
{
    static $bucket = 'mip-dashboard-upload';

    public function __construct()
    {
        $this->middleware('routeInfo');
        $this->middleware('auth');
        $this->middleware('clientInfo');
    }

    public function showAccount(Request $request){
        $client_id = $request['client']['id'];

        $roles = Role::where('name', '<>', 'SuperAdmin')
            ->where('name', '<>', 'Test')
            ->get();

        $detail = Client::where('id', $client_id)->first();
		return view('auth.account', [
            'roles' => $roles,
            'detail' => $detail
        ]);
	}

    public function loadAccount(Request $request){
        $client_id = $request->user()->client->id;
        $search = $request->input('search.value');

        $superAdminAndTest = DB::select(DB::raw("SELECT user_id FROM user_role ur INNER JOIN roles r ON ur.role_id = r.id WHERE r.name IN ('SuperAdmin' , 'Test')"));
        $superAdminAndTest = array_map(function($i){
            return $i->user_id;
        }, $superAdminAndTest);

        $query = User::with('roles')
            ->where('client_id', $client_id)
            ->whereNotIn('id', $superAdminAndTest);

        $total = $query->count();
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
        $client_id = $request->user()->client->id;
        return Client::where('id', $client_id)
            ->select('name', 'gtm', 'email_newsletter', 'ga', 'logo')
            ->first();
    }

    public function saveClientInfo(Request $request){
        $client_id = $request['client']['id'];
        $client_code = $request['client']['code'];
        $client = Client::where('id', $client_id)->first();
        if($client !== null){
            $client->update([
                'name' => $request['name'],
                'gtm' => $request['gtm'],
                'email_newsletter' => $request['email_newsletter'],
                'ga' => $request['ga'],
            ]);
            if($_FILES['logo']['tmp_name']){
                $name = $_FILES['logo']['name'];
                $extension = pathinfo($name)['extension'];
                $uploadFile = $_FILES['logo']['tmp_name'];
                $guid = Uuid::uuid4()->toString();
                $bucket = $this::$bucket;
                $path = "gs://${bucket}/${client_code}/${guid}.${extension}";
                move_uploaded_file($uploadFile, $path);
            }
        }
        return redirect(action('Auth\AccountController@showAccount'));
    }

    public function invite(Request $request){
        $client_id = $request->user()->client->id;

        $dbUser = User::where('email', $request['email'])->first();
        if($dbUser){
            return array('success'=>false, 'message'=>sprintf('email %s already been invited!', $dbUser->email));
        }

        $newUser = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'client_id' => $client_id
        ]);
        $newUser->save();

        $roles = DB::table('roles')
            ->wherein('id', $request['role'] ?: [])
            ->where('name', '<>', 'SuperAdmin')
            ->get(['id']);
        $roles = array_map(function($row){
            return $row->id;
        }, $roles);
        $newUser->roles()->sync($roles);

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
        $client_id = $request->user()->client->id;
        return User::with('roles')
            ->where('id', $userId)
            ->where('client_id', $client_id)
            ->first();
    }

    public function editAccount(Request $request){
        $client_id = $request->user()->client->id;
        $user = User::where('id', $request['id'])
            ->where('client_id', $client_id)
            ->first();
        if($user === null){
            return array('success'=>false, 'message' => 'user not exist');
        }
        $user->update([
            'name' => $request['name'],
        ]);
        $roles = DB::table('roles')
            ->wherein('id', $request['role'] ?: [])
            ->where('name', '<>', 'SuperAdmin')
            ->get(['id']);
        $roles = array_map(function($row){
            return $row->id;
        }, $roles);
        $user->roles()->sync($roles);
        return array('success' => true, $user);
    }

    public function removeAccount(Request $request, $userId){
        $client_id = $request->user()->client->id;
        $user = User::where('id', $userId)
            ->where('client_id', $client_id)
            ->first();

        if($user !== null) {
            $user->roles()->detach();
            $user->delete();
        }
        return array('success'=>true);
    }
}