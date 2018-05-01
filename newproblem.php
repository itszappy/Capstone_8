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
$PROBLEMSETID = $data['PROBLEMSETID'];
$PROBLEMSETNAME = $data['PROBLEMSETNAME'];
$PROBLEMDESC = $data['PROBLEMDESC'];
$PROBLEMFILE = $data['PROBLEMFILE'];
$COURSEID = $data['COURSEID'];
$PROFESSORHAWKID = $_SESSION['$HAWKID'];

// set up variables to handle errors
// is complete will be false if we find any problems when checking on the data
$isComplete = true;

// error message we'll send back to angular if we run into any problems
$errorMessage = "";

//
//

// check if we already have a problem id that matches the one the user entered
if ($isComplete) {
    // set up a query to check if this problem id is in the database already
    $slotquery = "SELECT * FROM PROBLEMS WHERE PROBLEMSETID='$PROBLEMSETID';";
    
    // we need to run the query
    $result = queryDB($slotquery, $db);
    
    // check on the number of records returned
    if (nTuples($result) > 0) {
        // if we get at least one record back it means the username is taken
        $isComplete = false;
        $errorMessage .= "The problem set $PROBLEMSETID is already posted. Please chose another. ";
    }
}

// check for the professor's course
if ($isComplete) {
    $problemquery = "SELECT COURSEID FROM PROFESSOR WHERE PROFESSORHAWKID = '$PROFESSORHAWKID';";
    
    // we need to run the query
    $result = queryDB($problemquery, $db);
    
    // check on the number of records returned
    if (nTuples($result) > 0) {
        // get the course they are teaching
        $row = nextTuple($result);
        $COURSEID = $row['COURSEID'];
        
    } else {
        // this professor has no courses
        $isComplete = false;
        $errorMessage .= "You don't have any courses assigned to you. Go talk to Dr. Segre!";
    }
}



// if we got this far and $isComplete is true it means we should add the problem to the database
if ($isComplete) {
    
    // we will set up the insert statement to add this new record to the database
    $insertproblem = "INSERT INTO PROBLEMS (PROBLEMSETID, PROBLEMSETNAME, PROBLEMDESC, PROBLEMFILE, COURSEID) VALUES ($PROBLEMSETID, $PROBLEMSETNAME, $PROBLEMDESC, $PROBLEMFILE , $COURSEID)'";
    
    // run the insert statement
    queryDB($insertproblem, $db);

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
