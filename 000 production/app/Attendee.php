<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    public function wims()
	{
		return $this->belongsToMany('App\Wim');
	}
}
