<?php
// NOTE: This file is not meant to be viewed. It is used to respond to AJAX requests.

header("Content-type:application/json");
include "../imports.php";

// Get the data
$userID = $_POST["userID"];

requestFriendship($userID);

echo "\"Friend request sent\"";

?>
