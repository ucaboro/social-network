<?php

/*
 * Represents a single photo.
 */
class photo extends interaction {

  /*
   * The ID assigned to this photo in the database.
   */
  public $id;

  /*
   * The user who uplodaded the photo.
   */
  public $user;

  /*
   * The time at which the photo was uploaded.
   */
  public $time;

  /*
   * The URL at which the photo is stored.
   */
  public $src;

  /*
   * The annotations on this photo.
   */
  private $annotations;

  /*
   * The comments on this photo.
   */
  private $comments;

  /*
   * Constructor which initialises the object and populates all fields.
   */
  public function __construct(int $id, user $user, DateTime $time, string $src) {
    $this->id = $id;
    $this->user = $user;
    $this->time = $time;
    $this->src = $src;
  }

  /*
   * Returns an array of users who have annotated this photo. Key is user ID, value is user object.
   */
  public function getAnnotations(): array {
    // Check if we already got the annotations for this photo
    if (is_null($this->annotations)) {
      // Get the annotations from the database
      $this->annotations =  array();
      $db = new db();
      $db->connect();
      $statement = $db -> prepare("SELECT * FROM photoannotation AS pa LEFT JOIN user AS u ON pa.userID = u.userID WHERE pa.photoID = ?");
      $statement->bind_param("i", $this->id);
      $statement->execute();
      $result = $statement->get_result();

      while($row = $result->fetch_array(MYSQLI_ASSOC)){
        $this->annotations[] = new user($row["userID"], $row["firstName"], $row["lastName"], "", $row["date"], $row["location"], $row["email"], $row["blogVisibility"], $row["infoVisibility"]);
      }
    }
    return $this->annotations;
  }

  /*
   * Returns an array of comments for this photo. Key is comment ID, value is comment object. Comments are in date-descending order.
   */
  public function getComments(): array {
    // Check if we already got the comments for this photo
    if (is_null($this->comments)) {
      // Get the comments from the database

      $this->comments = array();
      $db = new db();
      $db->connect();
      $statement = $db -> prepare("SELECT pc.commentID, pc.userID, pc.time, pc.comment, u.firstName, u.lastName, u.date, u.location, u.email, u.blogVisibility, u.infoVisibility, p.filename FROM photocomment AS pc LEFT JOIN user AS u ON pc.userID = u.userID LEFT JOIN photo AS p ON u.photoID = p.photoID WHERE pc.photoID = ? ORDER BY pc.time DESC");
      $statement->bind_param("i", $this->id);
      $statement->execute();
      $result = $statement->get_result();

      while($row = $result->fetch_array(MYSQLI_ASSOC)){
        $user = new user($row["userID"], $row["firstName"], $row["lastName"], "img/" . $row["filename"], $row["date"], $row["location"], $row["email"], $row["blogVisibility"], $row["infoVisibility"]);
        $this->comments [] = new comment($row["commentID"], $this, $user, new DateTime($row["time"]), $row["comment"]);
      }
    }
    return $this->comments;
  }

  /*
   * Returns the URL to the page which displays this photo.
   */
  public function getPhotoSrc() {
    return $this->src;
  }

  /*
   * Returns the URL to the page which displays this photo.
   */
  public function getURLToPhoto(): string {
    return "photo.php?p=" . $this->id;
  }

}

?>
