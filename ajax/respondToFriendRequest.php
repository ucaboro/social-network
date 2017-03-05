<?php
// NOTE: This file is not meant to be viewed. It is used to respond to AJAX requests.

header("Content-type:application/json");
include "../imports.php";

// Get the data
$requesterID = $_POST["userID"];
$isAccept = $_POST["isAccept"];

// If accepting the request, update the database
if ($isAccept === 'true') {
  acceptFriendRequest($requesterID);
  echo "\"Accepted\"";
} else {
  declineFriendRequest($requesterID);
  echo "\"Declined\"";
}

?>
