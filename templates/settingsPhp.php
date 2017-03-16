<?php
    require_once "imports.php";
    require_once "validation.php";
    //Get current values from database
    $user = getUser();
    $dob = ($user->dateOfBirth == null) ? "" : $user->dateOfBirth->format('Y-m-d'); //This defaults to today apparently
    $location = ($user->location == null) ? "" : $user->location;
    $firstName = $user->firstName;
    $lastName = $user->lastName;
    $email = $user->email;
    $blogPrivacy = $user->blogVisibility;
    $infoPrivacy = $user->infoVisibility;
    $errors = array();
    $userID = $user->getUserID(); //This is public but for some reason we have a getter

    //Post part for personal settings
    if(isset($_POST['dob_submit'])) {               //If submit button pressed and page reloaded, then validate and save to database
        //If dob has been changed and is non-empty
        if( checkBeforeUpdate($dob, $_POST['dob']) ) {
            //Update dob variable
            $dob = trim($_POST['dob']);
            updateUserCell("date", $dob, $userID);
        }
    }
    if(isset($_POST['location_submit'])) {
        if(checkBeforeUpdate($location, $_POST['location'])){
            $location = trim($_POST['location']);
            updateUserCell("location", $location, $userID);
        }
    }
    //Account part
    if(isset($_POST['first_name_submit'])){
        //Check field has been changed and is not empty
        if(checkBeforeUpdate($firstName, $_POST['first_name'])){
            $firstName = trim($_POST['first_name']);
            updateUserCell("firstName", $firstName, $userID);
        }
    }
    if(isset($_POST['last_name_submit'])){
        //Check field is not empty
        if(checkBeforeUpdate($lastName, $_POST['last_name'])){
            $lastName = trim($_POST['last_name']);
            updateUserCell("lastName", $lastName, $userID);
        }
    }
    if(isset($_POST['email_submit'])){ //Are you sure pop-ups?
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
            $email = trim($_POST['email']);
            updateUserCell('email', $email, $userID);
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
            updateUserCell('password', passwordEncrypt($newPassword), $userID);
        }
    }
    //Privacy part
    if(isset($_POST['blog_privacy_submit']))
    {
        //If value changed
        if($blogPrivacy != $_POST['blog_privacy']){
            $blogPrivacy = $_POST['blog_privacy'];
            updateUserCell('blogVisibility', $blogPrivacy, $userID);
        }
    }
    if(isset($_POST['info_privacy_submit']))
    {
        //If value changed
        if($infoPrivacy != $_POST['info_privacy']){
            $infoPrivacy = $_POST['info_privacy'];
            updateUserCell('infoVisibility', $infoPrivacy, $userID);
        }
    }

    //Existing Interests part
    if(isset($_POST['existing_interests_submit'])) {
        //Interest object posted from select, is that possible?
        $interestName = trim($_POST['existing_interests']);
        //$interest = getInterestObjectFromInterestName($interestName);
        $interestID = findInterestIDFromInterestName($interestName);
        if (!userObjectAlreadyHasInterestFromID($interestID , $user)) {
            //assignInterestToUserFromName($name, $userID);
            assignInterestToUser($interestID, $userID);
        }
    }

    //New Custom interests part
    if(isset($_POST['new_interests_submit']))
    {
        $interestName = trim($_POST['new_interests']);
        //Upload interest if user does not already have it stored in the database // Might be better to keep a list of interests and comapre to that
        if (!userObjectAlreadyHasInterest($interestName, $user)) {
            uploadNewInterest($interestName, $userID);
        }
    }