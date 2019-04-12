<?php

namespace App\Item;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
    	'budget_id',
        'account_id',
    	'item_no',
    	'item_name',
    	'description',
    	'unit_price',
    	'unit_measure',
    	'qunatity',
    	'total',
    ];
}
