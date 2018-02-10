var app = angular.module('myApp', []);
app.controller('formCtrl', function($scope,$http) {
    $scope.master = {name:"", email:"", mobile:""};
    $scope.submit = function() {
    	var req = {
  			 method: 'POST',
  			 url: serverURL+'/addStudent?name='+$scope.master.name+'&email='+$scope.master.email+'&mobile='+$scope.master.mobile,
  			 headers: {
  			   'Content-Type': 'application/json'
  			 }
		}

		$http(req).then(
			function mySucces(data){
				setData(data);
			}, 
			function myError(data){
				alert('Some Problem in Adding Data.');
			}
		);
    };
    
    $http({
        method : "GET",
        url : serverURL+'/getStudent'
    }).then(function mySucces(data) {
    	setData(data);
    }, function myError(data) {
        
    });
    
});