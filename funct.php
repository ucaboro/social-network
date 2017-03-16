<?php
    //Set error reporting on
    ini_set('display_errors', 1);
    error_reporting(E_ALL | E_STRICT | E_NOTICE);
    //Start session
    session_start();
    $statementFriendsOf2User="SELECT userID2 AS 'userID' FROM friendship WHERE isConfirmed = TRUE AND userID1 = ? UNION
                              SELECT userID1 AS 'userID' FROM friendship WHERE isConfirmed = TRUE AND userID2 = ? ";

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
        $stmt = $database->prepare("SELECT * FROM user WHERE userID = ? LIMIT 1");
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
        $stmt = $database->prepare("SELECT * FROM user WHERE email = ? LIMIT 1");
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
        $stmt = $database->prepare("INSERT INTO user (email, firstName, lastName, password, photoID) VALUES (?, ?, ?, ?,0)");
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
        $stmt = $database->prepare("UPDATE user SET $columnName = ? WHERE userID = ?");
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


    function getBlogsFromSearchTerm(string $term) {
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
        $statement = $db -> prepare("SELECT * FROM blogpost WHERE
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
    function createBlogObject($row) {
        return new blogPost($row["postID"], $row["headline"], $row["post"], getUserWithID($row["userID"]), new DateTime($row["time"]));
    }


    function addNewCustomInterest(string $interestName, int $userID) {
        //Get interest id from name, if in database, if not in database will return -1
        $interestID = findInterestIDFromInterestName($interestName);
        //If interest does not already exist in the database
        if($interestID == -1){
            uploadNewInterest($interestName,$userID);
        }
        //If interest already exists in the database, just assign it to this user
        else{
            assignInterestToUser($interestID, $userID);
        }
    }
    /*
     * Upload a new user created interest to the interests database
     */
    function uploadNewInterest(string $interest, int $userID) {
        $database= new db();
        $database->connect();
        //Perform query as prepared statement
        $stmt = $database->prepare("INSERT INTO interests (name)
                                    VALUES (?)");
        $stmt->bind_param("s", $interest);
        $result = $stmt->execute();
        $interestID = $database->lastInsertId();
        if (!$result) {
            printf("Errormessage: %s\n", $database->getError());
            die("Database query failed.");
        }
        else{
            assignInterestToUser($interestID, $userID);
        }
    }


    /*
     * Link a user and an interest in the interests assignment database, i.e. record that the user has that interest
     */
    function assignInterestToUser(int $interestID, int $userID) {
        $database= new db();
        $database->connect();
        //Perform query as prepared statement
        $stmt = $database->prepare("INSERT INTO interestsassignment (interestID, userID) VALUES (?, ?)");
        $stmt->bind_param("ii", $interestID, $userID);
        $result =$stmt->execute();
        check_query($result, $database);
    }

    //Should use object for this
    function assignInterestToUserFromName(string $name, int $userID) {
        $interestID = findInterestIDFromInterestName($name);
        assignInterestToUser($interestID, $userID);
    }
    /*
     *
     */
    function findInterestIDFromInterestName(string $name): int{
        //Set initially as -1, a returned value of -1 indicated interest not found
        $id = -1;
        $interestName = trim($name);
        $database= new db();
        $database->connect();
        $stmt = $database->prepare("SELECT interestID FROM interests WHERE name = ? LIMIT 1");
        $stmt->bind_param("s", $interestName);
        $stmt->execute();
        $stmt->bind_result($id);
        if($stmt->fetch()){
            return $id;
        }
        else{
            $stmt->close();
            return -1;
        }
    }

    function getInterestObjectFromInterestName(string $name): interest
    {
        return new interest(findInterestIDFromInterestName($name), $name);
    }


    /*
     * Check if user already has a given
     */
    function userAlreadyHasInterest(string $interestName, $userID): bool{
        return userAlreadyHasInterestFromID(findInterestIDFromInterestName($interestName), $userID);
    }

    function userAlreadyHasInterestFromID(int $interestID, int $userID): bool{
        //Set initially as -1, a returned value of -1 indicated interest not found
        $id = null;
        $database= new db();
        $database->connect();
        $stmt = $database->prepare("SELECT interestID FROM interestsassignment WHERE interestID = ? AND userID = ? LIMIT 1");
        $stmt->bind_param("ii", $interestID, $userID);
        $stmt->execute();
        $stmt->bind_result($id);
        $stmt->fetch();
        $stmt->close();

        if(is_null($id)){
            return false;
        }
        else{
            return true;
        }
    }

    function interestAlreadyInDatabase(string $interestName): bool{
        if(findInterestIDFromInterestName($interestName) > 0){
            return true;
        }
        else{
            return false;
        }

    }

    function userObjectAlreadyHasInterest(string $name, user $user){
        return in_array($name, $user->getInterestNames());
    }

    function userObjectAlreadyHasInterestFromID(int $interestID, user $user){
        return in_array($interestID, $user->getInterestIDs());
    }

    function getExistingInterests(): array{
        $db = new db();
        $db->connect();
        $result = $db -> query("SELECT * FROM interests");
        check_query($result, $db);
        return $result->fetch_array(MYSQLI_NUM);
    }

    function getExistingInterestNames(): array{
        $names = array();
        $db = new db();
        $db->connect();
        $result = $db -> query("SELECT name FROM interests");
        check_query($result, $db);
        while($name = $result->fetch_array(MYSQLI_NUM))
        {
            $names[] = $name[0];
        }
        return $names;
    }

    /*
     * Removes the interest with the specified ID from the currently logged in user.
     */
    function removeInterestWithID(int $interestID){
        $userID = getUserID();
        $db = new db();
        $db->connect();
        $stmt = $db -> prepare("DELETE FROM interestsassignment WHERE interestID = ? AND userID = ?");
        $stmt->bind_param("ii", $interestID, $userID);
        $stmt->execute();
    }

    /*
    * Get an integer score of the commonalities between two users
    */
    function getUsersObjectCommonalityScore(user $user1, user $user2, int $interests, int $friendsInCommon): int{
        //Number of interests in common
        $score = $interests;
        //Number of friends in common
        $score += $friendsInCommon;
        //If they live in same city
        if($user1 -> location === $user2 -> location){
            $score += 4;
        }
        //Difference in ages between two users
        $ageDiff = abs( ($user1 -> getAge() ) - ($user2 -> getAge()) );
        //Arbitrary measure of similarity in age
        $ageSimilarity = 4 - $ageDiff;
        if($ageSimilarity > 0){
            $score += $ageSimilarity;
        }
        return $score;
    }

    /*
    * Returns an array of users who match the given search string.
    * Ordered according to commonalities with logged in user
    */
    function getUsersCollaborativeSearch(string $filter = NULL): array {
        global $statementFriendsOf2User;
        //Logged in User
        $currentUser = getUser();
        $currentUserID = $currentUser -> id;
        //Perform search for users according to a string filter
        $db = new db();
        $db->connect();
        //If no filter provided, no filter is applied
        if (is_null($filter)) {
            $statement = $db -> prepare("SELECT * FROM user , photo WHERE user.userID NOT IN ($statementFriendsOf2User) AND user.userID != ? AND photo.photoID = user.photoID");
            $statement->bind_param("iii",$currentUserID, $currentUserID, $currentUserID);
        }
        //If filter provided, search for users with names like that filter
        else{
            global $searchParameters411;
            $searchTerm = '%'.preg_replace('/\s+/','',$filter).'%';
            $statement = $db -> prepare("SELECT * FROM user , photo WHERE user.userID != ? AND user.userID  NOT IN ($statementFriendsOf2User) AND $searchParameters411 AND photo.photoID = user.photoID");
            $statement->bind_param("iiissssss",$currentUserID, $currentUserID, $currentUserID, $searchTerm,$searchTerm,$searchTerm,$searchTerm,$filter,$searchTerm);
        }
        $statement->execute();
        $result = $statement->get_result();

        //Return ordered array according to commonalities
        $usersArray = array();
        //Loop through results of query, assigning a row from the table to an associative array
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            $id = $currentUser -> id;
            //Find common interest score between current user and user stored in row of table for this loop
            $interests = getCommonInterestsBetweenUsersWithID($currentUser -> id, $row["userID"]);
            //Find common friends score between current user and user stored in row of table for this loop
            $friendsInCommon = getCommonFriendsBetweenUsersWithID($currentUser -> id, $row["userID"]);
            //Get overall commonality score for user pair
            $score = getUsersCommonalityScore($currentUser, $row["location"], new DateTime($row["date"]), $interests, $friendsInCommon);
            //Assign user to array with the score as a key
            $usersArray[] = array($score, createUserObject($row), $interests, $friendsInCommon); //createUserObject($row)//getUserWithID($row["userID"]);
        }
        //Sort high to low according to key value
        usort($usersArray, 'sortByScore');
        return $usersArray;
    }

    /*
     * Sorts the array of users for collaborative searching, according to the first value in the array ($score), from high to low
     */
    function sortByScore($b, $a) {
        return $a[0] <=> $b[0];;
    }

    /*
     * Get an integer score of the commonalities between two users
     */
    function getUsersCommonalityScore(user $user1, $user2Location, DateTime $user2dob, int $interests, int $friendsInCommon): int{
        $user2Age = $user2dob->diff(new DateTime())->format('%y');
        //Number of interests in common
        $score = $interests;
        //Number of friends in common
        $score += $friendsInCommon;
        //If they live in same city, must check against null, so it doensn't count people with no location as living in the same place
        if(!is_null($user2Location) && ($user1 -> location === $user2Location))
        {
            $score += 4;
        }
        //Difference in ages of two users
        $ageDiff = abs( ($user1 -> getAge() ) - $user2Age );
        //Arbitrary measure of similarity in ages
        $ageSimilarity = 4 - $ageDiff;
        if($ageSimilarity > 0){
            $score += $ageSimilarity;
        }
        return $score;
    }
