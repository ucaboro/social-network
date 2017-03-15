<?php
// NOTE: This file is not meant to be viewed. It is used to respond to AJAX requests.

include "../imports.php";

// Get the data
$photoID = $_POST["photoID"];
$photo = getPhotoWithID($photoID);

togglePhotoAnnotation($photo);

?>
