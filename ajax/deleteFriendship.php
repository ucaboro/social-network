<?php
// NOTE: This file is not meant to be viewed. It is used to respond to AJAX requests.

header("Content-type:application/json");
include "../imports.php";

// Get the data
$userID = $_POST["userID"];

deleteFriendship($userID);

echo "\"Friend removed\"";

?>
