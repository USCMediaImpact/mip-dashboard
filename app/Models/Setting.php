<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    protected $table = 'settings';

    public $timestamps = false;

    protected $fillable = ['client_id', 'enable_sync', 'values'];

    public function client(){
        return $this->belongsTo('App\Models\Client');
    }
}
