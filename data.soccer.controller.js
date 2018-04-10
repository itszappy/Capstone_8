/*
 * Controller where we get the data on soccer players
 */
(function () {
    'use strict';
    
    // the 'soccer' part comes from the name of the app we created in soccer.module.js
    var myApp = angular.module("soccer");
    
    // 'dataControl' is used in the html file when defning the ng-controller attribute
    myApp.controller("dataControl", function($scope, $http, $window) {
        
        // define data for the app
        // in the html code we will refer to data.players. The data part comes from $scope.data, the players part comes from the JSON object below
        
        $http.get('getplayers.php')
            .then(function(response) {
                // response.data.value has value come from the getplayers.php file $response['value']['players'] = $players;
                $scope.data = response.data.value;
            }
                   );
        /*
        $scope.data = {
            "players":[
                {"name":"Lionel Messi", "country":"Argentina", "club":"FC Barcelona", "video":"<iframe src='https://www.youtube.com/embed/E2MBCa_OFsY' frameborder='0' allow='autoplay; encrypted-media' allowfullscreen></iframe>", "videoid":"E2MBCa_OFsY"},
                {"name":"Neymar Jr.", "country":"Brazil", "club":"Paris Saint Germain", "video":"<iframe src='https://www.youtube.com/embed/E2MBCa_OFsY' frameborder='0' allow='autoplay; encrypted-media' allowfullscreen></iframe>", "videoid":"E2MBCa_OFsY"},
                {"name":"Gianluigi Buffon", "country":"Italy", "club":"Juventus", "video":"<iframe src='https://www.youtube.com/embed/U0XSU0wNiNw' frameborder='0' allow='autoplay; encrypted-media' allowfullscreen></iframe>", "videoid":"U0XSU0wNiNw"},
                {"name":"Diego Godin", "country":"Uruguay", "club":"Atletico Madrid", "video":"<iframe src='https://www.youtube.com/embed/E2MBCa_OFsY' frameborder='0' allow='autoplay; encrypted-media' allowfullscreen></iframe>", "videoid":"E2MBCa_OFsY"}
            ]
        };
        */
        
        
        /*
         *    Code for search bar
         *    With queryBy you can say which attribute you want to search under. For example if it is "name" it will only search under names. If you want to search under everything, then use "$"
         *    We are assuming there is an input element with an ng-model="query[queryBy]"
         *    We are also assuming that you are filtering through this query when you get data under ng-repeat
         */
        $scope.query = {};
        $scope.queryBy = "$";
            
        // this variable will hold the page number that should be highlighted in the menu bar
        // 0 is for index.html
        // 1 is for newplayer.html
        $scope.menuHighlight = 0;
        
        
        // function to send new player information to web api to add it to the database
        $scope.newPlayer = function(playerDetails) {
          var playerupload = angular.copy(playerDetails);
          
          $http.post("newplayer.php", playerupload)
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
        
        
        // function to delete a player. it receives the player's name and id and call a php web api to complete deletion from the database
        $scope.deletePlayer = function(name, id) {
            if (confirm("Are you sure you want to delete " + name + "?")) {
          
                $http.post("deleteplayer.php", {"id" : id})
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
                  }
                );
            }
        };
        
        // function to edit player data and send it to web api to edit the player in the database
        $scope.editPlayer = function(playerDetails) {
          var playerupload = angular.copy(playerDetails);
          
          $http.post("editplayer.php", playerupload)
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
        

        /*
         * Set edit mode of a particular player
         * on is true if we are setting edit mode to be on, false otherwise
         * player corresponds to the player for which we are setting an edit mode
         */
        $scope.setEditMode = function(on, player) {
            if (on) {
                // if player had a birth, for example, you'd include the line below
                // player.birthyear = parseInt(player.birthyear);
                // editplayer matches the ng-model used in the form we use to edit player information
                $scope.editplayer = angular.copy(player);
                player.editMode = true;
            } else {
                // if editplayer is null we assume no player is currently being edited
                $scope.editplayer = null;
                player.editMode = false;
            }
        };
        
        /*
         * Gets the edit mode for a particular player
         */
        $scope.getEditMode = function(player) {
            return player.editMode;
        };
        
        
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
