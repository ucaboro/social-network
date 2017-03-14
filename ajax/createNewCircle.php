<?php
include "../imports.php";
// Get the message string and the current circle
$name = $_POST['name'];
$color = $_POST['color'];

//add the new circle to the database
addNewCircle($name, $color);

//echo new circle to the screen
echo getHtmlForCirclePanel();


?>
