<?php

/*
 * Returns the a user object representing the currently logged-in user, or NULL if no user is logged in.
 */
function getUser() {
  return getUserWithID(0);
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
 * Returns a circle object for the circle with the given ID.
 * $id: the ID of the circle to return.
 */
function getCircleWithID(int $id) {
  // TODO: Not yet implemented.
  $user1 = new user(0, "Bob", "Berenstain", "img/ex_profile1_thumb.jpg", new DateTime("1980-06-02"), "London");
  $user2 = new user(0, "Carrie", "Mathison", "img/profile2.jpg", new DateTime("1982-11-01"), "Pakistan");
  $user3 = new user(0, "Elliot", "Alderson", "img/profile3.jpg", new DateTime("1985-04-17"), "New York");
  return new circle(0, "Family", "blue", array($user1, $user2, $user3, $user1, $user2, $user3));
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
      return new user(2, "Elliot", "Alderson", "img/profile3.jpg", new DateTime("1985-04-17"), "New York");
      break;
    case 1:
      return new user(1, "Carrie", "Mathison", "img/profile2.jpg", new DateTime("1982-11-01"), "Pakistan");
      break;
    case 2:
    return new user(0, "Bob", "Berenstain", "img/ex_profile1_thumb.jpg", new DateTime("1980-06-02"), "London");
    default:
      break;
  }

}

/*
 * Gets an array of the photos that the specified user has uploaded. Key is photo ID, value is photo object.
 */
function getPhotosOwnedByUser(user $user): array {
  // TODO: Not yet implemented.
  $photo = new photo(0, getUserWithID(1), new DateTime("2017-04-01 11:57"), "img/ex_photo1.jpg");
  return array($photo, $photo, $photo, $photo, $photo, $photo, $photo);
}

/*
 * Returns an array of the photos that the specified user has uploaded. Key is photo ID, value is relative URL to photo as a string.
 */
function getBlogPostsByUser(user $user) {
  // TODO: Not yet implemented.
  $post1 = new blogPost(0, "Welcome to my blog", "Hello, this is my blog. I have written it because I was required to do so. Have a great day.", $user, new DateTime("2017-04-01 09:12"));
  $post2 = new blogPost(0, "A headline for a post on this, my blog.", "Welcome to Fight Club. The first rule of Fight Club is: you do not talk about Fight Club. The second rule of Fight Club is: you DO NOT talk about Fight Club! Third rule of Fight Club: someone yells stop, goes limp, taps out, the fight is over.", $user, new DateTime("2017-04-20 14:44"));
  return array($post1, $post2);
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


?>
