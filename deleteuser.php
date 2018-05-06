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
$HAWKID = $data['HAWKID'];
$PASSWORD = $data['PASSWORD'];
$FIRSTNAME = $data['FIRSTNAME'];
$LASTNAME = $data['LASTNAME'];
$ISADMIN = $data['ISADMIN'];
$USERROLE = $data['USERROLE'];
$EMAIL = $data['EMAIL'];
$PHONE = $data['PHONE'];
// set up variables to handle errors
// is complete will be false if we find any problems when checking on the data
$isComplete = true;
// error message we'll send back to angular if we run into any problems
$errorMessage = "";
// check if they are logged in
session_start();
if (!isset($_SESSION['HAWKID'])) {
    // if the session variable username is not set, then the user is not logged in and should not delete the player
    $isComplete = false;
    $errorMessage .= "User is not logged in.";
}

// check if we there is a record in the database matching the id
if ($isComplete) {
    // set up a query to check if the id passed to this file corresponds to a record in the database
    $checkquery = "SELECT HAWKID FROM USERTABLE WHERE HAWKID='$HAWKID'";
    
    // we need to run the query
    $checkresult = queryDB($checkquery, $db);
    
    // check on the number of records returned
    if (nTuples($checkresult) == 0) {
        // if we get no recors back, it means no records match the id that we got
        $isComplete = false;
        $errorMessage .= "This Hawkid '$HAWKID' is missing ";
    }
}
// if we got this far and $isComplete is true it means we should delete the player from the database
if ($isComplete) {
    // we will set up the delate statement to remove the player from the database
    $deletequery = "DELETE FROM USERTABLE WHERE HAWKID='$HAWKID'";
    
    // run the delete statement
    queryDB($deletequery, $db);
        
    // send a response back to angular
    $response = array();
    $response['status'] = 'success';
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