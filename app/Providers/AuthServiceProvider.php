<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [

    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        //register super admin
        $gate->define('SuperAdmin', function ($user) {
            return $user->roles()->where('name', 'SuperAdmin')->count() > 0;
        });
        //register admin
        $gate->define('Admin', function ($user) {
            return $user->roles()->where('name', 'Admin')->count() > 0;
        });
    }
}
