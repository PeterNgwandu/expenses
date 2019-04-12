<?php

namespace App\Department;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'company_id'
    ];

    public function users() {
        return $this->hasMany(User::class);
    }
}
