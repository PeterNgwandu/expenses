<?php

namespace App\Limits;

use Illuminate\Database\Eloquent\Model;

class Limit extends Model
{
    protected $fillable = [
    	'stafflevel_id',
    	'min_amount',
    	'max_amount',
    ];

    public function staff_level() {
    	return $this->hasMany(StaffLevel::class);
    }
}
