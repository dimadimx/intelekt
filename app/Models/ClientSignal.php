<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class ClientSignal
 * @package App\Models
 * @version April 11, 2020, 2:47 pm EEST
 *
 * @property \App\Models\Client client
 * @property integer client_id
 * @property string|\Carbon\Carbon date
 * @property number value
 */
class ClientSignal extends Model
{
    public $table = 'client_signals';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'client_id',
        'date',
        'value'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'client_id' => 'integer',
        'date' => 'datetime',
        'value' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'client_id' => 'required',
        'value' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }
}
