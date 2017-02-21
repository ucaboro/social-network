<?php

/*
 * Represents a single message in a circle.
 */
class message {

  /*
   * The ID assigned to this message in the database.
   */
  public $id;

  /*
   * The circle in which this message was posted.
   */
  public $circle;

  /*
   * The user who posted the message.
   */
  public $user;

  /*
   * The text which makes up this message.
   */
  public $text;

  /*
   * Constructor which initialises the object and populates all fields.
   */
  public function __construct($id, $circle, $user, $text) {
    $this->id = $id;
    $this->circle = $circle;
    $this->user = $user;
    $this->text = $text;
  }

}

?>
