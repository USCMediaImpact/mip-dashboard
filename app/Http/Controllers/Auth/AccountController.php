<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
		return View('auth.account', [
            'roles' => $roles
        ]);
	}

    public function loadAccount(Request $request){
        $total = User::count();
        $filtered = User::count();
        $data = User::with('roles')->skip($request->start ?: 0)
            ->take($request->length ?: 25)
            ->get();

        return [
            'draw' => $request->draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data
        ];
    }

    public function invite(Request $request){
        return User::create([
            'email' => $request['email'],
        ]);
    }
}