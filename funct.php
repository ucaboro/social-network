<?php
    //Set error reporting on
    ini_set('display_errors', 1);
    error_reporting(E_ALL | E_STRICT | E_NOTICE);

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
        if(!isset($_SESSION['userID'])){
            return false;
        }
        else{
            return true;
        }
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
            $database->closeConnection();
            return $user;
        }
        else{
            $database->closeConnection();
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
            $database->closeConnection();
            redirectTo("index.php");
        }
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
    function getBlogFromSearchTerm(string $term){
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

    }


    function getBlogFromUserSearch(string $term){

    }
    /*
     * Create and return a new blog object from the associative array produced by a SQL query
     */
    function createBlogObject($row){
        return new blogPost($row["postID"], $row["headline"] , getUserWithID($row["userID"]), new DateTime("time"));
    }


