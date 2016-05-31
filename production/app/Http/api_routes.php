<?php
//Dingo uses it own router to avoild conflicts with the main application router	
$api = app('Dingo\Api\Routing\Router');
//Creating a version group allows for the same endpoint to be used on multiple versions
$api->version('v1', function ($api) {
	//POST routes used when application accesses the API
	$api->post('auth/login', 'App\Api\V1\Controllers\AuthController@login');
	$api->post('auth/signup', 'App\Api\V1\Controllers\AuthController@signup');
	$api->post('auth/recovery', 'App\Api\V1\Controllers\AuthController@recovery');
	$api->post('auth/reset', 'App\Api\V1\Controllers\AuthController@reset');
	
	//Routes for profiles
	$api->group(['middleware' => 'api.auth'], function ($api) {
		$api->get('profile', 'App\Api\V1\Controllers\ProfileController@index');
	});
	
	//Routes for Wims
	$api->group(['middleware' => 'api.auth'], function ($api) {
		$api->get('wims', 'App\Api\V1\Controllers\WimController@index');
		//$api->get('wims/{id}', 'App\Api\V1\Controllers\WimController@show');
		$api->post('wims', 'App\Api\V1\Controllers\WimController@store');
		//$api->put('wims/{id}', 'App\Api\V1\Controllers\WimController@update');
		//$api->delete('wims/{id}', 'App\Api\V1\Controllers\WimController@destroy');
	});
	
	//Routes for relationships
	$api->group(['middleeware' => 'api.auth'], function ($api) {
		$api->get('relationship', 'App\Api\V1\Controllers\RelationshipController@index');
		$api->post('relationship', 'App\Api\V1\Controllers\RelationshipController@sendRequest');
		$api->get('relationship/viewRequests', 'App\Api\V1\Controllers\RelationshipController@viewRequests');
		$api->put('relationship/acceptRequest/{senderID}', 'App\Api\V1\Controllers\RelationshipController@acceptRequest');
		$api->put('relationship/denyRequest/{senderID}', 'App\Api\V1\Controllers\RelationshipController@denyRequest');
		$api->get('relationship/viewFriends', 'App\Api\V1\Controllers\RelationshipController@viewFriends');
	});
	//GET example of protected route
	$api->get('protected', ['middleware' => ['api.auth'], function () {		
		return \App\User::all();
    }]);
	//GET example of free route
	$api->get('free', function() {
		return \App\User::all();
	});
});