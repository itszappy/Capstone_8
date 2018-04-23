<?php

// We need to include these two files in order to work with the database
include_once('config.php');
include_once('dbutils.php');

// get a connection to the database
$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);

$SLOT = "SLOT";

// set up a query to get information on players
$query = "SELECT * FROM $SLOT;";

// run the query to get info on players
$result = queryDB($query, $db);

// assign results to an array we can then send back to whomever called
$slots = array();
$i = 0;

// go through the results one by one
while ($currSlot = nextTuple($result)) {
    $slots[$i] = $currSlot;
    $i++;
}

// put together a JSON object to send back the data on the players
$response = array();
$response['status'] = 'success';

// 'value' corresponds to response.data.value in data.soccer.controller.js
// 'players' corresponds to ng-repeat="player in data.players | filter:query" in the index.html file
$response['value']['slot'] = $slot;
header('Content-Type: application/json');
echo(json_encode($response));

?>