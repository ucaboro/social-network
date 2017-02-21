<?php

/*
 * Returns the ID of the currently logged-in user, or NULL if no user is logged in.
 */
function getUserID() {
  //TODO: Not yet implemented.
  return 0;
}

/*
 * Returns an array of the circles that a user is a member of. Key is circle ID, value is circle object.
 */
function getCirclesForUser($userID) {
  //TODO: Not yet implemented.
  return array(new circle(0, "Friends", "blue", NULL),
                new circle(0, "Work", "blue", NULL),
                new circle(0, "Students", "blue", NULL),
                new circle(0, "Hackers", "blue", NULL));
}


?>
