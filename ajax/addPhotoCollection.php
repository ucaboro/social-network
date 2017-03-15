<?php
include '../imports.php';
$collection_name=$_POST['collection_name'];
$collection_FOF_visibility= $_POST['collection_FOF_visibility'];
$collection_circle_visibility=$_POST['collection_circle_visibility'];

addNewPhotoCollection($collection_name,$collection_FOF_visibility,$collection_circle_visibility);

echo "The collection ".$collection_name." has been created";
?>
