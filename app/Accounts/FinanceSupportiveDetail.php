<?php

namespace App\Accounts;

use Illuminate\Database\Eloquent\Model;

class FinanceSupportiveDetail extends Model
{
    protected $fillable = [
    	'req_no',
    	'serial_no',
    	'account_id',
    	'amount_paid',
    	'cash_collector',
    	'ref_no',
    	'comment',
    	'payment_date',
    ];
}
