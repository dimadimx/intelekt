<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Imtigger\LaravelJobStatus\JobStatus;

/**
 * Class User
 * @package App\Models
 * @version February 25, 2020, 8:42 am UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection clients
 * @property string name
 * @property string email
 * @property string|\Carbon\Carbon email_verified_at
 * @property string password
 * @property string api_user
 * @property string api_password
 * @property integer api_gid
 * @property integer api_uid
 * @property number price
 * @property string remember_token
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'api_user',
        'api_password',
        'api_gid',
        'api_uid',
        'price',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name'                  => 'required',
        'email'                 => 'required|email|unique:users,email',
        'password'              => 'required|confirmed'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function clients()
    {
        return $this->hasMany(\App\Models\Client::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function jobs()
    {
        return $this->hasMany(JobStatus::class, 'input');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function jobsActive()
    {
        return $this->hasMany(JobStatus::class, 'input')->get()->filter(function ($value, $key) {
            return $value->is_executing;
        });
    }
}
