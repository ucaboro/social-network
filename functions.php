<?php
  require_once "funct.php"; //Later we can make them one file, but i suppose this will do for now
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
      return 1; // Should change this to null or something for final version
  }
}


/*
 * Returns an array of the circles that a user is a member of. Key is circle ID, value is circle object.
 */
function getCirclesForUser(user $user) {
  //TODO: Not yet implemented.



  return array(new circle(0, "Family", "blue"),
                new circle(0, "Friends", "blue"),
                new circle(0, "Work", "blue"),
                new circle(0, "Students", "blue"),
                new circle(0, "Hackers", "blue"));
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

  //foreach ($result as $result => $value) {
    return array (new circle($row["circleID"], $row["circleName"], $row["circleColor"]));

}

/*
 * Returns a circle object for the circle with the given ID.
 * $id: the ID of the circle to return.
 */
function getCircleWithID(int $id) {
  // TODO: Not fully implemented.

  $db = new db();
  $db->connect();
  $stmt = $db->prepare("SELECT circleID, circleName, circleColor FROM circle WHERE circleID = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  $result = $stmt->get_result();
  $row = $result->fetch_array(MYSQLI_ASSOC);


  return new circle($row["circleID"], $row["circleName"], $row["circleColor"]);

}


/*
 * Returns an array of message objects consisting of all the messages in a particular circle, in date descending order.
 * Key is message ID, value is message object.
 * $circle: a circle object representing the circle for which the messages should be returned.
 */
function getMessagesInCircle(circle $circle) {
$circleID = $circle -> id;
//get all messages in a specific circle

  $db = new db();
  $db->connect();
  $stmt = $db->prepare("SELECT messageID, userID, time, message FROM circlemessage WHERE circleID = ?");
  $stmt->bind_param("i", $circleID);
  $stmt->execute();

  $result = $stmt->get_result();

  $user = array();
  $message = array();
  $allmessages = new ArrayObject();
  while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $user = getUserWithID($row["userID"]);
    $message = new message ($row["messageID"], $circle, $user, new DateTime($row["time"]), $row["message"]);
    $allmessages -> append($message);
  }

  return $allmessages;
}

/*
 * Returns a user object for the user with the specified ID.
 */
function getUserWithID(int $id) {

  $db = new db();
  $db->connect();
  $stmt = $db->prepare("SELECT userID, firstName, lastName, photoID, date, location  FROM user WHERE userID = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  $result = $stmt->get_result();
  $row = $result->fetch_array(MYSQLI_ASSOC);

  return new user($row["userID"], $row["firstName"], $row["lastName"], "img/profile" . $row["photoID"] . ".jpg", new DateTime($row["date"]), $row["location"]);

}

/*
 * Gets an array of the photos that the specified user has uploaded. Key is photo ID, value is photo object.
 * Optional limit on the number of items returned. Set $limit to 0 for no limit. Photos are returned in date-descending order.
 */
function getPhotosOwnedByUser(user $user, int $limit = 0): array {
  // TODO: Not yet implemented.
  if ($limit == 0) { $limit = 18; }
  $photo = new photo(0, getUserWithID(1), new DateTime("2017-04-01 11:57"), "img/ex_photo1.jpg");
  $photos = [];
  for ($i = 0; $i < $limit; $i++) {
    $photos[] = $photo;
  }
  return $photos;
}

/*
 * Returns an array of the blog posts that the specified user has posted. Key is photo ID, value is blogPost object.
 * Optional limit on the number of items returned. Set $limit to 0 for no limit. Posts are returned in date-descending order.
 */
function getBlogPostsByUser(user $user, int $limit) {
  // TODO: Not yet implemented.
  $post1 = new blogPost(0, "Welcome to my blog", "Hello, this is my blog. I have written it because I was required to do so. Have a great day.", $user, new DateTime("2017-04-01 09:12"));
  $post2 = new blogPost(0, "A headline for a post on this, my blog.", "Welcome to Fight Club. The first rule of Fight Club is: you do not talk about Fight Club. The second rule of Fight Club is: you DO NOT talk about Fight Club! Third rule of Fight Club: someone yells stop, goes limp, taps out, the fight is over.", $user, new DateTime("2017-04-20 14:44"));
  return array($post1, $post2, $post1, $post2, $post1, $post2);
}

/*
 * Returns an array of feed items represeting the recent activity feed for the currently logged in user.
 * Items are in date-descending order. Values are interaction objects. The last 20 items only are returned.
 */
function getRecentActivityFeed() {
  // TODO: Not yet implemented.
  // Create some dummy objects, this is just to demo the layout
  $user = getUserWithID(1);
  $circle = new circle(0, "Family", "blue");
  $message = new message(0, $circle, $user, new DateTime("01 Apr 2017 13:42"), "It's one thing to question your mind. It's another to question your eyes and ears. But then again, isn't it all the same? Our senses just mediocre inputs for our brain? Sure, we rely on them, trust they accurately portray the real world around us. But what if the haunting truth is they can't? That what we perceive isn't the real world at all, but just our mind's best guess? That all we really have is a garbled reality, a fuzzy picture we will never truly make out?");
  $photo = new photo(0, $user, new DateTime("01 Apr 2017 13:45"), "img/ex_photo1.jpg");
  $message2 = new message(0, $circle, $user, new DateTime("01 Apr 2017 11:59"), "Just signed up for Connect. This website is way better than Facebook!");
  $blogPost = new blogPost(0, "A headline for a post on this, my blog.", "Welcome to Fight Club. The first rule of Fight Club is: you do not talk about Fight Club. The second rule of Fight Club is: you DO NOT talk about Fight Club! Third rule of Fight Club: someone yells stop, goes limp, taps out, the fight is over.", $user, new DateTime("2017-04-20 14:44"));
  return array($message, $blogPost, $photo, $message2);
}

/*
 * Returns the photo object with the specified ID from the database.
 */
function getPhotoWithID(int $photoID) {
  // TODO: Not yet implemented.
  return new photo(0, getUserWithID(1), new DateTime("01 Apr 2017 13:45"), "img/ex_photo1.jpg");
}

/*
 * Picks a specified number of photos at random from a user's uploaded photos.
 */
function getRandomPhotosFromUser(user $user, int $numberOfPhotos): array {
  // TODO: Not yet implemented.
  $photo = new photo(0, getUserWithID(1), new DateTime("2017-04-01 11:57"), "img/ex_photo1.jpg");
  $toReturn = [];
  for ($i=0; $i < $numberOfPhotos; $i++) {
    $toReturn[] = $photo;
  }
  return $toReturn;
}

/*
 * Returns an array of users who are friends with the given user.
 * Optionally filters the list based on a search string.
 */
function getFriendsOfUser(user $user, string $filter = NULL): array {
  // TODO: Not yet implemented.
  if (is_null($filter)) {
    return array(getUserWithID(1), getUserWithID(2), getUserWithID(3));
  } else {
    return array(getUserWithID(1));
  }
}

/*
 * Returns an array of users who match the given search string.
 */
function getUsers(string $filter): array {
  // TODO: Not yet implemented.
  return array(getUserWithID(0), getUserWithID(1), getUserWithID(2));
}

/*
 * Returns true if the users are friends, false otherwise.
 */
function areUsersFriends(user $user1, user $user2): bool {
  // TODO: Not yet implemented.
  return false;
}

/*
 * Returns an array of a particular user's photo collections.
 */
function getPhotoCollectionsByUser(user $user): array {
  // TODO: Not yet implemented.
  $collection1 = new collection(1, $user, new DateTime("2017-03-01 08:53"), "Photos of trees");
  $collection2 = new collection(1, $user, new DateTime("2017-03-01 08:57"), "My favourite tree photos");
  return array($collection1, $collection2, $collection1, $collection2, $collection1, $collection2);
}

/*
 * Returns the blog post with the specified ID.
 */
function getBlogPostWithID($id) {
  // TODO: Not yet implemented.
  $user = getUserWithID(1);
  return new blogPost(0, "A headline for a post on this, my blog.", "Welcome to Fight Club. The first rule of Fight Club is: you do not talk about Fight Club. The second rule of Fight Club is: you DO NOT talk about Fight Club! Third rule of Fight Club: someone yells stop, goes limp, taps out, the fight is over.", $user, new DateTime("2017-04-20 14:44"));
}

/*
 * Returns an array containing the friend requests for the current user.
 * Key is the ID of the user who sent the request, value is a DateTime object representing the time the request was sent.
 */
function getFriendRequests() {
  // // TODO: Not yet implemented.
  // $requests = [];
  // $requests[1] = new DateTime("01 Apr 2017 13:42");
  // $requests[2] = new DateTime("01 Apr 2017 13:42");
  // return $requests;

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
    //TODO: add a date column to the friendship table
    $time = new DateTime("01 Apr 2017 13:42");
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
  $stmt = $db->prepare("INSERT INTO friendship (userID1, userID2, isConfirmed) VALUES (?, ?, 0)");
  $stmt->bind_param("ii", $thisUserID, $userID);
  $stmt->execute();
}


?>
