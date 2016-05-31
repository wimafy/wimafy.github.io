<?php

namespace App\Api\V1\Controllers;

use JWTAuth;
use Validator;
use Config;
use Log;
use DB;
use App\Relationship;
use Input;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Exception\ValidationHttpException;

class RelationshipController extends Controller
{
	use Helpers;
	
	//Queries the table to find all users. This way users can look for friends to add or view their friend's profiles.
	public function index()
	{
		$currentUser = JWTAuth::parseToken()->authenticate();

		$users = DB::select('SELECT
								id,
								username,
								email,
								FirstName,
								LastName
							 FROM
								users');
		return $users;
	}
	
	//Creates a new row in the friends table when a user sends a friend request. 
	public function sendRequest(Request $request)
	{
		$currentUser = JWTAuth::parseToken()->authenticate();
		
		//check if relationship status exists already. if true, tell user the status. else create new relationship
			//TODO
			
		//create a new relationship model
		$relationship = new Relationship;
			
		//set the current user to action user and check which user has lower userID value.
		$relationship->action_user_id = $currentUser->id;;
		$relationship->user_one_id = $currentUser->id;;
		$relationship->user_two_id = $request->get('friend_id');
		$relationship->status = 0;
			
		//attempt to save the relationship in the relationships table
		if($relationship->save())
			return $this->response->created();
		else
			return $this->response->error('could_not_send_request', 500);
		
	}
	
	//Queries the relationships table and returns an array of all the friend requests for the current user.
	public function viewRequests()
	{
		$currentUser = JWTAuth::parseToken()->authenticate();
		
		$friendRequests = DB::table('users')
			->select('users.id','users.username')
			->join('relationships', 'relationships.user_one_id','=','users.id')
			->where('relationships.status','=',0)
			->where('relationships.user_two_id', '=', $currentUser->id)
			->get();
			
		return $friendRequests;
		
	}

	//Queries the relationship table and returns one record. Updates the status to 1, then saves.
	public function acceptRequest($senderID)
	{
		$currentUser = JWTAuth::parseToken()->authenticate();
		$friendRequest = Relationship::where('user_two_id', '=', $currentUser->id)
			->where('user_one_id', '=', $senderID) 
			->where('status', '=', '0')
			->first();

		$friendRequest->status = 1;
		$friendRequest->action_user_id = $currentUser->id;;
		Log::info($friendRequest);
		if($friendRequest->save())
			return $this->response->noContent();
		else
			return $this->response->error('could_not_accept_request', 500);
	}
	
	public function denyRequest($senderID)
	{
		$currentUser = JWTAuth::parseToken()->authenticate();
		
		$friendRequest = Relationship::where('user_two_id', '=', $currentUser->id)
			->where('user_one_id', '=', $senderID) 
			->where('status', '=', '0')
			->first();

		$friendRequest->status = 2;
		$friendRequest->action_user_id = $currentUser-id;;
		if($friendRequest->save())
			return $this->response->noContent();
		else
			return $this->response->error('could_not_accept_request', 500);
	}
	
	public function blockRelationship()
	{
		//TODO check if relationship already exists. If true, set status = 3. Else create new relationship with status = 3.
	}
	
	public function viewFriends()
	{
		$currentUser = JWTAuth::parseToken()->authenticate();
		
		$friendsList1 = DB::table('users')
			->select('users.id', 'users.FirstName', 'users.LastName', 'users.username')
			->join('relationships', 'relationships.user_one_id','=','users.id')
			->where('relationships.status','=',1)
			->where('relationships.user_two_id', '=', $currentUser->id)
			->get();
		
		$friendsList2 =	DB::table('users')
			->select('users.id', 'users.FirstName', 'users.LastName', 'users.username')
			->join('relationships', 'relationships.user_two_id', '=', 'users.id')
			->where('relationships.status', '=', 1)
			->where('relationships.user_one_id', '=', $currentUser->id)
			->get();
			
		$friendsList = array_merge($friendsList1, $friendsList2);
		 	
		return $friendsList;
	}
}
