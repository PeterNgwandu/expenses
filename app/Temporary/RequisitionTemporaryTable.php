<?php

namespace App\Temporary;

use Illuminate\Database\Eloquent\Model;

class RequisitionTemporaryTable extends Model
{
    protected $cast = [
    	'item_name' => 'array',
    	'description' => 'array',
    	'unit_measure' => 'array',
    	'unit_price' => 'array',
    	'quantity' => 'array',

    ];

    protected $fillable = [
    	'req_no',
            'item_id',
            'account_id',
            'user_id',
            'item_name',
            'description',
            'unit_measure',
            'unit_price' ,
            'quantity' 
    ];
}
