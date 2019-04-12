<?php

namespace App\ExpenseRetirement;

use Illuminate\Database\Eloquent\Model;

class ExpenseRetirement extends Model
{
    protected $fillable = [
		'budget_id',
    	'item_id',
    	'account_id',
    	'supplier_id',
    	'ret_no',
    	'ref_no',
    	'purchase_date',
    	'item_name',
    	'unit_measure',
    	'unit_price',
    	'vat',
    	'vat_amount',
    	'gross_amount',
        'description',
        'status',
    ];
}
