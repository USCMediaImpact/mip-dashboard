<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class AuthenticatedBaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('routeInfo');
        $this->middleware('auth');
    }
}