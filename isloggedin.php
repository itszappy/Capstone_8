<?php
//log user out by unsetting session variabled called email, destroying the session

    session_start();
    if (isset($_SESSION['HAWKID'])) {
        $isloggedin = true;
        $HAWKID = $_SESSION['HAWKID'];
        
    } else {
        $isloggedin = false;
        $HAWKID = "not logged in ";
    }
    
    $response = array();
    $response['status'] = 'success';
    $response['loggedin'] = $isloggedin;
    $response['HAWKID'] = $HAWKID;
    header('Content-Type: application/json');
    echo(json_encode($response));
    
?>
