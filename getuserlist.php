<?php

// We need to include these two files in order to work with the database
include_once('config.php');
include_once('dbutils.php');

// get a connection to the database
$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);

session_start();

$HAWKID = $_SESSION['HAWKID'];

// set up a query to get information on user
$queryuserlist = "SELECT * FROM USERTABLE;";

// run the query to get info on user
$resultuserlist = queryDB($queryuserlist, $db);

// assign results to an array we can then send back to whomever called
$userlistarray = array();
$i = 0;

// go through the results one by one
while ($currUserList = nextTuple($resultuserlist)) {
    $userlistarray[$i] = $currUserList;
    $i++;
}

// put together a JSON object to send back the data on the users
$response = array();
$response['status'] = 'success';
$response['value']['userlistarray'] = $userlistarray;
header('Content-Type: application/json');
echo(json_encode($response));

?>