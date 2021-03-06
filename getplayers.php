<?php

/// We need to include these two files in order to work with the database
include_once('config.php');
include_once('dbutils.php');

// get a connection to the database
$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);

$tablename = "USERTABLE";

// set up a query to get information on users
$query = "SELECT * FROM $tablename;";

// run the query to get info on users
$result = queryDB($query, $db);

// assign results to an array we can then send back to whomever called
$user = array();
$i = 0;

// go through the results one by one
while ($currUser = nextTuple($result)) {
    $user[$i] = $currUser;
    $i++;
}

// put together a JSON object to send back the data on the users
$response = array();
$response['status'] = 'success';

// 'value' corresponds to response.data.value in capstone js file
// 'user' corresponds to ng-repeat="user in the index.html file
$response['value']['USERTABLE'] = $user;
header('Content-Type: application/json');
echo(json_encode($response));

?>
