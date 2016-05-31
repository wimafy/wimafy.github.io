<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
	
	protected $fillable = [
        'user_one_id', 'user_two_id', 'status', 'action_user_id'
    ];
	
    public function user(){
		$this->belongsToMany('User');
	}
}
