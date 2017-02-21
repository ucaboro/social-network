<?php

/*
 * Represents a single user of the website.
 */
class user {

  /*
   * The ID assigned to this user in the database.
   */
  public $id;

  /*
   * The first name of the user.
   */
  public $firstName;

  /*
   * The last name of the user.
   */
  public $lastName;

  /*
   * The URL to the user's profile picture.
   */
  public $photoSrc;

  /*
   * Constructor which initialises the object and populates all fields.
   */
  public function __construct($id, $firstName, $lastName, $photoSrc) {
    $this->id = $id;
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->photoSrc = $photoSrc;
  }

}

?>
