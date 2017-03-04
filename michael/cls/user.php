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
   * The date of birth of this user.
   */
  public $dateOfBirth;

  /*
   * The user's location.
   */
  public $location;

  /*
   * An array of users who are friends with this user. Key is user ID, value is user object.
   */
  private $friends;

  /*
   * Constructor which initialises the object and populates all fields.
   */
  public function __construct(int $id, string $firstName, string $lastName, string $photoSrc, DateTime $dateOfBirth, string $location) {
    $this->id = $id;
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->photoSrc = $photoSrc;
    $this->dateOfBirth = $dateOfBirth;
    $this->location = $location;
  }

  /*
   * Returns the full name of this user as a string.
   */
  public function getFullName() {
    return $this->firstName . " " . $this->lastName;
  }

  /*
   * Returns the URL to the profile of this user.
   */
  public function getUrlToProfile() {
    return "profile.php?u=" . $this->id;
  }

  /*
   * Returns a string containing the current age of the user.
   */
  public function getAge() {
    return $this->dateOfBirth->diff(new DateTime())->format('%y');
  }

  /*
   * Get an array of the friends of this user. Key is user ID, value is user object.
   */
  public function getFriends() {
    //TODO: Not yet implemented.
    // Get the array of friends from the database the first time.
    if (is_null($this->friends)) {
      $user1 = new user(1, "Carrie", "Mathison", "img/profile1.jpg", new DateTime("1982-11-01"), "Pakistan");
      $user2 = new user(2, "Walter", "White", "img/profile2.jpg", new DateTime("1969-06-02"), "London");
      $this->friends = array($user1, $user2);
    }
    // Return the saved array
    return $this->friends;
  }
}

?>