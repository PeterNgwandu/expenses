<?php

namespace App\BudgetLevel;

use Illuminate\Database\Eloquent\Model;

class BudgetLevelSeven extends Model
{
    protected $fillable = [
        'top_level_id',
        'title',
        'level_number',
    ];
}
