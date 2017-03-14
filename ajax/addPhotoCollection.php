<?php
include '../imports.php';
$collection_name=$_POST['collection_name'];
$collection_FOF_visibility= $_POST['collection_FOF_visibility'];
$collection_circle_visibility=$_POST['collection_circle_visibility'];

// if ($_POST['collection_FOF_visibility']=="true") {
//   $collection_FOF_visibility=1;
// } else {
//   $collection_FOF_visibility=0;
// }
//
// if ($_POST['collection_circle_visibility']=="true") {
//   $collection_circle_visibility=1;
// } else {
//   $collection_circle_visibility=0;
// }

// $FOF_vis=($collection_FOF_visibility==true) ? 1 : 0;
// $cicle_vis=($collection_circle_visibility==true) ? 1 : 0;

addNewPhotoCollection($collection_name,$collection_FOF_visibility,$collection_circle_visibility);
echo "The new collection has been created  ".$_POST['collection_FOF_visibility']." circle: ".$_POST['collection_circle_visibility'];
?>
