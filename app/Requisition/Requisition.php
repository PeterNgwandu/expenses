<?php

namespace App\Requisition;

use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    protected $fillable = [
    	'budget_id',
    	'item_id',
    	'unit_measure',
    	'unit_price',
    	'quantity',
    	'description',
    	'total',
    ];

    public function comments()
    {
    	return $this->hasMany(Comment::class);
    }
}
