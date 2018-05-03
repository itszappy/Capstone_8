
<?php

// We need to include these two files in order to work with the database
include_once('config.php');
include_once('dbutils.php');

// get a connection to the database
$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);

session_start();

$HAWKID = $_SESSION['HAWKID'];

// set up a query to get information on user
$queryuser = "SELECT * FROM USERTABLE WHERE HAWKID = '$HAWKID';";

// run the query to get info on user
$resultuser = queryDB($queryuser, $db);

// assign results to an array we can then send back to whomever called
$userarray = array();
$i = 0;

// go through the results one by one
while ($currUser = nextTuple($resultuser)) {
    $userarray[$i] = $currUser;
    $i++;
}

// put together a JSON object to send back the data on the users
$response = array();
$response['status'] = 'success';
$response['value']['userarray'] = $userarray;
header('Content-Type: application/json');
echo(json_encode($response));

?>