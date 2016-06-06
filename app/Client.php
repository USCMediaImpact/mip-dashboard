<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $table = 'clients';

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'website', 'code'];

    public function users(){
        return $this->hasMany('User');
    }
}
