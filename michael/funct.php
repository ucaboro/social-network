<?php

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

    function check_query($result){
        if (!$result) {
            printf("Errormessage: %s\n", mysqli_error($connection));
            die("Database query failed.");
        }
    }


    function checkLoginOld($email, $password, $connection)
    {
        $query = "SELECT userID, email, password FROM User ";

        $result = mysqli_query($connection, $query);

        if (!$result) {
            printf("Errormessage: %s\n", mysqli_error($connection));
            die("Database query failed.");
        }

        while($row = mysqli_fetch_assoc($result))
        {
            ///THIS SHOULD NOT WORK LOOK AT ROW[$password]
            //$row['password']
            if( ($row[$password] === $password) && ($row[email] === $email) ) ///////USERNAME? NOT EMAIL?
            {
                $userID = $row[userID];
                mysqli_free_result($result); //?
                return $userID;
            }

        }
        return -1;
    }

    function checkLogin($email, $password, $connection){
        $user = getUser($email);
        $storedHash = $user['password']; ///CHECK THISSS!
        return password_verify($password, $storedHash);
    }

    //Returns a user for a given email (returns user as what? damn weak type bullshit
    function getUser($email){
        $safe_email = mysqli_real_escape_string($connection, $email);
        $query = "SELECT * FROM User WHERE email = {$safe_email} LIMIT 1";
        $result = mysqli_query($connection, $query);
        //Check to see if query successful
        check_query($result);
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


