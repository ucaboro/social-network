<?php
    //Set error reporting on
    ini_set('display_errors', 1);
    error_reporting(E_ALL | E_STRICT | E_NOTICE);
    //Start session
    session_start();

    //Simple redirect function
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

    //Checks if a given query ($result) succeeded, if not dies
    function check_query($result, $database){
        if (!$result) {
            printf("Errormessage: %s\n", $database->getError());
            die("Database query failed.");
        }
    }

    //Check if the user is logged in by checking if the session value for the userID has been set
    function checkLoggedIn(){
        if (!isset($_SESSION['userID'])){
            redirectTo("login.php");
        }
    }

    //Check if user logged in and return a boolean
    function isLoggedIn(){
        return isset($_SESSION["userID"]);
    }

    //Function to check if email password pair match at login
    function checkLogin($email, $password){
        $user = getUserFromEmail($email);
        $storedHash = $user['password'];
        //If password matches, set the Session value for userID, so the site knows the user is logged in
        if(password_verify($password, $storedHash)){
            $_SESSION["userID"] = $user["userID"];
            return true;
        }
        else{
            return false;
        }
    }

    //Function to check if email password pair match at login
    function checkPasswordOfLoggedInUser($password){
        $user = getUserRowFromID($_SESSION['userID']);
        $storedHash = $user['password'];
        //If password matches, set the Session value for userID, so the site knows the user is logged in
        if(password_verify($password, $storedHash)){
            return true;
        }
        else{
            return false;
        }
    }
    //Get User from Id, but not as a object, rather as an associative array that contains the hashed password
    function getUserRowFromID($id){
        //Create database object
        $database = new db();
        //Connect to database
        $database->connect();
        //Query as prepared statement
        $stmt = $database->prepare("SELECT * FROM User WHERE userID = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        //Check to see if query successful
        check_query($result, $database);
        if($user = mysqli_fetch_assoc($result)){
            return $user;
        }
        else{
            return null;
        }
    }

    //Returns a user for a given email
    function getUserFromEmail($email){
        //Create database object
        $database = new db();
        //Connect to database
        $database->connect();
        //Query as prepared statement
        $stmt = $database->prepare("SELECT * FROM User WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        //Check to see if query successful
        check_query($result, $database);
        if($user = mysqli_fetch_assoc($result)){
            return $user;
        }
        else{
            return null;
        }
    }

    //Get userID from given email, used to update session
    function getUserIDFromEmail($email){
        $user = getUserFromEmail($email);
        return $user['userID'];
    }

    //Register a new user to the database
    function register($firstName, $lastName, $email, $password){
        //Encrypt password
        $hashedPassword = passwordEncrypt($password);
        //Create database object
        $database = new db();
        //Connect to database
        $database->connect();
        //Perform query as prepared statement
        $stmt = $database->prepare("INSERT INTO User (email, firstName, lastName, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $firstName, $lastName, $hashedPassword);
        $result =$stmt->execute();
        //Check if query was successful
        if (!$result) {
            printf("Errormessage: %s\n", $database->getError());
            die("Database query failed.");
        }
        else{
            //If successful redirect
            $_SESSION["userID"] = getUserIDFromEmail($email);
            redirectTo("index.php");
        }
    }

    /** Update a cell in the user database for a user defined by userID
     * @param $columnName   name of column to update, i.e. "location"
     * @param $cellContent  content to update with, i.e. "Chicago"
     * @param $userID       id of user to to be updated
     * Note columnName is not protected by the prepared statement, so if it was user inputted it would have to be escaped
     * However as it will just be one of several predefined strings and hardcoded this is not a danger
     */
    function updateUserCell($columnName, $cellContent, $userID){
        //Create database object
        $database = new db();
        //Connect to database
        $database->connect();
        //Perform query as prepared statement
        $stmt = $database->prepare("UPDATE User SET $columnName = ? WHERE userID = ?");
        $stmt->bind_param("si", $cellContent, $userID);
        $result =$stmt->execute();
        //Check if query was successful
        check_query($result, $database);
    }

    //Encrypt a password
    function passwordEncrypt($password){
        return password_hash($password, PASSWORD_DEFAULT);
    }

    //Check if a password matches a storedHash
    function password_check($password, $storedHash){
        return (password_verify($password, $storedHash));
    }

    /*
     * Returns an array of all blogs that contain a given search term as a whole word
     */
    function getBlogsFromSearchTerm00(string $term){
        $db = new db();
        $db->connect();
        $searchTerm = '% '.$term.' %';
        $statement = $db -> prepare(" SELECT * FROM BlogPost WHERE post LIKE ? OR headline LIKE ?");
        $statement->bind_param("ss",$searchTerm,$searchTerm);
        $statement->execute();
        $result = $statement->get_result();
        $blogArray = array();
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            $blogArray[$row["postID"]] = createBlogObject($row); //Do i want to get this to output in a certain order?
        }
        return $blogArray;
    }

    function getBlogsFromSearchTerm(string $term){
        //TODO: Change this to getUser();
        if(isset($_SESSION["userID"]))
        {
            $currentUserID = $_SESSION["userID"];
        }
        else{
            // TODO:  Should change this to null or something for final version
            $currentUserID = 1;
        }
        $db = new db();
        $db->connect();
        $searchTerm = '% '.$term.' %';
        $statement = $db -> prepare("SELECT * FROM BlogPost WHERE 
                                    (post LIKE ? OR headline LIKE ?) 
                                    AND userID IN 
                                    (SELECT userID2 as 'userID' FROM friendship 
                                    WHERE userID1 = ? AND isConfirmed = True UNION
                                    SELECT userID1 as 'userID' FROM friendship
                                    WHERE isConfirmed = TRUE AND userID2 = ?)
                                    ORDER BY time DESC
                                    LIMIT 20;");
        $statement->bind_param("ssii",$searchTerm,$searchTerm,$currentUserID,$currentUserID);
        $statement->execute();
        $result = $statement->get_result();
        $blogArray = array();
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            $blogArray[$row["postID"]] = createBlogObject($row);
        }
        return $blogArray;
    }




    /*
     * Create and return a new blog object from the associative array produced by a SQL query
     */
    function createBlogObject($row){
        return new blogPost($row["postID"], $row["headline"], $row["post"], getUserWithID($row["userID"]), new DateTime($row["time"]));
    }


