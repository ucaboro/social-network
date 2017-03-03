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
      // TODO: Not yet implemented.
      $this->annotations = array(getUserWithID(1), getUserWithID(2));
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
      // TODO: Not yet implemented.
      $comment1 = new comment(0, $this, getUserWithID(2), new DateTime("2017-04-01 11:57"), "Great photo, really nice.");
      $comment2 = new comment(0, $this, getUserWithID(0), new DateTime("2017-04-01 11:58"), "Please upload more! I love your photos!");
      $comment3 = new comment(0, $this, getUserWithID(1), new DateTime("2017-04-01 13:00"), "I hate this photo. It's the worst photo I've ever seen. Please leave this website and never return.");
      $this->comments = array($comment3, $comment2, $comment1);
    }
    return $this->comments;
  }

  /*
   * Returns the URL to the page which displays this photo.
   */
  public function getURLToPhoto(): string {
    return "photo.php?p=" . $this->id;
  }

}

?>
