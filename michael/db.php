<?php
    $dbhost = "Localhost";
    $dbuser = "michael";
    $dbpass = "aQRpuC5GqyTFCHwD";
    $dbname = "networking";
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    if(mysqli_connect_errno()){
        die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")" );
    }

    //Start session so that it can be used to check if user logged in
    //session_start();
?>

