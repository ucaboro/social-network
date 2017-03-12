<?php
    require_once "imports.php";
    require_once "validation.php";
    //Get current values from database
    $user = getUser();
    $dob = ($user->dateOfBirth == null) ? "" : $user->dateOfBirth->format('Y-m-d'); //If date of birth already set in database, show it.
    $location = ($user->location == null) ? "" : $user->location; //$date->format('YmdHis')
    $firstName = $user->firstName;
    $lastName = $user->lastName;
    $email = "why@noEmail.com";
    $blogPrivacy = "0";
    $infoPrivacy = "0";
    $errors = array();

    //Post part for personal settings
    if(isset($_POST['dob_submit'])) {               //If submit button pressed and page reloaded, then validate and save to database
        //If dob has been changed and is non-empty
        if( checkBeforeUpdate($dob, $_POST['dob']) ) {
            echo $_POST['dob'];
            //Update dob variable
            $dob = trim($_POST['dob']);
        }
    }
    if(isset($_POST['location_submit'])) {
        if(checkBeforeUpdate($location, $_POST['location'])){
            echo $_POST['location'];
            $location = trim($_POST['location']);
        }
    }
    //Account part
    if(isset($_POST['first_name_submit'])){
        //Check field has been changed and is not empty
        if(checkBeforeUpdate($firstName, $_POST['first_name'])){
            echo $_POST['first_name'];
            $firstName = trim($_POST['first_name']);
        }
    }
    if(isset($_POST['last_name_submit'])){
        //Check field is not empty
        if(checkBeforeUpdate($lastName, $_POST['last_name'])){
            echo $_POST['last_name'];
            $lastName = trim($_POST['last_name']);
        }
    }
    if(isset($_POST['email_submit'])){
        //If field empty or unchanged
        if(!checkBeforeUpdate($email, $_POST['email'])){
            //Do nothing
        }
        //If not valid email
        else if (!validEmail(trim($_POST['email']))) {
            $errors[] = "Please provide a valid email address.";
        }
        //If not unique
        else if (!uniqueEmail(trim($_POST['email']))) {
            $errors[] = "There already exists an account for this email address";
        }
        //Passed validation
        else{
            //Update
            echo $_POST['email'];
            $email = trim($_POST['email']);
        }
    }
    if(isset($_POST['password_submit'])){
        $oldPassword = trim($_POST["old_password"]);
        $newPassword = trim($_POST["new_password"]);
        $confirmNewPassword = trim($_POST["confirm_new_password"]);
        $length = 7;
        //Check if old password is correct
        if(!checkPasswordOfLoggedInUser($oldPassword)){
            $errors[] = "Incorrect password.";
        }
        else if(!presence($newPassword) && !presence($confirmNewPassword)){
            //Do nothing (only if both passwords missing, otherwise return error that they do not match)
        }
        //Password matching
        else if(!matching($newPassword, $confirmNewPassword)){
            $errors[] = "New passwords do not match.";
        }
        //Security
        else if(!minLength($newPassword, $length) || !specialChars($newPassword)){
            $errors[] = "Passwords must be at least " . $length . " characters long and contain at least one non-alphabetical character, i.e. a number or symbol.";
        }
        else{
            echo "Updating passwords yo.";
        }
    }
    //Privacy part
    if(isset($_POST['blog_privacy_submit']))
    {
        //If value changed
        if($blogPrivacy != $_POST['blog_privacy']){
            echo $_POST['blog_privacy'];
            $blogPrivacy = $_POST['blog_privacy'];
        }
    }
    if(isset($_POST['info_privacy_submit']))
    {
        //If value changed
        if($infoPrivacy != $_POST['info_privacy']){
            echo $_POST['info_privacy'];
            $infoPrivacy = $_POST['info_privacy'];
        }
    }