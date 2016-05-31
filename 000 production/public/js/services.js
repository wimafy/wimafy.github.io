var wimServices = angular.module('wimServices', [
	'restangular',
	'LocalStorageModule',
	'ngFileUpload'
]);

wimServices.factory('userService', ['$http', 'Upload', 'localStorageService', function($http, Upload, localStorageService) {

	function checkIfLoggedIn(){
		if(localStorageService.get('token'))
			return true;
		else
			return false;
	}

	function signup(username, password, email, FirstName, LastName, birthday, gender, city, state, interests, bio, file, onSuccess, onError) {
		Upload.upload({
			url: '/api/auth/signup',
			data: {
				username: username,
				password: password,
				email: email,
				FirstName: FirstName,
				LastName: LastName,
				birthday: birthday,
				gender: gender,
				city: city,
				state: state,
				interests: interests,
				bio: bio,
				ProfilePic: file,
			}
		}).
		then(function(response) {
			localStorageService.set('token', response.data.token);
			onSuccess(response);
		}, function(response) {
			onError(response);
		});
	}

	function login(email, password, onSuccess, onError){

        $http.post('/api/auth/login', 
        {
            email: email,
            password: password
        }).
        then(function(response) {
            localStorageService.set('token', response.data.token);
            onSuccess(response);

        }, function(response) {

            onError(response);

        });

    }

    function logout(){
        localStorageService.remove('token');
    }

    function getCurrentToken(){
        return localStorageService.get('token');
    }
	
    return {
        checkIfLoggedIn: checkIfLoggedIn,
        signup: signup,
        login: login,
        logout: logout,
        getCurrentToken: getCurrentToken
    }

}]);

wimServices.factory('profileService', ['Restangular', 'userService', function(Restangular, userService){
	
	function index(onSuccess, onError){
		Restangular.one('api/profile').get().then(function(response){
			
			onSuccess(response);
			
		}, function(){
			
			onError(response);
		});
	}
	
	Restangular.setDefaultHeaders({ 'Authorization' : 'Bearer ' + userService.getCurrentToken() });
	
	return{
		index: index
	}
	
}]);

wimServices.factory('relationshipService', ['Restangular', 'userService', function(Restangular, userService){
	
	function getAll(onSuccess, onError){
		Restangular.all('api/relationship').getList().then(function(response){
			onSuccess(response);
			
		}, function(){
			onError(response);
		});
	}
	
	function sendRequest(data, onSuccess, onError){
		Restangular.all('api/relationship').post(data).then(function(response){
			onSuccess(response);
		}, function(response){
			onError(response);
		});
	}
	
	function viewRequests(onSuccess, onError){
		Restangular.all('api/relationship/viewRequests').getList().then(function(response){
			onSuccess(response);
		}, function(){
			onError(response);
		});
	}
	
	function acceptRequest(senderID, data, onSuccess, onError){
		Restangular.one('api/relationship/acceptRequest').customPUT(data, senderID).then(function(response){
			onSuccess(response);
		}, function(response){
			onError(response);
		});
	}
	
	function denyRequest(senderID, data, onSuccess, onError){
		Restangular.one('api/relationship/denyRequest').customPUT(data, senderID).then(function(response){
			onSuccess(response);
		}, function(response){
			onError(response);
		});
	}
	
	function viewFriends(onSuccess, onError){
		Restangular.all('api/relationship/viewFriends').getList().then(function(response){
			onSuccess(response);
		}, function(){
			onError(response);
		});
	}
	
	Restangular.setDefaultHeaders({ 'Authorization' : 'Bearer ' + userService.getCurrentToken() });
	
	return{
		getAll: getAll,
		sendRequest: sendRequest,
		viewRequests: viewRequests,
		acceptRequest: acceptRequest,
		denyRequest: denyRequest,
		viewFriends: viewFriends
	}
}]);

wimServices.factory('wimService', ['Restangular', 'userService', function(Restangular, userService) {

    //GET all the wims the current user is apart of from the API
    function getAll(onSuccess, onError){
        Restangular.all('api/wims').getList().then(function(response){

            onSuccess(response);
        
        }, function(){

            onError(response);

        });
    }

    //Haven't implemented this yet
    /*function getById(wimId, onSuccess, onError){
        Restangular.one('api/wims', wimId).get().then(function(response){
            onSuccess(response);
        }, function(response){
            onError(response);
        });
    }*/

    //POST a new wim to the API
    function create(data, onSuccess, onError){

        Restangular.all('api/wims').post(data).then(function(response){

            onSuccess(response);
        
        }, function(response){
            
            onError(response);
        
        });

    }

    //Haven't implemented this yet
    /*function update(wimId, data, onSuccess, onError){
        Restangular.one("api/wims").customPUT(data, wimId).then(function(response) {
                
                onSuccess(response);
            }, function(response){
                
                onError(response);
            
            }
        );
    }*/

    //Haven't implemented this yet
    /*function remove(wimId, onSuccess, onError){
        Restangular.one('api/wims/', wimId).remove().then(function(){
            onSuccess();
        }, function(response){
            onError(response);
        });
    }*/

    //Assign the user token into the GET/POST Http header to allow the user to communicate with the API
    Restangular.setDefaultHeaders({ 'Authorization' : 'Bearer ' + userService.getCurrentToken() });

    //Make these functions available to the controllers
    return {
        getAll: getAll,
        //getById: getById,
        create: create,
        //update: update,
        //remove: remove
    }

}]);
