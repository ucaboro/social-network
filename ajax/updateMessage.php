<?php
include "../imports.php";
// Get the message string and the current circle
$circleID = $_POST['crcl'];

//create a current circle object
$circle = getCircleWithID($circleID);
//get the messages in a circle with a new one in it
$messages = getMessagesInCircle($circle);

// Output all of them again
foreach ($messages as $messageID => $msg) {
  echo getHtmlForCircleMessage($msg);
}

?>
