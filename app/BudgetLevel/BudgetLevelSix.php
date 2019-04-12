<?php

namespace App\BudgetLevel;

use Illuminate\Database\Eloquent\Model;

class BudgetLevelSix extends Model
{
    protected $fillable = [
        'top_level_id',
        'title',
        'level_number',
    ];
}
