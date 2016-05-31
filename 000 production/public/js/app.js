var wim = angular.module('wim', [
  'ngRoute',
  'wimControllers',
  'wimServices'
]);

wim.config(['$routeProvider', function($routeProvider) {
    
    $routeProvider.
    when('/login', {
        templateUrl: 'partials/login.html',
        controller: 'LoginController'
    }).
    when('/signup', {
        templateUrl: 'partials/signup.html',
        controller: 'SignupController'
    }).
	when('/profile', {
		templateUrl: 'partials/profile.html',
		controller: 'ProfileController'
	}).
	when('/notifications', {
		templateUrl: 'partials/notifications.html',
		controller: 'RequestController'
	}).
	when('/currentWims', {
        templateUrl: 'partials/currentWims.html',
        controller: 'WimController'
    }).
    when('/newWim', {
        templateUrl: 'partials/newWim.html',
        controller: 'WimController'
	}).
    when('/wimInfo', {
        templateUrl: 'partials/wimInfo.html',
        controller: 'MainController'
    }).
    when('/homepage', {
        templateUrl: 'partials/homepage.html',
        controller: 'MainController'
    }).
    otherwise({
        redirectTo: '/homepage'
    });

}]);
