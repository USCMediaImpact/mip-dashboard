<?php

namespace App\Http\Middleware;

use Closure;
use Route;
use View;

class ParseCurrentControllerAndAction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $routeInfo = Route::getCurrentRoute()->getAction();
        $preLenth = strlen($routeInfo['namespace']) + 1;

        $controllerAtAction = substr($routeInfo['controller'], $preLenth);
        $info = explode('@', $controllerAtAction);
        
        View::share('controller', $info[0]);
        View::share('action', $info[1]);

        return $next($request);
    }
}
