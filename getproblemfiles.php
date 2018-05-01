<?php

// We need to include these two files in order to work with the database
include_once('config.php');
include_once('dbutils.php');

// get a connection to the database
$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);

session_start();
$PROFESSORHAWKID = $_SESSION['HAWKID'];
$PROBLEMS = "PROBLEMS";

// set up a query to get information on all the problems sets
$query = "SELECT * FROM PROBLEMS;";

// run the query to get all the information on the problem sets
$problemresult = queryDB($query, $db);

// assign results to an array we can then send back to whomever called
$problem = array();
$i = 0;

// go through the results one by one
while ($currProblem = nextTuple($problemresult)) {
    $problem[$i] = $currProblem;
    $i++;
}

// put together a JSON object to send back the data on the players
$response = array();
$response['status'] = 'success';

// 'value' corresponds to response.data.value in data.capstone.controller.js
// 'problem' corresponds to ng-repeat="problem in data.problem"
$response['value']['problem'] = $problem;
header('Content-Type: application/json');
echo(json_encode($response));

?>