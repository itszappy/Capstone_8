<?php
//log user out by unsetting session variabled called email, destroying the session

    session_start();
    if (isset($_SESSION['HAWKID'])) {
        $isadmin = true;
        $HAWKID = $_SESSION['HAWKID'];
        
    } else {
        $isadmin = false;
        $HAWKID = "not logged in ";
    }
    
    $response = array();
    $response['status'] = 'success';
    $response['isadmin'] = $isadmin;
    $response['HAWKID'] = $HAWKID;
    header('Content-Type: application/json');
    echo(json_encode($response));
    
?>
