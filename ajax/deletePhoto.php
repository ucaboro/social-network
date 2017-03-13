<?php
include '../imports.php';

$photoID=$_POST['photoID'];

deletePhotowithID($photoID);
echo "The Image has been successfully deleted";
?>
