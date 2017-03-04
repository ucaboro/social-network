<?php

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
 * Returns the a user object representing the currently logged-in user, or NULL if no user is logged in.
 */
function getUser() {
  return getUserWithID(0);

}
/*
 * Returns the a user ID representing the currently logged-in user, or NULL if no user is logged in.
 */
function getUserID() {
  //TODO: to modify according to log in and session
  return new user(1, "Steve", "Smith", "img/ex_profile1_thumb.jpg");
}


/*
 * Returns an array of the circles that a user is a member of. Key is circle ID, value is circle object.
 */
function getCirclesForUser(user $user) {
  //TODO: Not yet implemented.



  return array(new circle(0, "Family", "blue", NULL),
                new circle(0, "Friends", "blue", NULL),
                new circle(0, "Work", "blue", NULL),
                new circle(0, "Students", "blue", NULL),
                new circle(0, "Hackers", "blue", NULL));
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
    return array (new circle($row["circleID"], $row["circleName"], $row["circleColor"], NULL));


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

  $user1 = getUserWithID(0);
  $user2 = getUserWithID(1);
  $user3 = getUserWithID(2);
  return new circle($row["circleID"], $row["circleName"], $row["circleColor"], array($user1, $user2, $user3, $user1, $user2, $user3));

}

/*
 * Returns an array of message objects consisting of all the messages in a particular circle, in date descending order.
 * Key is message ID, value is message object.
 * $circle: a circle object representing the circle for which the messages should be returned.
 */
function getMessagesInCircle(circle $circle) {
  // TODO: Not yet implemented.
  $user = getUserWithID(0);
  $message = new message(0, $circle, $user, new DateTime("01 Apr 2017 13:42"), "It's one thing to question your mind. It's another to question your eyes and ears. But then again, isn't it all the same? Our senses just mediocre inputs for our brain? Sure, we rely on them, trust they accurately portray the real world around us. But what if the haunting truth is they can't? That what we perceive isn't the real world at all, but just our mind's best guess? That all we really have is a garbled reality, a fuzzy picture we will never truly make out?");
  $message2 = new message(0, $circle, $user, new DateTime("01 Apr 2017 11:59"), "Just signed up for Connect. This website is way better than Facebook!");
  return array($message, $message2);
}

/*
 * Returns a user object for the user with the specified ID.
 */
function getUserWithID(int $id) {
  // TODO: Not yet implemented.
  // Return an example user
  switch ($id) {
    case 0:
      return new user(0, "Elliot", "Alderson", "img/profile0.jpg", new DateTime("1985-04-17"), "New York");
      break;
    case 1:
      return new user(1, "Carrie", "Mathison", "img/profile1.jpg", new DateTime("1982-11-01"), "Pakistan");
      break;
    case 2:
    return new user(2, "Walter", "White", "img/profile2.jpg", new DateTime("1969-06-02"), "London");
    default:
      break;
  }

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
  $circle = new circle(0, "Family", "blue", array($user));
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
    return array(getUserWithID(0), getUserWithID(1), getUserWithID(2));
  } else {
    return array(getUserWithID(0));
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



?>
