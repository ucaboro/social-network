<?php

/*
 * Represents a single circle (group of people).
 */
class circle {

  /*
   * The ID assigned to this circle in the database.
   */
  public $id;

  /*
   * The name of this circle.
   */
  public $name;

  /*
   * The color chosen for this circle.
   */
  public $color;

  /*
   * An array of the users who are in the circle. Key is user ID, value is user object.
   */
  private $users;

  /*
   * Constructor which initialises the object and populates all fields.
   */
  public function __construct($id, $name, $color) {
    $this->id = $id;
    $this->name = $name;
    $this->color = $color;
  }

  /*
   * Returns the URL to the page for this circle.
   */
  public function getUrlToCircle() {
    return "circle.php?c=" . $this->id;
  }

  /*
   * Returns an array of the users who are in the circle. Key is user ID, value is user object.
   */
  public function getUsers() {
    // Check if we already got the users for this circle
    if (is_null($this->users)) {
      // Get the annotations from the database
      // TODO: Not yet implemented.
      $this->users = array(getUserWithID(0), getUserWithID(1), getUserWithID(2), getUserWithID(0), getUserWithID(1), getUserWithID(2));
    }
    return $this->users;
  }

}

?>
