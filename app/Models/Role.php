<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    public $timestamps = false;
    
    protected $hidden = ['pivot'];

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_role');
    }
}
