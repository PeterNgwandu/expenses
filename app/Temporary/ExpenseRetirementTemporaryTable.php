<?php

namespace App\Temporary;

use Illuminate\Database\Eloquent\Model;

class ExpenseRetirementTemporaryTable extends Model
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
    ];
}
