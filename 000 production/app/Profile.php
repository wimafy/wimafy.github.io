<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
	protected $fillable = ['FirstName', 'LastName', 'PhoneNumber', 'city', 'state', 'interests', 'bio'];
	
	protected $table = 'profiles';
	
	public function user() {
		return $this->belongsTo('App\User');
	}
	
}


