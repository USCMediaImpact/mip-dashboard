<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    public $timestamps = false;
    
    protected $hidden = ['pivot'];

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_role');
    }
}
