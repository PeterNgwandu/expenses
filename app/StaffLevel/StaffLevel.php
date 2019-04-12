<?php

namespace App\StaffLevel;

use App\User;
use Illuminate\Database\Eloquent\Model;

class StaffLevel extends Model
{
    protected $fillable = [
        'name',
    ];

    public function users() {
    	return $this->hasMany(User::class);
    }

    public function limits() {
    	return $this->belongsTo(Limit::class);
    }
}
