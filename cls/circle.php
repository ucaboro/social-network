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
  public $users;

  /*
   * Constructor which initialises the object and populates all fields.
   */
  public function __construct($id, $name, $color, $users) {
    $this->id = $id;
    $this->name = $name;
    $this->color = $color;
    $this->users = $users;
  }

  /*
   * Returns the URL to the page for this circle.
   */
  public function getUrlToCircle() {
    return "circle.php?c=" . $this->id;
  }

}

?>
