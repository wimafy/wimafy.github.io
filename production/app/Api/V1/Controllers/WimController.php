<?php

namespace App\Api\V1\Controllers;

use JWTAuth;
use Validator;
use Config;
use Log;
use DB;
use App\Wim;
use App\Attendee;
use Input;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Exception\ValidationHttpException;

class WimController extends Controller
{
    use Helpers;
	
	//This method retrieves all wims that are associated with the current user from the database
	public function index()
	{
		//Gets the current user info
		$currentUser = JWTAuth::parseToken()->authenticate();
		
		//Creates an array of wims the user is associated with
		$wims = DB::select('SELECT * FROM attendees INNER JOIN wims ON attendees.wim_id = wims.id WHERE attendees.user_id = :id', [ 'id' => $currentUser["id"]]);
		
		return $wims;
	}

	//This method is used to add a new wim to the database and associate it with the friends invited to it and the creator
	public function store(Request $request)
	{
		//Gets the current user info
		$currentUser = JWTAuth::parseToken()->authenticate();
		
		//Creates a new instance of the Wim model
		$wim = new Wim;
		
		//Fills in the required fields for the new wim that the wims table expects
		$wim->user_id = $currentUser->id;;
		$wim->title = $request->get('title');
		$wim->location = $request->get('location');
		$wim->address = $request->get('address');
		$wim->date = $request->get('date');
		$wim->time = $request->get('time');
		$wim->transportation = $request->get('transportation');
		$wim->attire = $request->get('attire');
		$wim->description = $request->get('description');
		//If the wim creation was successful send Yay, if not send error
		if($wim->save())
			$wimBool = true;
		else
			$wimBool = false;
		//Creates a new instance of the Attendess to the wim event (this one is used to create an instance for the current user)
		$attendee = new Attendee;
		$attendee->wim_id = $wim->id;
		$attendee->user_id = $currentUser->id;
		$attendee->save();
		//For each of the friends that were invited to the wim event, create a new instance in the attendee's table
		foreach ($request->get('friends') as $key => $value) {
			
			$attendee = new Attendee;
			$attendee->wim_id = $wim->id;
			$attendee->user_id = $value;
			if($attendee->save())
				$attendeeBool = true;
			else
				$attendeeBool = false;
		}
		
		//If the wim creation was successful send Yay, if not send error
		if($wimBool and $attendeeBool)
			return $this->response->created();
		else
			return $this->response->error('could_not_create_wim', 500);
	}
	
	//Haven't implemented this stuff yet
	/*public function show($id)
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();
	    $wim = $currentUser->wims()->find($id);
	    if(!$wim)
	        throw new NotFoundHttpException; 
	    return $wim;
	}
	public function update(Request $request, $id)
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();
	    $wim = $currentUser->wims()->find($id);
	    if(!$wim)
	        throw new NotFoundHttpException;
	    $wim->fill($request->all());
	    if($wim->save())
	        return $this->response->noContent();
	    else
	        return $this->response->error('could_not_update_wim', 500);
	}
	public function destroy($id)
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();
	    $wim = $currentUser->wims()->find($id);
	    if(!$wim)
	        throw new NotFoundHttpException;
	    if($wim->delete())
	        return $this->response->noContent();
	    else
	        return $this->response->error('could_not_delete_wim', 500);
	}*/
}
