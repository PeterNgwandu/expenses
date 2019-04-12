<?php

namespace App\BudgetLevel;

use Illuminate\Database\Eloquent\Model;

class BudgetLevelFour extends Model
{
    protected $fillable = [
        'top_level_id',
        'title',
        'level_number',
    ];
}
