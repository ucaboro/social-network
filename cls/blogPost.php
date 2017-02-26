<?php

/*
 * Represents a single blog post.
 */
class blogPost extends interaction {

  /*
   * The ID assigned to this blog post in the database.
   */
  public $id;

  /*
   * The headline of the blog post.
   */
  public $headline;

  /*
   * The body of the blog post.
   */
  public $body;

  /*
   * The user who authored the blog post.
   */
  public $user;

  /*
   * The time at which the blog post was posted.
   */
  public $time;

  /*
   * Constructor which initialises the object and populates all fields.
   */
  public function __construct($id, $headline, $body, $user, $time) {
    $this->id = $id;
    $this->headline = $headline;
    $this->body = $body;
    $this->user = $user;
    $this->time = $time;
  }

  public function getURLToBlog() {
    return "blog.php?u=" . $this->user->id;
  }

}

?>
