<?php

namespace App;
use App\StaffLevel\StaffLevel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'gender',
        'username',
        'dob',
        'hiring_date',
        'picture',
        'phone',
        'phone_alternative',
        'status',
        'department_id',
        'company_id',
        'stafflevel_id',
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

    public function companies() {
        return $this->belongsTo(Company::class);
    }

    public function departments() {
        return $this->belongsTo(Department::class);
    }

    public function staff_levels() {
        return $this->belongsTo('App\StaffLevel\StaffLevel','stafflevel_id','id');
    }

}
