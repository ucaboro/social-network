<?php
// NOTE: This file doesn't contain any output, it just adds a photo to a collection and redirects.

include "imports.php";

// Get the data
$photoID = getValueFromGET("p");
$collectionID = getValueFromGET("c");

// Add the photo to the collection
addPhotoToCollection($photoID, $collectionID);

// Redirect to the collection URL
redirectTo("collection.php?c=" . $collectionID);

?>
