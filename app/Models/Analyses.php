<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Analyses extends Model
{
    use SoftDeletes;

    protected $table = 'analyses';

    protected $dates = ['deleted_at'];

    protected $fillable = ['file_id', 'file_name', 'file_type', 'description', 'screen_shot', 'path'];
}
