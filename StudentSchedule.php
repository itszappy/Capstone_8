<?php
// We need to include these two files in order to work with the database
include_once('config.php');
include_once('dbutils.php');
// get a handle to the database
$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);

$tablename = "SLOTS";

// get data from the angular controller
// decode the json object
$data = json_decode(file_get_contents('php://input'), true);
// get each piece of data
// 'name' matches the name attribute in the form
$STUDENTHAWKID = $_SESSION['STUDENTHAWKID'];

// set up variables to handle errors
// is complete will be false if we find any problems when checking on the data
$isComplete = true;
// error message we'll send back to angular if we run into any problems
$errorMessage = "";
// check if they are logged in
session_start();
if (!isset($_SESSION['user'])) {
    // if the session variable username is not set, then the user is not logged in and should not edit the 
    $isComplete = false;
    $errorMessage .= "User is not logged in.";
}


if ($isComplete) {
    // updating the slot record with the logged in students hawkid 
    $updatequery = "UPDATE SLOTS SET STUDENTHAWKID='$STUDENTHAWKID' WHERE SLOTID='$SLOTID'";
    
    // run the update statement
    queryDB($updatequery, $db);
    
    // send a response back to angular
    $response = array();
    $response['status'] = 'success';
    $response['hawkid'] = $HAWKID;
    header('Content-Type: application/json');
    echo(json_encode($response));    
} else {
    // there's been an error. We need to report it to the angular controller.
    
    // one of the things we want to send back is the data that his php file received
    ob_start();
    var_dump($data);
    $postdump = ob_get_clean();
    
    // set up our response array
    $response = array();
    $response['status'] = 'error';
    $response['message'] = $errorMessage . $postdump;
    header('Content-Type: application/json');
    echo(json_encode($response));    
}
?>