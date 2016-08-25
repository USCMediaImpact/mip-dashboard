<?php
/**
 * Created by IntelliJ IDEA.
 * User: steve
 * Date: 2016-08-24
 * Time: 21:31
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataException extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'data_exceptions';

    protected $fillable = ['client_id', 'report_user_id', 'title', 'data_impact', 'resolution', 'begin_date', 'end_date', 'description', 'resolved'];

    public function reporter(){
        return $this->hasOne('App\Models\User', 'id', 'report_user_id');
    }
}