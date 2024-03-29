<?php

namespace App\Http\Middleware;

use App\Models\Client;
use View;
use Crypt;
use Closure;
use Symfony\Component\HttpFoundation\Cookie;

class InjectClientInfo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function handle($request, Closure $next)
    {
        $needSetCookie = false;
        $user = $request->user();


        /**
         * for super admin client id get from cookie
         * others used current user client id
         */
        if ($user->roles()->where('name', 'SuperAdmin')->count() > 0) {
            $request->merge(['isSuperAdmin' => true]);
            $clientId = $request->cookie('client-id');
            if (!$clientId) {
                $client = Client::first()
                    ->select(['id', 'name', 'code', 'website'])
                    ->get()->toArray()[0];
            } else {
                $client = Client::where('id', $clientId)
                    ->select(['id', 'name', 'code', 'website'])
                    ->get()->toArray()[0];
            }

            $needSetCookie = true;
            $allClient = Client::select(['id', 'name'])
                ->where('ready', true)
                ->get()
                ->toArray();
            View::share('allClient', array_map(function($row){
                return array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'value' => Crypt::encrypt($row['id'])
                );
            }, $allClient));
        }
        else {
            $client = $user->client->toArray();
        }

        View::share('client', $client);

        $request->merge(['client' => $client]);

        $response = $next($request);

        if($needSetCookie){
            $clientCookie = new Cookie('client-id', $client['id'], 0, '/', null, false, false);
            $response->headers->setCookie($clientCookie);
        }
        return $response;
    }
}
