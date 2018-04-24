
<?php

//We need to include these 2 files in order to work with the DB

include_once('config.php');
include_once('dbutils.php');


//get a handle to the database
$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);

//get data from the angular controller
//decode the json object
$data = json_decode(file_get_contents('php://input'), true);


//get each piece of data
//name matches the name attribute in the form
$HAWKID = $data['HAWKID'];
$PASSWORD = $data['PASSWORD'];
$FIRSTNAME = $data['FIRSTNAME'];
$LASTNAME = $data['LASTNAME'];
$ISADMIN = $data['ISADMIN'];
$USERROLE = $data['USERROLE'];
$EMAIL = $data['EMAIL'];
$PHONE = $data['PHONE'];

//set up variables to handle errors
//is complete will be false if we find any problems when checking on the data

$isComplete = true; //is the form complete?

//error message we’ll send back to angular if we run into any problems
$errorMessage = "";

//
//Validation

//

if ($isComplete) {
    // check if username meets criteria
    if (!isset($HAWKID) || (strlen($HAWKID) < 2)) {
        $isComplete = false;
        $errorMessage .= "Please enter a username with at least two characters. ";
    } else {
        $HAWKID = makeStringSafe($db, $HAWKID);
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
if ($isComplete) {
	//set up a query to check if this user is in the database already (and is not the one we are processing)
	$query = "SELECT HAWKID FROM USERTABLE WHERE HAWKID='$HAWKID'";

	//we need to run the query. 
	$result = queryDB($query, $db);
	

}


if ($isComplete) {
	//we willl setup the insert statement to add this new record to the database
	$updatequery = "UPDATE USERTABLE SET HAWKID='$HAWKID', FIRSTNAME='$FIRSTNAME', LASTNAME='$LASTNAME', USERROLE='$USERROLE',EMAIL='$EMAIL', PHONE='$PHONE' WHERE HAWKID='$HAWKID'";

	//run the insert statement
	queryDB($updatequery, $db);

	//get the id of the player we just entered
	$HAWKID = mysqli_insert_id($db);

	//send a response back to angular
	$response = array();
	$response['status'] = 'success';
	$response['hawkid'] = $HAWKID;
	header('Content-Type: application/json');
	echo(json_encode($response));


} else {
	//there’s been an error. We need to report it to the angular controller.

	//one of the things we want to send back is the data that this php file received
	ob_start();
	var_dump($data);
	$postdump = ob_get_clean();

	//set up our response array
	$response = array();
	$response['status'] = 'error';
	$response['message'] = $errorMessage . $postdump; 
	header('Content-Type: application/json');
	echo(json_encode($response));



}

?>

