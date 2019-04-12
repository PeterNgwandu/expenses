<?php

namespace App\BudgetLevel;

use Illuminate\Database\Eloquent\Model;

class BudgetLevelTen extends Model
{
    protected $fillable = [
        'top_level_id',
        'title',
        'level_number',
    ];
}
