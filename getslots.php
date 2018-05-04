<?php

// We need to include these two files in order to work with the database
include_once('config.php');
include_once('dbutils.php');

// get a connection to the database
$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);


session_start();
$SLOTS = "SLOTS";
$STUDENTHAWKID = $_SESSION['HAWKID'];


// set up a query to get information on the slots 
$slotquery = "SELECT USERTABLE.FIRSTNAME, SLOTS.SLOTID, SLOTS.SLOTDATE, TIME(SLOTS.SLOTSTART) AS SLOTSTART, TIME(SLOTS.SLOTEND) AS SLOTEND, COURSE.TITLE, SLOTS.TUTORHAWKID, SLOTS.LOCATION FROM STUDENT, SLOTS, USERTABLE, COURSE WHERE STUDENT.STUDENTHAWKID='$STUDENTHAWKID' AND SLOTS.COURSEID=STUDENT.COURSEID AND SLOTS.TUTORHAWKID = USERTABLE.HAWKID AND STUDENT.COURSEID = COURSE.COURSEID AND SLOTS.STUDENTHAWKID IS NULL ORDER BY SLOTS.SLOTDATE, TIME(SLOTS.SLOTSTART);";

// run the query to get info on slots
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