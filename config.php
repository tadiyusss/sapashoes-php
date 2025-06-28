<?php 
    session_start();
    $HOST = '';
    $USER = '';
    $PASS = '';
    $DB = '';

    $conn = mysqli_connect($HOST, $USER, $PASS, $DB);
    if (mysqli_connect_errno()) {
        echo 'MYSQL CONNECTION ERROR: '. mysqli_connect_error();
        exit();
    }
?>