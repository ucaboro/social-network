<?php
  require_once "funct.php"; //Later we can make them one file, but i suppose this will do for now. Yea I agree with that.
/* Returns the mysqli_result object as an array.
 * $result: the mysqli_result object.
 * $keyColumn: the name of the column to use as the key in the array.
 * $valueColumn: the name of the column to use as the value in the array.
 * Returns an array containing the result.
 */
function getArrayFromResult($result, $keyColumn, $valueColumn) {
  // Loop through the result and return an array
  $array = array();
  while($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $array[$row[$keyColumn]] = $row[$valueColumn];
  }
  return $array;
}

/*
 * Returns the specified value from the GET request, or NULL if no such value was passed.
 */
function getValueFromGET(string $key) {
  if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET[$key])) {
    return $_GET[$key];
  } else {
    return NULL;
  }
}

/*
 * Inserts a message into datbase
 */
 function sendMessage($message, $circleID) {

  $userID = getUser()->id;
  //$timeUnformatted = new DateTime();
  //$time = date_format($timeUnformatted, 'Y-m-d H:i:s');

  $db = new db();
  $db->connect();
  $stmt = $db->prepare("INSERT INTO circlemessage (circleID, userID, message, time) VALUES (?,?,?,NOW())");
  $stmt->bind_param("iis", $circleID, $userID, $message);
  $stmt->execute();

  return $stmt;
}


/*
 * Gets last circleID and increments by 1 for proper new circle creation
 */
 function getNewCircleID () {

  $db = new db();
  $db->connect();
  //return last circleID
  $stmt = $db->prepare("SELECT circleID FROM `circle` ORDER BY circleID DESC LIMIT 1");
  $stmt->execute();
  $result = $stmt->get_result();

  $row = $result->fetch_array(MYSQLI_ASSOC);

  return $row["circleID"]+1;
}

/*
 * Create new circle in the database based on the color and name
 */
 function addNewCircle ($name, $color) {
  $circleID = getNewCircleID();
  $userID = getUser()->id;

  $db = new db();
  $db->connect();

  //insert new in circle
  $stmt = $db->prepare("INSERT INTO circle (circleID, circleName, circleColor) VALUES (?,?,?)");
  $stmt->bind_param("iss", $circleID, $name, $color);
  $stmt->execute();

  //assign yourself to the new circle
  $stmt = $db->prepare("INSERT INTO circlemembership (circleID, userID) VALUES (?,?)");
  $stmt->bind_param("ii", $circleID, $userID);
  $stmt->execute();


}

/*
 * Returns a user object representing the currently logged-in user, or NULL if no user is logged in.
 */
function getUser() {
  return getUserWithID(getUserID());

}
/*
 * Returns a user ID representing the currently logged-in user, or NULL if no user is logged in.
 */
function getUserID() {
  if(isset($_SESSION["userID"]))
  {
    return $_SESSION["userID"];
  }
  else{
    // TODO:  Should change this to null or something for final version. Do we need to add checking for the null case?
      return 1;
  }
}

/*
 * Returns an array of the circles that a user is a member of. Key is circle ID, value is circle object.
 */
function getCirclesForUser(user $user) {
  $userID = $user->getUserID();
  $db = new db();
  $db->connect();
  $statement = $db -> prepare("SELECT circleID FROM circlemembership WHERE userId = ?");
  $statement ->bind_param("i", $userID);
  $statement->execute();
  $result = $statement->get_result();

  $circles=array();
  while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $circles[$row["circleID"]] = getCircleWithID($row["circleID"]);
  }
  return $circles;
}

/*
 *Returns an array of all associated circles IDs from the database.
 */
 function getUserCircles($userID) {
  $db = new db();
  $db->connect();
  $stmt = $db->prepare("SELECT circleID, userID FROM circlemembership WHERE userID = ?");
  $stmt->bind_param("i", $userID);
  $stmt->execute();
  $result = $stmt->get_result();

  return getArrayFromResult ($result, "circleID", "userID");
}

/*
 * Returns an array of the circles that a user is a member of. Key is circle ID, value is circle object.
 */
 function getCircleNames($circleID) {
  $db = new db();
  $db->connect();
  $stmt = $db->prepare("SELECT circleID, circleName, circleColor FROM circle WHERE circleID = ?");
  $stmt->bind_param("i", $circleID);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_array(MYSQLI_ASSOC);

  return array (new circle($row["circleID"], $row["circleName"], $row["circleColor"]));
}

/*
 * Returns a circle object for the circle with the given ID.
 * $id: the ID of the circle to return.
 */
function getCircleWithID(int $id) {
  $db = new db();
  $db->connect();
  $statement = $db->prepare("SELECT circleID, circleName, circleColor FROM circle WHERE circleID = ?");
  $statement ->bind_param("i", $id);
  $statement->execute();
  $result = $statement->get_result();
  while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $circle = new circle($row["circleID"],$row["circleName"],$row["circleColor"],getUsersInCircleWithID($row["circleID"]));
  }
  return $circle;
}

/*
 * Returns an array of User Object. Key is user ID and Value is User object.
 * $id: the ID of the circle from which the user list is returned.
 */
function getUsersInCircleWithID(int $id) {
  $db = new db();
  $db->connect();
  $statement = $db -> prepare("SELECT userID FROM circlemembership WHERE circleID = ?");
  $statement ->bind_param("i", $id);
  $statement->execute();
  $result = $statement->get_result();

  $users = array();
  while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $users[$row["userID"]] = getUserWithID($row["userID"]);
  }
  return $users;
}

/*
 * Returns an array of message objects consisting of all the messages in a particular circle, in date descending order.
 * Key is message ID, value is message object.
 * $circle: a circle object representing the circle for which the messages should be returned.
 */
function getMessagesInCircle(circle $circle) {
  $db = new db();
  $db->connect();
  $id=$circle->getCircleID();
  $statement = $db -> prepare("SELECT * FROM circlemessage WHERE circleID = ? ORDER BY time DESC");
  $statement ->bind_param("i", $id);
  $statement->execute();
  $result = $statement->get_result();

  $messagesArray = array();
  while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $message = new message($row["messageID"],$circle,getUserWithID($row["userID"]), new DateTime($row["time"]),$row["message"]);
    $messagesArray[$row["messageID"]] = $message;
  }

  return $messagesArray;
}

/*
 * Returns a user object for the user with the specified ID.
 */
function getUserWithID(int $id) {
  $db = new db();
  $db->connect();
  $statement = $db->prepare("SELECT user.userID AS userID, firstName, lastName, email, photo.filename AS filename,
                              user.date AS date, location, blogVisibility, infoVisibility  FROM user, photo WHERE photo.photoID = user.photoID AND user.userID = ?");
  $statement->bind_param("i", $id);
  $statement->execute();
  $result = $statement->get_result();

  $row = $result->fetch_array(MYSQLI_ASSOC);
  return createUserObject($row);
}

/*
 * Gets an array of the photos that the specified user has uploaded. Key is photo ID, value is photo object.
 * Optional limit on the number of items returned. Set $limit to 0 for no limit. Photos are returned in date-descending order.
 */
function getPhotosOwnedByUser(user $user, int $limit = 0): array {
  $userID =$user->getUserID();

// Sets a default number of photos to be returned if no limit is specified.
  if ($limit == 0) { $limit = 18; }
  $db = new db();
  $db->connect();
  $statement = $db -> prepare("SELECT * FROM photo WHERE userID = ? AND isArchived=0 LIMIT ?");
  $statement ->bind_param("ii", $userID, $limit);
  $statement->execute();
  $result = $statement->get_result();

  $photosArray = array();
  while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $photosArray[$row["photoID"]] = getPhotoWithID($row["photoID"]);
  }

  return $photosArray;
}

function getPhotosOwnedByUserInCollection(user $user, int $collectionID, int $limit = 0): array {
    $userID =$user->getUserID();
    //TODO: do we want a limit?
    // Sets a default number of photos to be returned if no limit is specified.
    if ($limit == 0) { $limit = 18; }
    $db = new db();
    $db->connect();
    $statement = $db -> prepare("SELECT * FROM photo WHERE userID = ? AND collectionID = ? AND isArchived=0 LIMIT ?");
    $statement ->bind_param("iii", $userID,$collectionID, $limit);
    $statement->execute();
    $result = $statement->get_result();

    $photosArray = array();
    while($row = $result->fetch_array(MYSQLI_ASSOC)){
        $photosArray[$row["photoID"]] = getPhotoWithID($row["photoID"]);
    }

    return $photosArray;
}

/*
 * Returns an array of the blog posts that the specified user has posted. Key is blogPost ID, value is blogPost object.
 * Optional limit on the number of items returned. Set $limit to 0 for no limit. Posts are returned in date-descending order.
 */
function getBlogPostsByUser(user $user, int $limit) {

 $userID =$user->getUserID();

$db = new db();
$db->connect();
if (!isset($limit) || ($limit==0)) {
  $statement = $db -> prepare("SELECT postID,headline,post,time FROM blogpost WHERE userID = ? ORDER BY time DESC");
  $statement ->bind_param("i",$userID );
} else {
  $statement = $db -> prepare("SELECT postID,headline,post,time FROM blogpost WHERE userID = ? ORDER BY time DESC LIMIT ?");
  $statement ->bind_param("ii", $userID, $limit);
}
$statement->execute();
$result = $statement->get_result();

$blogPostsArray = array();
while($row = $result->fetch_array(MYSQLI_ASSOC)){
  $blogPostsArray[$row["postID"]] = new blogPost($row["postID"], $row["headline"], $row["post"], $user, new DateTime($row["time"]));
}

return $blogPostsArray;
}

/*
 * Returns an array of feed items represeting the recent activity feed for the currently logged in user.
 * Items are in date-descending order. Values are interaction objects. The last 20 items only are returned.
 */
function getRecentActivityFeed() {
  // Get ther currently logged in user.
  // TODO: Neet to make sure the function is actually returning the currently logged-in user.
  $user = getUser();
  $userID = $user->getUserID();

  $db = new db();
  $db->connect();

  $mainArray = array();
  $sortArray = array();

  // Gets the last 20 blogposts made by the friends of the currently logged-in user where available.
  $statement = $db -> prepare("SELECT * from blogpost
                                where userID in (
                                select userID2 as 'userID' from friendship
                                where isConfirmed = true and userID1 = ? union
                                select userID1 as 'userID' from friendship
                                where isConfirmed = true and userID2 = ?)
                                ORDER BY time Desc
                                LIMIT 20");
  $statement ->bind_param("ii",$userID,$userID);
  $statement->execute();
  $result = $statement->get_result();
  while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $sortArray[strtotime($row["time"])]= getBlogPostWithID($row["postID"]);
  }

  // Gets the last 20 messages sent in the circles that the user is currently part of.
  $statement = $db -> prepare("SELECT * from circlemessage
                                where circleID in
                                (Select circleID from circlemembership where userID = ?)
                                ORDER BY time Desc
                                LIMIT 20");
  $statement ->bind_param("i",$userID);
  $statement->execute();
  $result = $statement->get_result();
  while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $sortArray[strtotime($row["time"])] = new message($row["messageID"], getCircleWithID($row["circleID"]), getUserWithID($row["userID"]), new DateTime($row["time"]), $row["message"]);
  }

  // Gets the last 20 messages sent in the circles that the user is currently part of.
  $statement = $db -> prepare("SELECT * from photo where isArchived=0 AND photoID in
                              (select photoID from photo where userID in
                              (select userID2 as 'userID' from friendship
                              where isConfirmed = true and userID1 = ? union
                              select userID1 as 'userID' from friendship
                              where isConfirmed = true and userID2 = ?)
                              union
                              select photoID from photocollectionassignment where collectionID in
                              (select collectionID from photocollection where isVisibleToFriendsOfFriends = 1
                              and userID in
                              -- this selection makes sure the friends of friends are selected while making sure
                              -- the user himself and his direct friends are not selected.
                              (select userID from user where userID != ? and
                              userID not in
                              -- makes sure direct friends of ther user is not selected
                              (select userID2 as 'userID' from friendship
                              where isConfirmed = true and userID1 = ? union
                              select userID1 as 'userID' from friendship
                              where isConfirmed = true and userID2 = ?)
                              -- chooses the friends of friends
                              and userID in
                              (select userID2 as 'userID' from friendship
                              where isConfirmed = true and userID1
                              in
                              (select userID2 as 'userID' from friendship
                              where isConfirmed = true and userID1 = ? union
                              select userID1 as 'userID' from friendship
                              where isConfirmed = true and userID2 = ?)
                              union
                              select userID1 as 'userID' from friendship
                              where isConfirmed = true and userID2
                              in
                              (select userID2 as 'userID' from friendship
                              where isConfirmed = true and userID1 = ? union
                              select userID1 as 'userID' from friendship
                              where isConfirmed = true and userID2 = ?))))
                              -- friends of friends part ends here
                              union
                              select photoID from photocollectionassignment where collectionID in
                              (select collectionID from photocollection where
                                isVisibleToCircles = 1 and userID in
                              (select userID from circlemembership
                              where circleID in
                              (Select circleID from circlemembership where userID = ?))))
                              ORDER BY time Desc
                              LIMIT 20");
  $statement ->bind_param("iiiiiiiiii",$userID,$userID,$userID,$userID,$userID,$userID,$userID,$userID,$userID,$userID);
  $statement->execute();
  $result = $statement->get_result();
  while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $sortArray[strtotime($row["time"])] = getPhotoWithID($row["photoID"]);
    // $sortArray[strtotime($row["time"])] = new photo($row["photoID"], getUserWithID($row["userID"]), new DateTime($row["time"]), "img/".$row["filename"]);
  }

  krsort($sortArray);
  $slicedArray = array_slice($sortArray,0,20);
  foreach ($slicedArray as $key => $value) {
    $mainArray[]=$value;
  }
  return $mainArray;
}

/*
 * Returns the photo object with the specified ID from the database.
 */
function getPhotoWithID(int $photoID) {
  $db = new db();
  $db->connect();
  $statement = $db -> prepare("SELECT * FROM photo WHERE photoID = ? AND isArchived = 0");
  $statement->bind_param("i", $photoID);
  $statement->execute();
  $result = $statement->get_result();

  $row = $result->fetch_array(MYSQLI_ASSOC);
  return new photo($row["photoID"], getUserWithID($row["userID"]) , new DateTime($row["time"]), "img/".$row["filename"] );
}

/*
 * Picks a specified number of photos at random from a user's uploaded photos.
 */
function getRandomPhotosFromUser(user $user, int $numberOfPhotos): array {
  $userID =$user->getUserID();
  $db = new db();
  $db->connect();
  if (isset($numberOfPhotos)){
    $statement = $db -> prepare("SELECT photoID FROM photo WHERE userID = ? AND isArchived=0 ORDER BY RAND() LIMIT ?");
    $statement->bind_param("ii", $userID, $numberOfPhotos);
  } else {
    $statement = $db -> prepare("SELECT photoID FROM photo WHERE userID = ? AND isArchived=0 ORDER BY RAND()");
    $statement->bind_param("i", $userID);
  }
  $statement->execute();
  $result = $statement->get_result();

  $photosArray = array();
  while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $photosArray[$row["photoID"]] = getPhotoWithID($row["photoID"]);
  }
  return $photosArray;
}

/*
 * Returns an array of users who are friends of friends (who are not already friends) with the given user.
 * Optionally filters the list based on a search string.
 */
function getFriendsOfFriendsOfUser(user $user): array {
  $userID =$user->getUserID();
  $db = new db();
  $db->connect();

  $statement = $db -> prepare("select userID from user where userID != ? and
                              userID not in
                              -- makes sure direct friends of ther user is not selected
                              (select userID2 as 'userID' from friendship
                              where isConfirmed = true and userID1 = ? union
                              select userID1 as 'userID' from friendship
                              where isConfirmed = true and userID2 = ?)
                              -- chooses the friends of friends
                              and userID in
                              (select userID2 as 'userID' from friendship
                              where isConfirmed = true and userID1
                              in
                              (select userID2 as 'userID' from friendship
                              where isConfirmed = true and userID1 = ? union
                              select userID1 as 'userID' from friendship
                              where isConfirmed = true and userID2 = ?)
                              union
                              select userID1 as 'userID' from friendship
                              where isConfirmed = true and userID2
                              in
                              (select userID2 as 'userID' from friendship
                              where isConfirmed = true and userID1 = ? union
                              select userID1 as 'userID' from friendship
                              where isConfirmed = true and userID2 = ?))");
  $statement->bind_param("iiiiiii", $userID, $userID, $userID, $userID, $userID, $userID,$userID);
  $statement->execute();
  $result = $statement->get_result();

  $friendsArray = array();
  while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $friendsArray[$row["userID"]] = getUserWithID($row["userID"]);
  }

  return $friendsArray;
}
/*Returns an array of IDs of all the users who are friends of friends (who are not already friends) with the given user.*/
function getFriendsOfFriendsOfUserAsIDs(int $userID): array {
    $db = new db();
    $db->connect();

    $statement = $db -> prepare("select userID from user where userID != ? and
                              userID not in
                              -- makes sure direct friends of ther user is not selected
                              (select userID2 as 'userID' from friendship
                              where isConfirmed = true and userID1 = ? union
                              select userID1 as 'userID' from friendship
                              where isConfirmed = true and userID2 = ?)
                              -- chooses the friends of friends
                              and userID in
                              (select userID2 as 'userID' from friendship
                              where isConfirmed = true and userID1
                              in
                              (select userID2 as 'userID' from friendship
                              where isConfirmed = true and userID1 = ? union
                              select userID1 as 'userID' from friendship
                              where isConfirmed = true and userID2 = ?)
                              union
                              select userID1 as 'userID' from friendship
                              where isConfirmed = true and userID2
                              in
                              (select userID2 as 'userID' from friendship
                              where isConfirmed = true and userID1 = ? union
                              select userID1 as 'userID' from friendship
                              where isConfirmed = true and userID2 = ?))");
    $statement->bind_param("iiiiiii", $userID, $userID, $userID, $userID, $userID, $userID,$userID);
    $statement->execute();
    $result = $statement->get_result();

    $friendsArray = array();
    while($row = $result->fetch_array(MYSQLI_ASSOC)){
        $friendsArray[] = $row["userID"];
    }
    return $friendsArray;
}


/*
 * Returns an array of users who are friends with the given user.
 * Optionally filters the list based on a search string.
 */
function getFriendsOfUser(user $user, string $filter = NULL): array {
  $userID = $user->getUserID();
  $searchTerm = '%'.preg_replace('/\s+/','',$filter).'%';
  $db = new db();
  $db->connect();
  if (is_null($filter)) {
    // If the search parameter is NULL then it returns all the friends of the user
    $statement = $db -> prepare("SELECT userID2 AS 'userID' FROM friendship WHERE isConfirmed = true AND userID1 = ?
                                  UNION
                                  SELECT userID1 AS 'userID' FROM friendship WHERE isConfirmed = true AND userID2 = ?");
    $statement->bind_param("ii", $userID, $userID);
  } else {
    // If a search parameter exists then it looks if that term is contained in either the firstName,
    // lastName or location, it would also select the user whose e-mail is an excact match
    $statement = $db -> prepare(" SELECT userID FROM user WHERE userID IN
                                (SELECT userID2 AS 'userID' FROM friendship WHERE isConfirmed = TRUE AND userID1 = ? union
                                  SELECT userID1 AS 'userID' FROM friendship WHERE isConfirmed = true AND userID2 = ? )
                                  AND firstName LIKE ?
                                  OR lastName LIKE ?
                                  OR CONCAT_WS('', firstName, lastName) LIKE ?
                                  OR CONCAT_WS('', lastName, firstName) LIKE ?
                                  OR email = ?
                                  OR location LIKE ? ");
    $statement->bind_param("iissssss",$userID,$userID,$searchTerm,$searchTerm,$searchTerm,$searchTerm,$filter,$searchTerm);
  }
  $statement->execute();
  $result = $statement->get_result();

  $friendsArray = array();
  while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $friendsArray[$row["userID"]] = getUserWithID($row["userID"]);
  }

  return $friendsArray;
}

/*
 * Returns an array of users who match the given search string.
 */
function getUsers(string $filter): array {
  $db = new db();
  $db->connect();
  $searchTerm = '%'.preg_replace('/\s+/','',$filter).'%';
  $statement = $db -> prepare(" SELECT userID FROM user WHERE
                                  firstName LIKE ?
                                OR lastName LIKE ?
                                OR CONCAT_WS('', firstName, lastName) LIKE ?
                                OR CONCAT_WS('', lastName, firstName) LIKE ?
                                OR email = ?
                                OR location LIKE ? ");
  $statement->bind_param("ssssss",$searchTerm,$searchTerm,$searchTerm,$searchTerm,$filter,$searchTerm);
  $statement->execute();
  $result = $statement->get_result();

  $usersArray = array();
  while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $usersArray[$row["userID"]] = getUserWithID($row["userID"]);
  }

  return $usersArray;
}

function areUsersFriendsOfFriends(int $userID1, int $userID2){
    //Get list of all the userIDs of friends of friends of user1
    $friendsOfFriends = getFriendsOfFriendsOfUserAsIDs($userID1);
    //See if userID2 is in the array
    return in_array($userID2, $friendsOfFriends);
}
function areUsersFriendsWithID(int $userID1, int $userID2) : bool{
    $db = new db();
    $db->connect();
    $statement = $db -> prepare(" SELECT (CASE
                                WHEN (userID1 = ? and userID2 = ?) THEN 1
                                WHEN (userID2 = ? and userID1 = ?) THEN 1
                                ELSE 0 END) as 'result', isConfirmed
                                FROM friendship
                                WHERE isConfirmed = 1");
    $statement->bind_param("iiii", $userID1, $userID2, $userID1, $userID2);
    $statement->execute();
    $result = $statement->get_result();
    // Check result. 1 means they are friends.
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        if ($row["result"] == 1) { return true; }
    }
    return false;
}

/*
 * Returns true if the users are friends, false otherwise.
 */
function areUsersFriends(user $user1, user $user2): bool {
  $userID1 = $user1->getUserID();
  $userID2 = $user2->getUserID();
    return areUsersFriendsWithID($userID1, $userID2);
}

/*Decide if the blog should be displayed when visiting a profile, user is the user who's profile is shown*/
function displayBlog(user $user, bool $friends, bool $friendsOfFriends): bool{
    //If user has set to visible to all
    if($user->blogVisibility < 1){
        return true;
    }
    //If user has set to friends of friends, and you are a friend of a friend
    else if( ($user->blogVisibility < 2) && $friendsOfFriends){
        return true;
    }
    //If user has set to friends and you are a friend
    else if( ($user->blogVisibility < 3) && $friends){
        return true;
    }
    //If user has set to only themselves, and you are that user
    else if($user->id == $_SESSION['userID']){ //// ??????
        return true;
    }
    //Otherwise it is not visible
    else{
        return false;
    }
}

/*Decide if the blog should be displayed when visiting a profile, user is the user who's profile is shown*/
function displayInfo(user $user, bool $friends, bool $friendsOfFriends): bool{
    if($user->infoVisibility < 1){
        return true;
    }
    else if( ($user->infoVisibility < 2) && $friendsOfFriends){
        return true;
    }
    else if( ($user->infoVisibility < 3) && $friends){
        return true;
    }
    else if($user->id == $_SESSION['userID']){
        return true;
    }
    else{
        return false;
    }
}

/*
 * Returns true if there is a pending friend request which was initiated by $sender, false otherwise.
 */
function isFriendRequestPending(user $sender, user $receiver) {
  $db = new db();
  $db->connect();
  $senderID = $sender->getUserID();
  $receiverID = $receiver->getUserID();
  $statement = $db -> prepare("SELECT * FROM friendship WHERE userID1 = ? AND userID2 = ? AND isConfirmed = 0");
  $statement->bind_param("ii", $senderID, $receiverID);
  $statement->execute();
  $result = $statement->get_result();

  return ($result->num_rows == 1);
}


/*
 * Returns true if there is a photoAlready Saved with the specified name.
 */
function isPhotoNameExist($photoName) : bool {
  $db = new db();
  $db->connect();
  $statement = $db -> prepare("SELECT photoID FROM photo WHERE filename =  ?  ");
  $statement->bind_param("s", $photoName);
  $statement->execute();
  $result = $statement->get_result();

  return ($result->num_rows == 1);
}
/*
 * Return a collection object for a given collection id
 */
//FARSE
function getPhotoCollectionFromID(int $collectionID){
    $db = new db();
    $db->connect();
    $statement = $db -> prepare("SELECT * FROM photocollection WHERE collectionID = ? LIMIT 1");
    $statement->bind_param("i", $collectionID);
    $statement->execute();
    $result = $statement->get_result();
    $row = $result->fetch_array(MYSQLI_ASSOC);
    return new Collection($row["collectionID"], getUserWithID($row["userID"]), new DateTime("2017-04-20 14:44"),$row["name"]);
}

//TODO add visibility for this, also do collections need an actual user object, or would ID suffice? does this create overhead?
function newCollection($row): Collection{
    return new Collection($row["collectionID"], getUserWithID($row["userID"]), new DateTime("2017-04-20 14:44"), $row["name"]);
};
/*
 * Returns an array of a particular user's photo collections.
 */
function getPhotoCollectionsByUser(user $user): array {
  $db = new db();
  $db->connect();

  $userID = $user->getUserID();

  $statement = $db -> prepare("SELECT collectionID, name FROM photocollection WHERE userID = ?");
  $statement->bind_param("i", $userID);
  $statement->execute();
  $result = $statement->get_result();
  $photocollectionsArray = array();
  while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $photocollectionsArray[$row["collectionID"]] = new Collection($row["collectionID"],$user, new DateTime("2017-04-20 14:44"),$row["name"]);
  }
  return $photocollectionsArray;
}

/*
 * Returns the blog post with the specified ID.
 */
function getBlogPostWithID($postID) {
  $db = new db();
  $db->connect();
  $statement = $db -> prepare("SELECT * FROM blogpost WHERE postID = ?");
  $statement->bind_param("i", $postID);
  $statement->execute();
  $result = $statement->get_result();
  $row = $result->fetch_array(MYSQLI_ASSOC);
  return createBlogObject($row);
}

/*
 * Returns an array containing the friend requests for the current user.
 * Key is the ID of the user who sent the request, value is a DateTime object representing the time the request was sent.
 */
function getFriendRequests() {

  // Get all non-confirmed friendships that this user is on the receiving end of
  $db = new db();
  $db->connect();
  $stmt = $db->prepare("SELECT * FROM friendship WHERE userID2 = ? AND isConfirmed = 0");
  $stmt->bind_param("i", getUser()->id);
  $stmt->execute();
  $result = $stmt->get_result();

  // Loop through the result and return an array of users
  $users = [];
  while($row = $result->fetch_array(MYSQLI_ASSOC)) {
    // Get this user's ID
    $userID = $row["userID1"];
    // Get the time the request was made
    $time = new DateTime($row["time"]);
    // Add it to the array
    $users[$userID] = $time;
  }
  return $users;

}

/*
 * Accepts an existing friend request.
 */
function acceptFriendRequest(int $requestingUserID) {
  $thisUserID = getUserID();
  // Confirm the request
  $db = new db();
  $db->connect();
  $stmt = $db->prepare("UPDATE friendship SET isConfirmed = 1 WHERE userID1 = ? AND userID2 = ?");
  $stmt->bind_param("ii", $requestingUserID, $thisUserID);
  $stmt->execute();

}

/*
 * Declines an existing friend request.
 */
function declineFriendRequest(int $requestingUserID) {
  $thisUserID = getUserID();
  // Delete the request
  $db = new db();
  $db->connect();
  $stmt = $db->prepare("DELETE FROM friendship WHERE userID1 = ? AND userID2 = ?");
  $stmt->bind_param("ii", $requestingUserID, $thisUserID);
  $stmt->execute();
}

/*
 * Requests a friendship between the current user and the user with the specified ID.
 */
function requestFriendship(int $userID) {
  $thisUserID = getUserID();
  $db = new db();
  $db->connect();
  $stmt = $db->prepare("INSERT INTO friendship (userID1, userID2, isConfirmed, time) VALUES (?, ?, 0, NOW())");
  $stmt->bind_param("ii", $thisUserID, $userID);
  $stmt->execute();
}

/*
 * Removes the friendship between the current user and the user with the specified ID.
 */
function deleteFriendship(int $userID) {
  $thisUserID = getUserID();
  $db = new db();
  $db->connect();
  // Delete one way (ignore errors)
  $stmt = $db->prepare("DELETE FROM friendship WHERE userID1 = ? AND userID2 = ?");
  $stmt->bind_param("ii", $thisUserID, $userID);
  $stmt->execute();
  // Delete the other way (ignore errors)
  $stmt = $db->prepare("DELETE FROM friendship WHERE userID1 = ? AND userID2 = ?");
  $stmt->bind_param("ii", $userID, $thisUserID);
  $stmt->execute();
}

  /*
   * Adds a blogpost to the database where the user is the currently logged-in user.
   */
  function addNewBlogPost($blogTitle,$blogpost,$dateString){
    $thisUserID = getUserID();
    $db = new db();
    $db->connect();
    $stmt = $db->prepare("INSERT INTO blogpost (userID,post,time,headline) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss",$thisUserID,$blogpost,$dateString,$blogTitle);
    $stmt->execute();
  }

  /*
   * Adds a photo to the database where the user is the currently logged-in user.
   */
  function addPhotoToDB($photoName,$dateString){
    $thisUserID = getUserID();
    $db = new db();
    $db->connect();
    $stmt = $db->prepare("INSERT INTO photo (userID,filename,time) VALUES (?, ?, ?)");
    $stmt->bind_param("iss",$thisUserID,$photoName,$dateString);
    $stmt->execute();
  }

  /*
   * Marks the photo with the given ID in the database as Archieved.
   */
  function deletePhotoWithID($photoID) {
    $db = new db();
    $db->connect();
    $statement = $db -> prepare("UPDATE photo SET isArchived = 1 WHERE photoID = ? ");
    $statement->bind_param("i", $photoID);
    $statement->execute();
  }

  /*
   * Adds a new photo collection to the database where the user is the currently logged-in user.
   */
  function addNewPhotoCollection($name,$FOF_visibility,$circle_visibility){

    $thisUserID = getUserID();
    $FOF_vis=($FOF_visibility) ? 1 : 0;
    $cicle_vis=($circle_visibility) ? 1 : 0;
    $db = new db();
    $db->connect();
    $stmt = $db->prepare("INSERT INTO photocollection (userID,name,isVisibleToFriendsOfFriends,isVisibleToCircles) VALUES (?, ?, ?,?)");
    $stmt->bind_param("isii",$thisUserID,$name,$FOF_visibility,$circle_visibility);
    $stmt->execute();
  }

  /*
   * Updates the profile picture ID of the currently logged-in user in the database with the given one.
   */
  function setProfilePhoto($photoID) {
    $userID=getUserID();
    $db = new db();
    $db->connect();
    $statement = $db -> prepare("UPDATE user SET photoID = ? WHERE userID = ? ");
    $statement->bind_param("ii", $photoID,$userID);
    $statement->execute();
  }

  function createUserObject($row){
      return new user($row["userID"],$row["firstName"],$row["lastName"],"img/" . $row["filename"]  ,new DateTime($row["date"]),$row["location"],$row["email"],$row["blogVisibility"],$row["infoVisibility"]);
  }
?>
