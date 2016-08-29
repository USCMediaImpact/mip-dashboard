<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password', 'client_id', 'create_at', 'update_at', 'last_login_date'];

    protected $hidden = ['password', 'remember_token', 'pivot'];

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'user_role');
    }

    public function client(){
        return $this->belongsTo('App\Models\Client');
    }
}
