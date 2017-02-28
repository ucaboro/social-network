<?php include "imports.php"; ?>
<!DOCTYPE html>

<html lang="en-gb">
  <?php echo getHtmlForHead(); ?>
  <body>
    <?php echo getHtmlForTopNavbar(); ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
          <!-- Profile summary -->
          <div class="panel panel-primary">
            <div class="panel-body">
              <div class="row">
                <div class="col-xs-2">
                  <?php
                  $userID = $_GET["u"];
                  $user = getUserWithID($userID);
                  $profileUrl = $user->getUrlToProfile();
                  echo "<a href=\"$profileUrl\">" . getHtmlForSquareImage($user->photoSrc) . "</a>";
                  ?>
                </div>
                <div class="col-xs-10">
                  <div class="row">
                    <div class="col-xs-12">
                      <span class="h2"><?php echo $user->getFullName(); ?></span><br>
                      <span class="h4">Photos</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /END Profile summary -->
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
              <div class="panel panel-primary">
                <div class="panel-body">
                  <ul>
                   <li><a href="index.php">Home</a></li>
                   <li><a href="me.php">My Profile</a></li>
                   <li><a href="photos.php">Photos</a></li>
                   <li><a href="blogs.php">Blogs</a></li>
                   <li><a href="friends.php">Friends</a></li>
                 </ul>
                </div>
              </div>
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
