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
   *
   */
  public $email;

  /*
   *
   */
  public $blogVisibility;

  /*
   *
   */
  public $infoVisibility;

  /*
   * An array of users who are friends with this user. Key is user ID, value is user object.
   */
  private $friends;

  /*
   * Constructor which initialises the object and populates all fields.
   */
  public function __construct(int $id, string $firstName, string $lastName, $photoSrc, $dateOfBirth, $location, $email, $blogVisibility, $infoVisibility) {
    $this->id = $id;
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->photoSrc = $photoSrc;
    $this->dateOfBirth = $dateOfBirth;
    $this->location = $location;
    $this->email = $email;
    $this->blogVisibility = $blogVisibility;
    $this->infoVisibility = $infoVisibility;
  }

  /*
   * Returns the user ID of this user.
   */
  public function getUserID() {
    return $this->id;
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
    // Get the array of friends from the database the first time.
    if (is_null($this->friends)) {
      $db = new db();
      $db->connect();
      $statement = $db -> prepare("SELECT userID2 AS 'userID' FROM friendship WHERE isConfirmed = true AND userID1 = ?
                                    UNION
                                    SELECT userID1 AS 'userID' FROM friendship WHERE isConfirmed = true AND userID2 = ?");
      $statement->bind_param("ii", $this->id, $this->id);
      $statement->execute();
      $result = $statement->get_result();

      while($row = $result->fetch_array(MYSQLI_ASSOC)){
        $this->friends[] = getUserWithID($row["userID"]);
      }
    }
    // Return the saved array
    return $this->friends;
  }

}

?>
