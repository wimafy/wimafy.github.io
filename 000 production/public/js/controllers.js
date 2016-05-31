var wimControllers = angular.module('wimControllers', ['ngFileUpload']);

wimControllers.controller('LoginController', ['userService', '$location', '$scope', '$http', function (userService, $location, $scope, $http) {

	$scope.login = function() {
        userService.login(
            $scope.email, $scope.password,
            function(response){
                $location.path('/');
            },
            function(response){
                alert('Something went wrong with the login process. Try again later!');
            }
        );
    }

    $scope.email = '';
    $scope.password = '';

    if(userService.checkIfLoggedIn())
        $location.path('/');

}]);

wimControllers.controller('SignupController', [ 'userService', '$scope', '$http', '$location', function (userService, $scope, $http, $location) {

	$scope.signup = function(file) {
		userService.signup(
			$scope.username, $scope.password, $scope.email, $scope.FirstName, $scope.LastName, $scope.birthday, $scope.gender, $scope.city, $scope.state, $scope.interests, $scope.bio, file,
			function(response){
				alert('Great! You are signed up! Welcome to WIM(afy), ' + $scope.FirstName + '!');
				$location.path('/');
			},
			function(response){
			alert('Something went wrong with the signup process. Try again later.');
			}
		);
	}
	
	$scope.username = '';
	$scope.password = '';
	$scope.email = '';
	$scope.FirstName = '';
	$scope.LastName = '';
	$scope.birthday = '';
	$scope.gender = '';
	$scope.city = '';
	$scope.state = '';
	$scope.interests = '';
	$scope.bio = '';
	file = '';
	
	if(userService.checkIfLoggedIn())
		$location.path('/');
	
}]);

wimControllers.controller('ProfileController', ['$scope', '$log', '$location', 'userService', 'profileService', '$http', function($scope, $log, $location, userService, profileService, $http){
	
	$scope.logout = function(){
		userService.logout();
		$location.path('/login');
	}
	
	$scope.refresh = function(){
		profileService.index(function(response){
			$scope.profile = response;
		}, function(){
			alert('Some errors occurred while communicating with the service. Try again later.');
		});
	
	}
	
	if(!userService.checkIfLoggedIn())
		$location.path('/login');
	
	$scope.profile = [];
	$scope.refresh();
}]);

wimControllers.controller('UserListController', ['$scope', '$location', 'userService', 'relationshipService', '$http', function ($scope, $location, userService, relationshipService, $http) {
	
	$scope.logout = function(){
		userService.logout();
		$location.path('/login');
	}
	
	$scope.sendFriendRequest = function(id){
		
		relationshipService.sendRequest({
			friend_id: id
		}, function(){
			alert('Friend request sent!');
		}, function(){
			alert('Some errors occurred while communicating with the service. RIP');
		});
	}
	
	$scope.refresh = function(){
		
		relationshipService.getAll(function(response){
			$scope.relationships = response;
		}, function(){
			alert('Some errors happened... oopsie');
		});
	};
	
	if(!userService.checkIfLoggedIn())
		$location.path('/login');
	$scope.relationships = [];
	$scope.refresh();
}]);

wimControllers.controller('RequestController', ['$scope', '$log', '$location', 'userService', 'relationshipService', '$http', function ($scope, $log, $location, userService, relationshipService, $http) {
	
	
	$scope.logout = function(){
		userService.logout();
		$location.path('/login');
	}
	
	$scope.acceptRequest = function(senderID){
		relationshipService.acceptRequest(senderID, function(){
			alert('Friend request accepted!');
		}, function(){
			alert('Some errors happened when accepting the friend request.');
		});
	}
	
	$scope.denyRequest = function(senderID){
		relationshipService.denyRequest(senderID, function(){
			alert('Friend request denied!');
		}, function(){
			alert('Some errors happened when denying the friend request.');
		});
	}
	
	$scope.refresh = function(){
		
		relationshipService.viewRequests(function(response){
			$scope.requests = response;
		}, function(){
			alert('Some errors happened... oopsie');
		});
	};
	
	if(!userService.checkIfLoggedIn())
		$location.path('/login');
	$scope.requests = [];
	$scope.refresh();
	

}]);

wimControllers.controller('FriendController', ['$scope', '$location', 'userService', 'relationshipService', '$http', function ($scope, $location, userService, relationshipService, $http) {
	
	$scope.logout = function(){
		userService.logout();
		$location.path('/login');
	}
	
	$scope.refresh = function(){
		relationshipService.viewFriends(function(response){
			$scope.friends = response;
		}, function(){
			alert('some errors happened...');
		});
	};
	
	if(!userService.checkIfLoggedIn())
		$location.path('/login');
	$scope.friends = [];
	$scope.refresh();
	
}]);

wimControllers.controller('WimController', ['$scope', '$location', 'userService', 'wimService', '$http', function ($scope, $location, userService, wimService, $http) {
    
    //Logs the user out and redirects to the login page
    $scope.logout = function(){

        //Removes the user's JWT
        userService.logout();

        //Redirects the the user to the login page
        $location.path('/login');
    }

    //Attempts to create a new Wim
    $scope.create = function(){
        
        //This is ghetto right now...Basically pulling the array of strings that represent friend ids and recreating the $scope.friends as an array of integers
		//TODO adjust this to get the actual id once the form actually generates a friends list.
        for(i = 0; i < $scope.friends.length; i++) {
            $scope.friends[i] = parseInt($scope.friends[i]);
        }

        //Sends the user supplied values to the wimService in services.js to create the new Wim
        wimService.create({
            title: $scope.currentWimTitle,
            location: $scope.currentWimLocation,
            address: $scope.currentWimAddress,
            date: $scope.currentWimDate,
			time: $scope.currentWimTime,
			transportation: $scope.currentWimTransportation,
            attire: $scope.currentWimAttire,
            description: $scope.currentWimDescription,
            friends: $scope.friends
        }, function(){

            //If succussful, refesh the Wims on the currentWims page and redirect to that page
            $scope.refresh();
            $location.path('/currentWims');

        }, function(){

            //If error, alert user
            alert('Some errors occurred while communicating with the service. Try again later.');

        });

    }

    //Everytime the currentWims page is loaded, load the wims array with all the wims associated with that user
    $scope.refresh = function(){

        //GET wims associated with that user through the wimService created in in the services.js from API
        wimService.getAll(function(response){
            
            //Load the wims array created below with the wims retrieved from the API
            $scope.wims = response;
        
        }, function(){
            
            //Error out if there was a problem getting the wims
            alert('Some errors occurred while communicating with the service. Try again later.');
        
        });

    };

    //If the an unsigned in user attempts to access this page, redirect to the login page
    if(!userService.checkIfLoggedIn())
        $location.path('/login');

    //Create the array to be used by the refresh function every time the page loads
    $scope.wims = [];

    //Repopulate the current wims for that user
    $scope.refresh();

}]);

wimControllers.controller('MainController', ['userService', '$location', '$scope', '$http', function (userService, $location, $scope, $http) {
	
	$scope.logout = function(){
		userService.logout();
		$location.path('/login');
	}
	
	if(!userService.checkIfLoggedIn())
		$location.path('/login');	
}]);