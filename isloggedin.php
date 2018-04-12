<?php
//log user out by unsetting session variabled called email, destroying the session

    session_start();
    if (isset($_SESSION['username'])) {
        $isloggedin = true;
        $username = $_SESSION['username'];
        
    } else {
        $isloggedin = false;
        $username = "not logged in ";
    }
    
    $response = array();
    $response['status'] = 'success';
    $response['loggedin'] = $isloggedin;
    $response['username'] = $username;
    header('Content-Type: application/json');
    echo(json_encode($response));
    
?>