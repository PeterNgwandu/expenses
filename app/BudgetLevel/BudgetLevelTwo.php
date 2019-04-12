<?php

namespace App\BudgetLevel;

use Illuminate\Database\Eloquent\Model;

class BudgetLevelTwo extends Model
{
    protected $fillable = [
        'top_level_id',
        'title',
        'level_number',
    ];
}
