<?php

namespace App\Retirement;

use Illuminate\Database\Eloquent\Model;

class Retirement extends Model
{
    protected $fillable = [
    	'req_no',
    	'budget_id',
    	'item_id',
    	'account_id',
        'user_id',
    	'ret_no',
        'supplier_id',
        'ref_no',
        'purchase_date',
    	'item_name',
    	'description',
    	'unit_measure',
    	'quantity',
    	'unit_price',
    	'vat',
    	'vat_amount',
    	'gross_amount',
    	'status',
    ];
}
