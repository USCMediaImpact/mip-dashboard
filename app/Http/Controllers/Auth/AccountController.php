<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Mail;
use Config;
use DB;
use App\Models\User;
use App\Models\Role;
use App\Http\Controllers\AuthenticatedBaseController;

class AccountController extends AuthenticatedBaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('clientInfo');
    }

    public function showAccount(){
        $roles = Role::where('name', '<>', 'SuperAdmin')
            ->where('name', '<>', 'Test')
            ->get();
		return view('auth.account', [
            'roles' => $roles
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
            'name' => $request['name']
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