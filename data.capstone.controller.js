/*
 * Controller where we get the data on capstone stuff
 */
(function () {
    'use strict';
    
    // the 'capstone' part comes from the name of the app we created in capstone.module.js
    var myApp = angular.module("capstone");
    
    // 'dataControl' is used in the html file when defning the ng-controller attribute
    myApp.controller("dataControl", function($scope, $http, $window) {
        
        // define data for the app
        // in the html code we will refer to data.players. The data part comes from $scope.data, the players part comes from the JSON object below
        
        $http.get('getusers.php')
            .then(function(response) {
                // response.data.value has value come from the getplayers.php file $response['value']['players'] = $players;
                $scope.data = response.data.value;
            }
                   );
       
        $scope.query = {};
        $scope.queryBy = "$";
        $scope.menuHighlight = 0; //higlight for menu
        
        
        // function to send new account information to web api to add it to the database
        $scope.newAccount = function(accountDetails) {
          var accountupload = angular.copy(accountDetails);
          
          $http.post("newaccount.php", accountupload)
            .then(function (response) {
               if (response.status == 200) {
                    if (response.data.status == 'error') {
                        alert('error: ' + response.data.message);
                    } else {
                        // successful
                        // send user back to home page
                        $window.location.href = "index.html";
                    }
               } else {
                    alert('unexpected error');
               }
            });                        
        };        
        
        // function to send new account information to web api to add it to the database
        $scope.login = function(accountDetails) {
          var accountupload = angular.copy(accountDetails);
          
          $http.post("login.php", accountupload)
            .then(function (response) {
               if (response.status == 200) {
                    if (response.data.status == 'error') {
                        alert('error: ' + response.data.message);
                    } else if (response.data.role == 'a') {
                        $window.location.href = "Admin_Landing.html";
                    } else if (response.data.role == 's') {
                        $window.location.href = "Student_Landing.html";
                    } else if (response.data.role == 't') {
                        $window.location.href = "Tutor_Landing.html";
                    } else if (response.data.role == 'p') {
                        $window.location.href = "Teacher_Landing.html";
                    }
               } if (response.status == 500) {
                    alert('unexpected error');
               }
            });                        
        };
        
        
        // function to log the user out
        $scope.logout = function() {
          $http.post("logout.php")
            .then(function (response) {
               if (response.status == 200) {
                    if (response.data.status == 'error') {
                        alert('error: ' + response.data.message);
                    } else {
                        // successful
                        // send user back to home page
                        $window.location.href = "index.html";
                    }
               } else {
                    alert('unexpected error');
               }
            });                        
        };             
        
        // function to check if user is logged in
        $scope.checkifloggedin = function() {
          $http.post("isloggedin.php")
            .then(function (response) {
               if (response.status == 200) {
                    if (response.data.status == 'error') {
                        alert('error: ' + response.data.message);
                    } else {
                        // successful
                        // set $scope.isloggedin based on whether the user is logged in or not
                        $scope.isloggedin = response.data.loggedin;
                    }
               } else {
                    alert('unexpected error');
               }
            });                        
        };       

        
    });
    
    
} )();
