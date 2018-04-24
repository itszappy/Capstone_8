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
$STUDENTHAWKID = $data['Student Hawk ID'];

// set up variables to handle errors
// is complete will be false if we find any problems when checking on the data
$isComplete = true;
// error message we'll send back to angular if we run into any problems
$errorMessage = "";
// check if they are logged in
session_start();
if (!isset($_SESSION['user'])) {
    // if the session variable username is not set, then the user is not logged in and should not edit the player
    $isComplete = false;
    $errorMessage .= "User is not logged in.";
}
//
// Validation
//
if ($isComplete) {
    // check if STUDENTHAWKID meets criteria
    if (!isset($STUDENTHAWKID) || (strlen($STUDENTHAWKID) < 2)) {
        $isComplete = false;
        $errorMessage .= "Please enter a HAWK ID with at least two characters. ";
    } else {
        $STUDENTHAWKID = makeStringSafe($db, $STUDENTHAWKID);
    }
}

// check if the id passed to this api corresponds to an existing record in the database
if ($isComplete) {
    // set up a query to check if this player is in the database already
    $query = "SELECT name FROM soccerplayers WHERE id=$id";
    
    // we need to run the query
    $result = queryDB($query, $db);
    
    // check on the number of records returned
    if (nTuples($result) == 0) {
        // if we get no results it means the id we got does not correspond to any records in the soccerplayers table
        $isComplete = false;
        $errorMessage .= "The id $id does not correspond to any players in the soccerplayers table. ";
    }
}
// if we got this far and $isComplete is true it means we should edit the player in the database
if ($isComplete) {
    // we will set up the insert statement to add this new record to the database
    $updatequery = "UPDATE slots SET name='$name', country='$country', club='$club', video='$video' WHERE id=$id";
    
    // run the update statement
    queryDB($updatequery, $db);
    
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