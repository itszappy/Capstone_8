<?php

// We need to include these two files in order to work with the database
include_once('config.php');
include_once('dbutils.php');

// get a connection to the database
$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);


session_start();
$SLOTS = "SLOTS";
$STUDENTHAWKID = "STUDENTHAWKID";

// set up a query to get information on the slots 
$slotquery = "SELECT * FROM $SLOTS";

// run the query to get info on players
$slotresult = queryDB($slotquery, $db);

// assign results to an array we can then send back to whomever called
$slotsarray = array();
$i = 0;

// go through the results one by one
while ($currSlot = nextTuple($slotresult)) {
    $slotsarray[$i] = $currSlot;
    $i++;
}

// put together a JSON object to send back the data on the available slots
$response = array();
$response['status'] = 'success';
$response['value']['slotsarray'] = $slotsarray;
header('Content-Type: application/json');
echo(json_encode($response));

?>