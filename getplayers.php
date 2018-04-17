<?php

// We need to include these two files in order to work with the database
include_once('config.php');
include_once('dbutils.php');

// get a connection to the database
$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);

$tablename = "soccerplayers";

// set up a query to get information on players
$query = "SELECT * FROM $tablename;";

// run the query to get info on players
$result = queryDB($query, $db);

// assign results to an array we can then send back to whomever called
$players = array();
$i = 0;

// go through the results one by one
while ($currPlayer = nextTuple($result)) {
    $players[$i] = $currPlayer;
    $video = $players[$i]['video'];
    $players[$i]['videoshow'] = "<iframe src='https://www.youtube.com/embed/$video' frameborder='0' allow='autoplay; encrypted-media' allowfullscreen></iframe>";
    $i++;
}

// put together a JSON object to send back the data on the players
$response = array();
$response['status'] = 'success';

// 'value' corresponds to response.data.value in data.soccer.controller.js
// 'players' corresponds to ng-repeat="player in data.players | filter:query" in the index.html file
$response['value']['players'] = $players;
header('Content-Type: application/json');
echo(json_encode($response));

?>