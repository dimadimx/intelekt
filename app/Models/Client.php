<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Client
 * @package App\Models
 * @version February 18, 2020, 8:54 am UTC
 *
 * @property \App\Models\User user
 * @property \Illuminate\Database\Eloquent\Collection clientStatistics
 * @property integer user_id
 * @property integer api_uid
 * @property integer api_gid
 * @property integer api_belong_uid
 * @property string login
 * @property string phone
 * @property string|\Carbon\Carbon registration
 * @property boolean warning
 */
class Client extends Model
{
    use SoftDeletes;

    public $table = 'clients';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'user_id',
        'api_uid',
        'api_gid',
        'api_belong_uid',
        'login',
        'phone',
        'registration',
        'warning'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'api_uid' => 'integer',
        'api_gid' => 'integer',
        'api_belong_uid' => 'integer',
        'login' => 'string',
        'phone' => 'string',
        'registration' => 'datetime',
        'warning' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'api_uid' => 'required',
        'login' => 'required',
        'registration' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function clientStatistics()
    {
        return $this->hasMany(\App\Models\ClientStatistic::class, 'client_id');
    }
}
