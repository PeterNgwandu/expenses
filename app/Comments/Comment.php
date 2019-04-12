<?php

namespace App\Comments;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
    	'req_id',
    	'body',
    ];

    public function requisitions()
    {
    	return $this->belongsTo(Requisition::class);
    }
}
