<?php

// We need to include these two files in order to work with the database
include_once('config.php');
include_once('dbutils.php');

// get a connection to the database
$db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);

session_start();

$PROFESSORHAWKID = $_SESSION['HAWKID'];

// set up a query to get information on user
$querystudentlist = "SELECT DISTINCT USERTABLE.HAWKID, USERTABLE.FIRSTNAME, USERTABLE.LASTNAME, COURSE.COURSEID, COURSE.TITLE, COURSE.SECTION FROM PROFESSOR, USERTABLE,COURSE,STUDENT WHERE STUDENT.COURSEID=COURSE.COURSEID AND COURSE.COURSEID=PROFESSOR.COURSEID AND PROFESSOR.PROFESSORHAWKID = '$PROFESSORHAWKID'";

// run the query to get info on user
$resultstudentlist = queryDB($querystudentlist, $db);

// assign results to an array we can then send back to whomever called
$studentlistarray = array();
$i = 0;

// go through the results one by one
while ($currStudentList = nextTuple($resultstudentlist)) {
    $studentlistarray[$i] = $currStudentList;
    $i++;
}

// put together a JSON object to send back the data on the users
$response = array();
$response['status'] = 'success';
$response['value']['studentlistarray'] = $studentlistarray;
header('Content-Type: application/json');
echo(json_encode($response));

?>