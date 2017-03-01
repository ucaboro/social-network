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
 * Returns the a user object representing the currently logged-in user, or NULL if no user is logged in.
 */
function getUser() {
  //TODO: Not yet implemented.
  return new user(1, "Steve", "Smith", "img/ex_profile1_thumb.jpg");
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
function getCirclesForUser($user) {
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
function getCircleWithID($id) {
  // TODO: Not yet implemented.
  $user1 = new user(0, "Bob", "Berenstain", "img/ex_profile1_thumb.jpg");
  $user2 = new user(0, "Carrie", "Mathison", "img/profile2.jpg");
  $user3 = new user(0, "Elliot", "Alderson", "img/profile3.jpg");
  return new circle(0, "Family", "blue", array($user1, $user2, $user3, $user1, $user2, $user3));
}

/*
 * Returns an array of message objects consisting of all the messages in a particular circle, in date descending order.
 * Key is message ID, value is message object.
 * $circle: a circle object representing the circle for which the messages should be returned.
 */
function getMessagesInCircle($circle) {
  // TODO: Not yet implemented.
  $user = new user(0, "Bob", "Berenstain", "img/ex_profile1_thumb.jpg");
  $message = new message(0, $circle, $user, "01 Apr 2017 13:42", "It's one thing to question your mind. It's another to question your eyes and ears. But then again, isn't it all the same? Our senses just mediocre inputs for our brain? Sure, we rely on them, trust they accurately portray the real world around us. But what if the haunting truth is they can't? That what we perceive isn't the real world at all, but just our mind's best guess? That all we really have is a garbled reality, a fuzzy picture we will never truly make out?");
  $message2 = new message(0, $circle, $user, "01 Apr 2017 11:59", "Just signed up for Connect. This website is way better than Facebook!");
  return array($message, $message2);
}



?>
