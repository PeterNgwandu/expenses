<?php

namespace App\Comments;

use Illuminate\Database\Eloquent\Model;

class RetirementComment extends Model
{
    protected $fillable = [
    	'ret_no',
    	'body',
    ];
}
