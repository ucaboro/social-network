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
   * The time at which the photo collection was created.
   */
  public $time;

  /*
   * An array of the photos in this collection.
   */
  private $photos;

  /*
   * Constructor which initialises the object and populates all fields.
   */
  public function __construct(int $id, user $user, DateTime $time, string $name) {
    $this->id = $id;
    $this->user = $user;
    $this->time = $time;
    $this->name = $name;
  }

  /*
   * Returns an array of the photos in this collection. Key is photo ID, value is photo object.
   */
  public function getPhotos(): array {
    // Check if we already got the photos for this collection
    if (is_null($this->photos)) {
      // Get the annotations from the database

      $db = new db();
      $db->connect();
      $statement = $db -> prepare("SELECT photoID FROM photocollectionassignment WHERE collectionID = ?");
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
   * Returns the URL to the page which displays this photo collection.
   */
  public function getURLToCollection(): string {
    return "collection.php?c=" . $this->id;
  }

}

?>
