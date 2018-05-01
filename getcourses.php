<?php

// We need to include these two files in order to work with the database
include_once('config.php');
include_once('dbutils.php');

// get a connection to the database
$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);
session_start();
$COURSE = "COURSE";
$HAWKID = $_SESSION['HAWKID'];

// set up a query to get information on courses
$query = "SELECT * FROM $COURSE;";

// run the query to get info on courses
$courseresult = queryDB($query, $db);

// assign results to an array we can then send back to whomever called
$course = array();
$i = 0;

// go through the results one by one
while ($currCourse = nextTuple($result)) {
    $course[$i] = $currCourse;
    $i++;
}

// put together a JSON object to send back the data on the corresponding course table
$response = array();
$response['status'] = 'success';

// 'value' corresponds to response.data.value in data.capstone.controller.js
// 'cours corresponds to ng-repeat="course in data.course" 
$response['value']['course'] = $course;
header('Content-Type: application/json');
echo(json_encode($response));

?>