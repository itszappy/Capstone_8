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

session_start();
//$SLOTID = $data['SLOTID']; this should be generated
$SLOTDATE = $data['SLOTDATE'];
$SLOTSTART = $data['SLOTSTART'];
//$SLOTSTART = date("H:i", $data['SLOTSTART']);
$SLOTEND = $data['SLOTEND'];
//$SLOTEND = date("H:i", $data['SLOTEND']);
$TUTORHAWKID = $_SESSION['HAWKID'];
$STUDENTHAWKID = NULL;
$COURSEID = $data['COURSEID'];
$COURSEID = 3;
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
        if (!isset($LOCATION) || (strlen($LOCATION) < 2)) {
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

// check for the tutor's course
if ($isComplete) {
    $coursequery = "SELECT COURSEID FROM TUTOR WHERE TUTORHAWKID = '$TUTORHAWKID'";
    
    // we need to run the query
    $result = queryDB($coursequery, $db);
    
    // check on the number of records returned
    if (nTuples($result) > 0) {
        // get the course they are tutoring
        $row = nextTuple($result);
        $COURSEID = $row['COURSEID'];
        
    } else {
        // this tutor has no courses
        $isComplete = false;
        $errorMessage .= "You don't have any courses assigned to you. Go talk to Dr. Segre!";
    }
}




if ($isComplete) {
    
    // we will set up the insert statement to add this new record to the database
    $insertslot = "INSERT INTO SLOTS(SLOTDATE, SLOTSTART, SLOTEND, TUTORHAWKID, STUDENTHAWKID, COURSEID, LOCATION) VALUES ('$SLOTDATE', '$SLOTSTART', '$SLOTEND','$TUTORHAWKID',NULL, $COURSEID, '$LOCATION')";
    
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
