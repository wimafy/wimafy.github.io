<?php

namespace App\Api\V1\Controllers;

use JWTAuth;
use Validator;
use Config;
use Log;
use DB;
use App\Profile;
use Input;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Exception\ValidationHttpException;

class ProfileController extends Controller
{
	use Helpers;
	
    public function index()
	{
		$currentUser = JWTAuth::parseToken()->authenticate();
		
		$profile = DB::select('SELECT * FROM profiles WHERE profiles.profileID = :id', ['id' => $currentUser["id"]]);
		
		return $profile;
	}
	
	public function update(Request $request)
	{
		$currentUser = JWTAuth::parseToken()->authenticate();
		
		$profile = $currentUser->profiles()->get();
		if(!$profile)
			throw new NotFoundHttpException;
		
		$profile->fill($request->all());
		if($profile->save())
			return $this->response->noContent();
		else
			return $this->response->error('could_not_update_book', 500);
	}
}

