<?php

// We need to include these two files in order to work with the database
include_once('config.php');
include_once('dbutils.php');

// get a connection to the database
$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);


session_start();
$SLOTS = "SLOTS";
$TUTORHAWKID = $_SESSION['HAWKID'];


// set up a query to get information on the slots 
$slottutorquery = "SELECT * FROM SLOTS WHERE TUTORHAWKID = '$TUTORHAWKID' AND STUDENTHAWKID IS NOT NULL;";

// run the query to get info on slots
$slottutorresult = queryDB($slottutorquery, $db);

// assign results to an array we can then send back to whomever called
$slottutorsarray = array();
$i = 0;

// go through the results one by one
while ($currSlotTutor = nextTuple($slottutorresult)) {
    $slottutorsarray[$i] = $currSlotTutor;
    $i++;
}

// put together a JSON object to send back the data on the available slots
$response = array();
$response['status'] = 'success';
$response['value']['slottutorsarray'] = $slottutorsarray;
header('Content-Type: application/json');
echo(json_encode($response));

?>