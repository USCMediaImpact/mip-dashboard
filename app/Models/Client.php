<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $table = 'clients';

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'website', 'code'];

    public function users(){
        return $this->hasMany('App\Models\User');
    }
    
    public function setting(){
        return $this->hasOne('App\Models\Setting');
    }
}
