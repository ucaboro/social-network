<?php
include '../imports.php';
$photoID=$_POST['photoID'];
setProfilePhoto($photoID);
echo "Your profile photo has been changed";
?>
