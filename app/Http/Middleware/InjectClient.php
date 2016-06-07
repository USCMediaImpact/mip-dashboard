<?php

namespace App\Http\Middleware;

use App\Models\Client;
use View;
use Closure;
use Symfony\Component\HttpFoundation\Cookie;

class InjectClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        /**
         * if super admin client id get from cookie
         * others use current user client info
         */
        if ($user->roles()->where('name', 'SuperAdmin')->count() > 0) {

            $clientId = $request->cookie('client-id');
            if ($clientId === null) {

                $client = Client::first()
                    ->select(['id', 'name', 'code'])
                    ->get()->toArray()[0];
            } else {
                $client = Client::where('id', $clientId)
                    ->select(['id', 'name', 'code'])
                    ->get()->toArray()[0];
            }
            $needSetCookie = true;
        } else {
            $client = $user->client->first();
        }

        View::share('client', $client);

        $request->attributes->add(['client' => $client]);
        //dd($request);
        $response = $next($request);

        if($needSetCookie){
            $response->headers->setCookie(new Cookie('client-id', $client['id']));
        }
        return $response;
    }
}
