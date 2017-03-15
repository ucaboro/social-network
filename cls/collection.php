<?php

/*
 * Represents a single photo collection.
 */
class collection {

  /*
   * The ID assigned to this photo collection in the database.
   */
  public $id;

  /*
   * The name of this photo collection.
   */
  public $name;

  /*
   * The user who created the photo collection.
   */
  public $user;

  /*
   * An array of the photos in this collection.
   */
  private $photos;

  /*
   * Status as whether the collection is visible to Circles.
   */
  private $isVisibleToCircles;

  /*
   * Status as whether the collection is visible to friends of friends.
   */
  private $isVisibleToFriendsOfFriends;

  /*
   * Constructor which initialises the object and populates all fields.
   */
  public function __construct(int $id, user $user, string $name) {
    $this->id = $id;
    $this->user = $user;
    $this->name = $name;
  }

  /*
   * Returns an array of the photos in this collection. Key is photo ID, value is photo object.
   */
  public function getPhotos(): array {
    // Check if we already got the photos for this collection
    if (is_null($this->photos)) {
      // Get the annotations from the database

      $this->photos = array();
      $db = new db();
      $db->connect();
      $statement = $db -> prepare("SELECT photo.photoID as photoID FROM photocollectionassignment,photo WHERE photo.photoID=photocollectionassignment.photoID AND isArchived = 0 AND  collectionID =?");
      $statement->bind_param("i", $this->id);
      $statement->execute();
      $result = $statement->get_result();

      while($row = $result->fetch_array(MYSQLI_ASSOC)){
        $this->photos [] = getPhotoWithID($row["photoID"]);
      }
    }
    return $this->photos;
  }

  /*
   * Returns an boolean indicating whether it is visible to the circles of the user.
   */
  public function isVisibleToCircles(): bool {
    // Check if we already got the photos for this collection
    if (is_null($this->isVisibleToCircles)) {
      // Get the annotations from the database
      $db = new db();
      $db->connect();
      $statement = $db -> prepare("SELECT photo.photoID as photoID FROM photocollectionassignment,photo WHERE photo.photoID=photocollectionassignment.photoID AND isArchived = 0 AND  collectionID =?");
      $statement->bind_param("i", $this->id);
      $statement->execute();
      $result = $statement->get_result();
    }
    return $this->isVisibleToCircles;
  }

  /*
   * Returns an boolean indicating whether it is visible to friends of friends of the user.
   */
  public function isVisibleToFriendsOfFriends(): bool {
    // Check if we already got the photos for this collection
    if (is_null($this->isVisibleToCircles)) {
      // Get the annotations from the database
      $db = new db();
      $db->connect();
      $statement = $db -> prepare("SELECT photo.photoID as photoID FROM photocollectionassignment,photo WHERE photo.photoID=photocollectionassignment.photoID AND isArchived = 0 AND  collectionID =?");
      $statement->bind_param("i", $this->id);
      $statement->execute();
      $result = $statement->get_result();
    }
    return $this->isVisibleToCircles;
  }

  /*
   * Returns the URL to the page which displays this photo collection.
   */
  public function getURLToCollection(): string {
    return "collection.php?c=" . $this->id;
  }

}

?>
