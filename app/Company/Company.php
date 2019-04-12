<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'company_name',
        'location',
        'description',
    ];

    public function users() {
        return $this->hasMany(User::class);
    }
}
