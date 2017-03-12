<?php
    require_once "imports.php";
    require_once "validation.php";
    //Defaults
    $dob = "";
    $location = "";
    $firstName = "Harry";
    $lastName = "Harpsicord";
    $email = "h@H.com";
    $blogPrivacy = "0";
    $infoPrivacy = "0";
    /*Post part for personal settings*/
    //If submit button pressed and page reloaded, then validate and save to database
    if(isset($_POST['dob_submit']))
    {
        echo $_POST['dob'];
        $dob = trim($_POST['dob']);
    }
    if(isset($_POST['location_submit'])){
        echo $_POST['location'];
        $location = trim($_POST['location']);
        //Check field is not empty
        if(presence($location)){

        }
    }
    //Account part
    if(isset($_POST['first_name_submit'])){
        echo $_POST['first_name'];
        $firstName = trim($_POST['first_name']);
        //Check field is not empty
        if(presence($firstName)){

        }
    }
    if(isset($_POST['last_name_submit'])){
        echo $_POST['last_name'];
        $lastName = trim($_POST['last_name']);
        //Check field is not empty
        if(presence($lastName)){

        }
    }
    if(isset($_POST['email_submit'])){
        echo $_POST['email'];
        $email = trim($_POST['email']);
        //If field empty
        if(!presence($email)){

        }
        //If not valid email
        else if (!validEmail($email)) {
            //$errors[] = "Please provide a valid email address.";
        }
        //If not unique
        else if (!uniqueEmail($email)) {
            //$errors[] = "There already exists and account for this email address";
        }
        //Passed validation
        else{
            //Update
        }
    }
    if(isset($_POST['password_submit'])){
        echo "yo";
        $oldPassword = trim($_POST["old_password"]);
        $newPassword = trim($_POST["new_password"]);
        $confirmNewPassword = trim($_POST["confirm_new_password"]);
        //Check if old password is correct
        if(/*Password Correct*/true){
            $length = 7;
            if(!presence($newPassword) && !presence($confirmNewPassword)){

            }
            //Password matching
            else if(!matching($newPassword, $confirmNewPassword)){
                //$errors[] = "Passwords do not match.";
            }
            //Security
            else if(!minLength($newPassword, $length) || !specialChars($newPassword)){
                //$errors[] = "Passwords must be at least " . $length . " characters long and contain at least one non-alphabetical character, i.e. a number or symbol.";
            }
            else{
                //Update
            }
        }
    }
    //Privacy part
    if(isset($_POST['blog_privacy_submit']))
    {
        echo $_POST['blog_privacy'];
        $blogPrivacy = $_POST['blog_privacy'];
        /*Check value has changed
         * when setting original value to display, store this as old value, and compare to new before uploading
         */

    }
    if(isset($_POST['info_privacy_submit']))
    {
        echo $_POST['info_privacy'];
        $infoPrivacy = $_POST['info_privacy'];
    }