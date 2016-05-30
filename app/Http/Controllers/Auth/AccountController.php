<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;
use Config;
use App\User;
use App\Role;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAccount(){
        $roles = Role::all();
		return view('auth.account', [
            'roles' => $roles
        ]);
	}

    public function loadAccount(Request $request){
        $search = $request->input('search.value');

        $total = User::count();
        $query = User::whereRaw("1=1");
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
        $dbUser = User::where('email', $request['email'])->first();
        if($dbUser){
            return array('success'=>false, 'message'=>sprintf('email %s already been invited!', $dbUser->email));
        }

        $newUser = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
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

    public function removeAccount(Request $request, $userId){
        $user = User::where('id', $userId)->first();
        if($user !== null) {
            $user->roles()->detach();
            $user->delete();
        }
        return array('success'=>true);
    }
}