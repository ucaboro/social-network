<?php

/*
 * Represents a single comment on a photo.
 */
class comment extends interaction {

  /*
   * The ID assigned to this comment in the database.
   */
  public $id;

  /*
   * The photo on which this comment was left.
   */
  public $photo;

  /*
   * The user who left the comment.
   */
  public $user;

  /*
   * The time at which the comment was left.
   */
  public $time;

  /*
   * The text which makes up this comment.
   */
  public $text;

  /*
   * Constructor which initialises the object and populates all fields.
   */
  public function __construct($id, $photo, $user, $time, $text) {
    $this->id = $id;
    $this->photo = $photo;
    $this->user = $user;
    $this->time = $time;
    $this->text = $text;
  }

}

?>
