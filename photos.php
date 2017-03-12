<?php include "imports.php";
//Ensures user is logged in before displaying page
checkLoggedIn();

$photoStorageLocation="img/";

$isPhotoUploaded=false;
if(isset($_FILES['image'])){
   $photoUploadErrors= array();
   $file_name = $_FILES['image']['name'];
   $file_size = $_FILES['image']['size'];
   $file_tmp = $_FILES['image']['tmp_name'];
   $file_type = $_FILES['image']['type'];

   $date = new DateTime();
   $dateString = $date->format('YmdHis');

   $file_prePreExt=explode('.',$file_name);
   $file_preext=end($file_prePreExt);
   $file_ext=strtolower($file_preext);

   $extensions= array("jpeg","jpg","png");

   if(in_array($file_ext,$extensions)=== false){
      $photoUploadErrors[]="extension not allowed, please choose a JPEG or PNG file.";
   }

   if($file_size > 2097152) {
      $photoUploadErrors[]='File size must be less than 2 MB';
   }

   $randomName = RAND(1,50000);
   while (!isPhotoNameExitst($randomName.$file_ext)) {
     $randomName = RAND(1,50000);
   }
   if(empty($photoUploadErrors)==true) {
      move_uploaded_file($file_tmp,$photoStorageLocation.$randomName.".".$file_ext);
      addPhotoToDB($randomName.".".$file_ext,$dateString);

   }
}

?>
<!DOCTYPE html>

<html lang="en-gb">
  <?php echo getHtmlForHead(); ?>
  <body>
    <?php echo getHtmlForTopNavbar(); ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
          <!-- Profile summary -->
          <?php
          $userID = getValueFromGET("u");
          $user = ($userID == NULL) ? getUser() : getUserWithID($userID);
          echo getHtmlForSmallUserSummaryPanel($user, "Photos");
          ?>
          <!-- /END Profile summary -->


          <!-- <div class="panel-body">
              <input type="file" class="btn btn-primary pull-right" action="\uploadPhoto.php">Add Photos</input>
          </div> -->


          <!-- Photos -->
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h4 class="panel-title">Upload Photos</h4>
            </div>
            <div class="panel-body">
              <div class="row">
                <form action = "photos.php" method = "POST" enctype = "multipart/form-data">
                <input class="btn col-xs-6 col-md-4 " type = "file" name = "image" />
                <input class="btn btn-info" type = "submit"/>
                </form>
              </div>
              <?php
                  //If registration form submitted
                  if(isset($_FILES['image']))
                  {
                      //And if errors occured, display errors as alerts
                      if(empty($photoUploadErrors)){
                        echo "
                        <div class=\" col-xs-12 panel-body alert alert-success\" role=\"alert\">
                        Photo Successfully uploaded</span>
                        </div>";
                      } else {
                        echo "
                        <div class=\" col-xs-12 panel-body alert alert-danger\" role=\"alert\"> Photo upload unsuccessful: <br>";
                        foreach ($photoUploadErrors as $error)
                        {
                            echo $error . "<br>";
                        }
                        echo "</div>";
                      }
                  }
              ?>

            </div>
          </div>
          <!-- /END Photos -->

          <!-- Photos -->
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h4 class="panel-title">Photos</h4>
            </div>
            <div class="panel-body">
              <div class="row">
                <?php
                // Get the array of photos
                $photos = getPhotosOwnedByUser($user);
                // Output each one
                foreach ($photos as $photoID => $photo) {
                  echo "<div class=\"col-xs-6 col-sm-3\" style=\"padding:8px 15px;\">
                          <a href=\"photo.php?p=$photoID\">" . getHtmlForSquareImage($photo->src) . "</a>
                        </div>";
                }
                ?>
              </div>
            </div>
          </div>
          <!-- /END Photos -->
        </div>
        <div class="col-md-4">
          <div class="row">
            <div class="col-xs-12">
              <?php echo getHtmlForNavigationPanel(); ?>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h4 class="panel-title">Photo Collections</h4>
                </div>
                <div class="panel-body">
                  <div class="row">
                    <?php
                    $collections = getPhotoCollectionsByUser($user);
                    foreach ($collections as $collection) {
                      $photos = $collection->getPhotos();
                      $photo = $photos[0];
                      $img = getHtmlForSquareImage($photo->src);
                      $url = $collection->getURLToCollection();
                      echo "<div class=\"col-xs-6\">
                              <div class=\"photo-collection\">
                                <a href=\"$url\">$img</a>
                                <a href=\"$url\">$collection->name</a>
                              </div>
                            </div>";
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- JQuery javascript -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!-- Custom JavaScript -->
    <!--<script src="script.js"></script>-->
  </body>
</html>
