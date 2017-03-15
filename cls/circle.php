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
   * Returns the user ID of this circle.
   */
  public function getCircleID() {
    return $this->id;
  }

  /*
   * Returns the URL to the page for this circle.
   */
  public function getUrlToCircle() {
    return "circle.php?c=" . $this->id;
  }

  /*
   * Returns an array of the users who are in the circle. Key is circle ID, value is user object.
   */
  public function getUsers() {
    // Check if we already got the users for this circle
    if (is_null($this->users)) {
      // Get the annotations from the database

      $db = new db();
      $db->connect();
      $stmt = $db->prepare
      ("SELECT u.userID, firstName, lastName, p.filename, date, location, email, blogVisibility, infoVisibility
        FROM user u
        JOIN circlemembership c ON c.userID = u.userID
        JOIN photo p ON u.photoID = p.photoID
        WHERE circleID = ?;");
      $stmt->bind_param("i", $this ->id);
      $stmt->execute();
      $result = $stmt->get_result();
      $this ->users = array();
      while ($row = $result->fetch_array(MYSQLI_ASSOC)) {

      $this ->users[] = new user($row["userID"], $row["firstName"], $row["lastName"], "img/" . $row["filename"], new DateTime($row["date"]), $row["location"], $row["email"], $row["blogVisibility"], $row["infoVisibility"] );

    }
    return $this ->users;
  }
}

}

?>
