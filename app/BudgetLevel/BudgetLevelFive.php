<?php

namespace App\BudgetLevel;

use Illuminate\Database\Eloquent\Model;

class BudgetLevelFive extends Model
{
    protected $fillable = [
        'top_level_id',
        'title',
        'level_number',
    ];
}
