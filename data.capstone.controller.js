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
        // in the html code we will refer to capstone.js file The data part comes from $scope.data, the users part comes from the JSON object below
        
        $http.get("getusers.php")
            .then(function(response) {
                // response.data.value has value come from the getusers.php file $response['value']['userarray'] = $userarray;
                $scope.users = response.data.value;
            }
                   );
           
          $http.get("getslots.php")
            .then(function(response) {
                // response.data.value has value come from the getslots.php file $response['value']['slots'] = $slots;
                $scope.slots = response.data.value;
            }
                   );
         
        $http.get("getproblemfiles.php")
            .then(function(response) {
                // response.data.value has value come from the getslots.php file $response['value']['slots'] = $slots;
                $scope.problems = response.data.value;
            }
                   );
            
            
        $http.get("getstudentslot.php")
            .then(function(response) {
                // response.data.value has value come from the getslots.php file $response['value']['slots'] = $slots;
                $scope.studentslots = response.data.value;
            }
                   );
            
        $http.get("gettutorslots.php")
            .then(function(response) {
                // response.data.value has value come from the getslots.php file $response['value']['slots'] = $slots;
                $scope.tutorslots = response.data.value;
            }
                   ); 

        $http.get("getuserlist.php")
            .then(function(response) {
                // response.data.value has value come from the getslots.php file $response['value']['slots'] = $slots;
                $scope.userlists = response.data.value;
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
        
        // Function to send slot info to database
        $scope.newSlot = function(slotDetails) {
          var slotupload = angular.copy(slotDetails);
          
          $http.post("newslot.php", slotupload)
            .then(function (response) {
               if (response.status == 200) {
                    if (response.data.status == 'error') {
                        alert('error: ' + response.data.message);
                    } else {
                        // successful
                        // send user back to home page
                        $window.location.href = "Tutor_Landing.html";
                    }
               } else {
                    alert('unexpected error');
               }
            });                        
        };
        
        // Function to update slot info to database
        $scope.updateSlot = function(slotUpdateDetails) {
          var slotupdateupload = angular.copy(slotUpdateDetails);
          
          $http.post("StudentSchedule.php", {"SLOTID": slotupdateupload})
            .then(function (response) {
               if (response.status == 200) {
                    if (response.data.status == 'error') {
                        alert('error: ' + response.data.message);
                    } else {
                        // successful
                        // send user back to home page
                        $window.location.href = "Student_Landing.html";
                    }
               } else {
                    alert('unexpected error');
               }
            });                        
        };   
        
        
        //Function to send materials information to database
         $scope.newProblem = function(problemDetails) {
          var problemupload = angular.copy(problemDetails);
          
          $http.post("newproblem.php", problemupload)
            .then(function (response) {
               if (response.status == 200) {
                    if (response.data.status == 'error') {
                        alert('error: ' + response.data.message);
                    } else {
                        // successful
                        // send user back to home page
                        $window.location.href = "Teacher_Landing.html";
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
                    } if (response.data.role == 'a') {
                        $window.location.href = "Admin_Landing.html";
                    } if (response.data.role == 's') {
                        $window.location.href = "Student_Landing.html";
                    } if (response.data.role == 't') {
                        $window.location.href = "Tutor_Landing.html";
                    } if (response.data.role == 'p') {
                        $window.location.href = "Teacher_Landing.html";
                    } if (reponse.status == 500) {
                        alert("error 500");
                    }
                    
               } else {
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
        
        $scope.setEditMode = function(on, user) {
            if (on) {
                //edit user matches the ng-model used in the form we use to edit user information 
                $scope.edituser = angular.copy(user);
                user.editMode = true;
            } else {
                $scope.edituser = null;
                user.editMode = false;
            }
        };
        /*
         * Gets the edit mode for a particular user
         */
        $scope.getEditMode = function(user) {
            return user.editMode;
        };
        
        //function to edit user data and send it to web api to edit the profile in the database
        $scope.editUser = function(userDetails) {
          var userupload = angular.copy(userDetails);
          
          $http.post("editusers.php", userupload)
            .then(function (response) {
               if (response.status == 200) {
                    if (response.data.status == 'error') {
                        alert('error: ' + response.data.message);
                    } else {
                        // successful
                        // send user back to home page
                        $window.location.href = "Admin_Profile.html";
                    }
               } else {
                    alert('unexpected error');
               }
            });
        };

    });

        
    
    
} )();
