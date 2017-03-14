<?php
include '../imports.php';
$collection_name=$_POST['collection_name'];
$collection_FOF_visibility=$_POST['collection_FOF_visibility'];
$collection_circle_visibility=$_POST['collection_circle_visibility'];

$FOF_vis=($collection_FOF_visibility==true) ? 1 : 0;
$cicle_vis=($collection_circle_visibility==true) ? 1 : 0;

// addNewPhotoCollection($collection_name,$collection_FOF_visibility,$collection_circle_visibility);
echo "The new collection has been created".$collection_name." FoF ".$FOF_vis." ann".$cicle_vis." ".$collection_FOF_visibility." 00".$collection_circle_visibility;
?>
