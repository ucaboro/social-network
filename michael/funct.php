<?php

    //Start session so that it can be used to check if user logged in
    session_start();
    function redirectTo($location){
        header("Location: " . $location);
        exit;
    }

    //http://stackoverflow.com/questions/4323411/how-can-i-write-to-console-in-php
    function debug_to_console( $data ) {

        if ( is_array( $data ) )
            $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
        else
            $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

        echo $output;
    }

    function check_query($result, $connection){
        if (!$result) {
            printf("Errormessage: %s\n", mysqli_error($connection));
            die("Database query failed.");
        }
    }

    //Check if the user is logged in by checking if the session value for the userID has been set
    function isLoggedIn(){
        if (!isset($_SESSION['userID'])){
            redirectTo("form.php");
        }
    }

    function checkLogin($email, $password, $connection){
        $user = getUser($email, $connection);
        $storedHash = $user['password'];
        //If password matches, set the Session value for userID, so the site knows the user is logged in
        if(password_verify($password, $storedHash)){
            //debug_to_console("userID = " . $user["userID"]);
            $_SESSION["userID"] = $user["userID"];
            return true;
        }
        else{
            return false;
        }
    }

    //Returns a user for a given email (as associative array?)
    function getUser($email, $connection){
        $safe_email = mysqli_real_escape_string($connection, $email);
        $query = "SELECT * FROM User WHERE email = '{$safe_email}' LIMIT 1";
        $result = mysqli_query($connection, $query);
        //Check to see if query successful
        check_query($result, $connection);
        if($user = mysqli_fetch_assoc($result)){
            return $user;
        }
        else{
            return null;
        }
    }


    function passwordEncrypt($password){
        return password_hash($password, PASSWORD_DEFAULT);
    }

    function password_check($password, $storedHash){
        return (password_verify($password, $storedHash));
    }


