<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wim extends Model
{
    protected $fillable = ['title', 'location', 'address', 'date', 'time', 'transportation', 'attire', 'description'];
	
	protected function users()
	{
		return $this->belongsToMany('App\User');
	}
	
	protected function attendees()
	{
		return $this->hasMany('App\Attendee');
	}
}
