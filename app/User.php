<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_id',
        'area_id',
        'role_id',
        'name',
        'gender',
        'maritial_status',
        'phone',
        'other_phone',
        'address',
        'photo',
        'npwp',
        'no_bpjs',
        'no_ktp',
        'ktp_image',
        'no_kk',
        'kk_image',
        'bank_name',
        'bank_owner',
        'bank_number',
        'start_date',
        'end_date',
        'signature',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'api_token',
        'fcm_token',
        'leave',
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
        'area_id' => 'array',
    ];

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }

    public function area()
    {
        if ($this->area_id) {
            return Area::whereIn('id', $this->area_id)->get();
        } else {
            return [];
        }
    }
}
