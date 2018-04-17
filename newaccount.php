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

// 
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

//
// Validation
//
if ($isComplete) {
    // check if username meets criteria
    if (!isset($HAWKID) || (strlen($HAWKID) < 2)) {
        $isComplete = false;
        $errorMessage .= "Please enter a username with at least two characters. ";
    } else {
        $HAWKID = makeStringSafe($db, $HAWKID);
    }
    
    if (!isset($PASSWORD) || (strlen($PASSWORD) < 6)) {
        $isComplete = false;
        $errorMessage .= "Please enter a password with at least six characters!! ";
    }
    
        if (!isset($FIRSTNAME) || (strlen($FIRSTNAME) < 2)) {
        $isComplete = false;
        $errorMessage .= "Please enter a first name with at least 2 characters!! ";
    }
    
        if (!isset($LASTNAME) || (strlen($LASTNAME) < 2)) {
        $isComplete = false;
        $errorMessage .= "Please enter a last name with at least two characters!! ";
    }
    
        if (!isset($USERROLE) || (strlen($USERROLE) != 1)) {
        $isComplete = false;
        $errorMessage .= "Please enter a role with only one character!! ";
    }
    
        if (!isset($EMAIL) || (strlen($EMAIL) < 6)) {
        $isComplete = false;
        $errorMessage .= "Please enter an email with at least six characters!! ";
    }
    
        if (!isset($PHONE) || (strlen($PHONE) < 9)) {
        $isComplete = false;
        $errorMessage .= "Please enter a phone number with at least 9 characters!! ";
    }  
}

// check if we already have a username that matches the one the user entered
if ($isComplete) {
    // set up a query to check if this username is in the database already
    $query = "SELECT HAWKID FROM USERTABLE WHERE HAWKID='$HAWKID'";
    
    // we need to run the query
    $result = queryDB($query, $db);
    
    // check on the number of records returned
    if (nTuples($result) > 0) {
        // if we get at least one record back it means the username is taken
        $isComplete = false;
        $errorMessage .= "The hawk id $HAWKID is already taken. Please select a different username. ";
    }
}

if ($isComplete) {
    // set up a query to check if this username is in the database already
    $query = "SELECT FIRSTNAME FROM USERTABLE WHERE FIRSTNAME='$FIRSTNAME'";
    
    // we need to run the query
    $result = queryDB($query, $db);
    
    // check on the number of records returned
    if (nTuples($result) > 0) {
        // if we get at least one record back it means the username is taken
        $isComplete = false;
        $errorMessage .= "The first name $FIRSTNAME is already taken. Please select a different firstname. ";
    }
}

if ($isComplete) {
    // set up a query to check if this username is in the database already
    $query = "SELECT LASTNAME FROM USERTABLE WHERE LASTNAME='$LASTNAME'";
    
    // we need to run the query
    $result = queryDB($query, $db);
    
    // check on the number of records returned
    if (nTuples($result) > 0) {
        // if we get at least one record back it means the username is taken
        $isComplete = false;
        $errorMessage .= "The last name $LASTNAME is already taken. Please select a different last name. ";
    }
}

if ($isComplete) {
    // set up a query to check if this username is in the database already
    $query = "SELECT EMAIL FROM USERTABLE WHERE EMAIL='$EMAIL'";
    
    // we need to run the query
    $result = queryDB($query, $db);
    
    // check on the number of records returned
    if (nTuples($result) > 0) {
        // if we get at least one record back it means the username is taken
        $isComplete = false;
        $errorMessage .= "The email $EMAIL is already taken. Please select a different email. ";
    }
}

if ($isComplete) {
    // set up a query to check if this username is in the database already
    $query = "SELECT PHONE FROM USERTABLE WHERE PHONE='$PHONE'";
    
    // we need to run the query
    $result = queryDB($query, $db);
    
    // check on the number of records returned
    if (nTuples($result) > 0) {
        // if we get at least one record back it means the username is taken
        $isComplete = false;
        $errorMessage .= "The phone number $PHONE is already taken. Please select a different phone number. ";
    }
}


// if we got this far and $isComplete is true it means we should add the player to the database
if ($isComplete) {
    // create a hashed version of the password
    $HASHED_PSSWRD = crypt($PASSWORD, getSalt());
    
    // we will set up the insert statement to add this new record to the database
    $insertquery = "INSERT INTO USERTABLE(HAWKID, HASHED_PSSWRD, FIRSTNAME, LASTNAME, ISADMIN, USERROLE, EMAIL, PHONE) VALUES ('$HAWKID', '$HASHED_PSSWRD', '$FIRSTNAME', '$LASTNAME', '$ISADMIN', '$USERROLE', '$EMAIL', '$PHONE')";
    
    // run the insert statement
    queryDB($insertquery, $db);

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
