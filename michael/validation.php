<?php

    //Accumulate errors in array for different fields
    //$errors['value'] = "value can't be blank";
    ///////////////////////////////////////////////CONSTITUENT PARTS///////////////////////////////////////////////
    //Check whether value has been entered
    //MUST REMOVE WHITESPACE ETC.  (have already done trim() )
    function presence($value)
    {
        if(!isset($value) || empty($value))
        {
            return false;
        }
        else{
            return true;
        }

    }

    /////////////////EMAIL//////////////

    //Check entered value is a valid email address
    function validEmail($email)
    {
        $emailClean = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (filter_var($emailClean, FILTER_VALIDATE_EMAIL))
        {
           return true;
        }
        else
            {
            return false;
        }
    }

    //Check entered value is not already stored in database, i.e. is unique
    function uniqueEmail($email, $connection){
        //Get all email addresses currently stored in database
        $query = "SELECT email FROM User ";
        $result = mysqli_query($connection, $query);
        //Check if query failed
        if (!$result) {
            printf("Errormessage: %s\n", mysqli_error($connection));
            die("Database query failed.");
        }
        else{
            //Loop through all returned results and compare email with stored emails
            while($row = mysqli_fetch_row($result))
            {
                if( $row[0] === $email)  //Can i just user fetch_row row[0]? as only one column? (row is faster than assoc)
                {
                    return false;
                }
            }
            //If all rows checked without matching
            return true;
        }

    }

    /////////////////NAMES//////////////
    function onlyAlphabetical($name){
        //Or hyphen? for double barrelled?
    }

    /////////////////PASSWORD///////////
    function matching($password1, $password2){
        return $password1 === $password2;
    }
    //Test if password above min length
    function minLength($password, $length){
        return strlen($password) > $length;
    }

    function specialChars($password){
        return true;
    }

    function commonWords($password, $words){

    }
    ///////////////////////////////////////////////FINISHED FUNCTIONS///////////////////////////////////////////////

    //If not present -> break
    //I.e. need to implement a form of laziness

    /////////////////EMAIL//////////////
    //Full validation for email address for register page
    function validateSignUpEmail($email, $connection){
        $presence = presence($email);
        $isEmail = validEmail($email);
        $uniqueness = uniqueEmail($email, $connection);
        return array($presence, $isEmail, $uniqueness);
    }

    /////////////////NAMES//////////////

    function validateNames($name){
        $presence = presence($name);
        $alphabetical = onlyAlphabetical($name);
        return array($presence, $alphabetical);
    }

    /////////////////PASSWORD///////////
    function validateSignUpPassword($firstPW, $secondPW){
        $presence1 = presence($firstPW);
        $presence2 = presence($secondPW);
        $presence = ($presence1 && $presence2);
        $match = matching($firstPW, $secondPW);
        $minLength = minLength($firstPW);
        $specialChars = specialChars($firstPW);
        return array($presence, $match,$minLength, $specialChars);
    }

