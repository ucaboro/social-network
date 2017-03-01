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
      // TODO: Not yet implemented.
      $photo1 = new photo(0, getUserWithID(1), new DateTime("2017-04-01 11:57"), "img/ex_photo1.jpg");
      $photo2 = new photo(0, getUserWithID(1), new DateTime("2017-04-01 11:57"), "img/ex_photo1.jpg");
      $this->photos = array($photo1, $photo2);
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
