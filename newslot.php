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

start_session();
//$SLOTID = $data['SLOTID']; this should be generated
$SLOTDATE = $data['SLOTDATE'];
$SLOTSTART = $data['SLOTSTART'];
$SLOTEND = $data['SLOTEND'];
$TUTORHAWKID = $_SESSION['HAWKID'];
$STUDENTHAWKID = NULL;
$COURSEID = $data['COURSEID'];
$LOCATION = $data['LOCATION'];



// set up variables to handle errors
// is complete will be false if we find any problems when checking on the data
$isComplete = true;

// error message we'll send back to angular if we run into any problems
$errorMessage = "";

//
// Validation
//
if ($isComplete) {
    // check if slotdate meets criteria
        if (!isset($LOCATION) || (strlen($LOCATION) < 6)) {
        $isComplete = false;
        $errorMessage .= "Please enter a location with at least 2 characters.";
    } 
}

// check if we already have a username that matches the one the user entered
if ($isComplete) {
    // set up a query to check if this username is in the database already
    $slotquery = "SELECT * FROM SLOTS WHERE SLOTDATE='$SLOTDATE' AND SLOTSTART='$SLOTSTART' AND '$SLOTEND' AND TUTORHAWKID='$HAWKID';";
    
    // we need to run the query
    $result = queryDB($slotquery, $db);
    
    // check on the number of records returned
    if (nTuples($result) > 0) {
        // if we get at least one record back it means the username is taken
        $isComplete = false;
        $errorMessage .= "The this date $DATE, time ($SLOTSTART AND $SLOTEND) and location $LOCATION are already taken. Please select a different date time and location. ";
    }
}



// if we got this far and $isComplete is true it means we should add the player to the database
if ($isComplete) {
    
    // we will set up the insert statement to add this new record to the database
    $insertslot = "INSERT INTO SLOTS(SLOTDATE, SLOTSTART, SLOTEND, TUTORHAWKID, STUDENTHAWKID, COURSEID, LOCATION) VALUES ('$SLOTDATE', '$SLOTSTART', '$SLOTEND','jsmith',NULL, 0000000001, '$LOCATION')";
    
    // run the insert statement
    queryDB($insertslot, $db);

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
