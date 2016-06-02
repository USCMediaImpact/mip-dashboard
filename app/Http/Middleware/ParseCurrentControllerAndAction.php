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
        $controllerAtAction = ltrim($routeInfo['controller'], $routeInfo['namespace']);
        $info = explode('@', $controllerAtAction);

        View::share('controller', $info[0]);
        View::share('action', $info[1]);

        return $next($request);
    }
}
