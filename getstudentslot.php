<?php
// We need to include these two files in order to work with the database
include_once('config.php');
include_once('dbutils.php');
// get a handle to the database
$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);


// get data from the angular controller
// decode the json object
$data = json_decode(file_get_contents('php://input'), true);

// get each piece of data
// 'name' matches the name attribute in the form
session_start();
$SLOTS = "SLOTS";
$STUDENTHAWKID = $_SESSION['HAWKID'];


// Selecting the slot record to show the students upcoming appointments
$studentslotquery = "SELECT USERTABLE.FIRSTNAME, SLOTS.SLOTID, SLOTS.SLOTDATE, TIME(SLOTS.SLOTSTART) AS SLOTSTART, TIME(SLOTS.SLOTEND) AS SLOTEND, SLOTS.STUDENTHAWKID, SLOTS.LOCATION FROM SLOTS, USERTABLE WHERE SLOTS.TUTORHAWKID=USERTABLE.HAWKID AND STUDENTHAWKID = '$STUDENTHAWKID';";

// run the update statement
$studentslotresult = queryDB($studentslotquery, $db);

$studentslotarray = array();
$i = 0;

while ($currStudentSlot = nextTuple($studentslotresult)) {
    $studentslotarray[$i] = $currStudentSlot;
    $i++;
}

// send a response back to angular
$response = array();
$response['status'] = 'success';
$response['value']['studentslotarray'] = $studentslotarray;
header('Content-Type: application/json');
echo(json_encode($response));    


?>