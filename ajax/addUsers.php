<?php
include "../imports.php";
// Get the message string and the current circle
$id = $_POST['id'];
$circleID = $_POST['circle'];


$check = checkUserInCircle($id, $circleID);
$circle = getCircleWithID($circleID);
if ($check == 1){
  echo getHtmlForCircleUsersPanel($circle);
  echo getHtmlForCirclePanel();
}else{
  addToCircle($id, $circleID);
  echo getHtmlForCircleUsersPanel($circle);
  echo getHtmlForCirclePanel();
}

?>
