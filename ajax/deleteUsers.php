<?php
include "../imports.php";
// Get the message string and the current circle
$id = $_POST['id'];
$circleID = $_POST['circle'];

  $circle = getCircleWithID($circleID);
  deleteFromCircle($id, $circleID);
  echo getHtmlForCircleUsersPanel($circle);
  echo getHtmlForCirclePanel();

?>
