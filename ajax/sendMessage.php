<?php
include "../imports.php";
// Get the message string and the current circle
$message = $_POST['msg'];
$circleID = $_POST['crcl'];
//send message to the database
sendMessage($message, $circleID);

//create a current circle object
$circle = getCircleWithID($circleID);
//get the messages in a circle with a new one in it
$messages = getMessagesInCircle($circle);

// Output all of them again
foreach ($messages as $messageID => $msg) {
  echo getHtmlForCircleMessage($msg);
}

?>
