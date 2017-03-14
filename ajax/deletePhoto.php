<?php
include '../imports.php';

$photoID=$_POST['photoID'];

deletePhotoWithID($photoID);
echo "The Image has been successfully deleted";
?>
