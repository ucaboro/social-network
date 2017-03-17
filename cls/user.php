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
  private $email;

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
   * An array of all the interests the user has
   */
  private $interests;

  /*
   * An array of the ids of all the interests the user has
   */
  private $interestIDs;

  /*
  * An array of the ids of all the interests the user has
  */
  private $interestNames;



  /*
   * Constructor which initialises the object and populates all fields.
   */
  public function __construct(int $id, string $firstName, string $lastName, $photoSrc, $dateOfBirth, $location, $blogVisibility, $infoVisibility) {
    $this->id = $id;
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->photoSrc = $photoSrc;
    $this->dateOfBirth = $dateOfBirth;
    $this->location = $location;
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
   * Returns a email address of the user.
   */
  public function getEmail() {
    // Get the array of friends from the database the first time.
    if (is_null($this->email)) {
      $db = new db();
      $db->connect();
      $statement = $db -> prepare("SELECT email FROM useremail WHERE userID = ?");
      $statement->bind_param("i", $this->id);
      $statement->execute();
      $result = $statement->get_result();

      $row = $result->fetch_array(MYSQLI_ASSOC);
      $this->email = $row["email"];
    }
    // Return the saved email
    return $this->email;
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

//  public function getInterestIDs(){
//      // Get the array of friends from the database the first time.
//      if (is_null($this->interests)) {
//          //$interestID = 0;
//          $db = new db();
//          $db->connect();
//          $statement = $db -> prepare("SELECT interestID FROM interestsassignment WHERE userID1 = ?");
//          $statement->bind_param("i", $this->id);
//          $statement->execute();
//          $result = $statement->get_result();
//          $this->interestIDs = $result->fetch_array(MYSQLI_NUM);
//
////          /* bind result variables */
////          $statement->bind_result($interestID);
////          /* fetch values */
////          while($statement->fetch()) {
////            $this->interestIDs[] = $interestID;
////          }
////          /* close statement */
////          $statement->close();
//      }
//      else{
//        return $this->interestIDs;
//      }
//  }

  public function getInterests(): array{
    //Get array of interests from the database for the first time
      if (is_null($this->interests)){
          $db = new db();
          $db->connect();
          $statement = $db -> prepare("SELECT * FROM interests,interestsassignment
                                        WHERE interests.interestID = interestsassignment.interestID
                                        AND userID = ?");
          $statement->bind_param("i", $this->id);
          $statement->execute();
          $result = $statement->get_result();
          check_query($result, $db);
          while($row = $result->fetch_array(MYSQLI_ASSOC)){
              $this->interests[] = new interest($row["interestID"], $row["name"]);
              $this->interestNames[] = $row["name"];
              $this->interestIDs[] = $row["interestID"];
          }
          //If the user currently has no interests
          if(is_null($this->interests)){
              $this->interests = array();
              $this->interestNames = array();
              $this->interestIDs = array();
              return $this->interests = array();
          }
          else{
              return $this->interests;

          }
      }
      else{
        return $this->interests;
      }
  }

  public function getInterestNames(): array{
      if (is_null($this->interestNames)){
          $dummy = $this->getInterests();
          return $this->interestNames;
      }
      else{
          return $this->interestNames;
      }
  }

  public function getInterestIDs(): array{
      if (is_null($this->interestIDs)){
          $dummy = $this->getInterests();
          return $this->interestIDs;
      }
      else{
          return $this->interestIDs;
      }
  }

}

?>
