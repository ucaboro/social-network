<?php include "imports.php"; ?>
<!DOCTYPE html>

<html lang="en-gb">
  <?php echo getHtmlForHead(); ?>
  <body>
    <?php echo getHtmlForTopNavbar(); ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
          <!-- Photo -->
          <?php
          // Get the photo object
          $photoID = $_GET["p"];
          $photo = getPhotoWithID($photoID);
          ?>
          <div class="panel panel-primary">
            <div class="panel-body">
              <div class="row">
                <div class="col-xs-12">
                  <div class="photo-container">
                    <img class="img-responsive center-block" src="<?php echo $photo->src; ?>">
                  </div>
                </div>
              </div>
              <div class="row">
                <?php
                $user = $photo->user;
                $profileImg = getHtmlForSquareImage($user->photoSrc);
                $name = $user->getFullName();
                $time = $photo->time->format("d M Y H:i");
                $profileUrl = $user->getUrlToProfile();
                echo "<div class=\"col-xs-12\">
                  <div class=\"feed-profile-image\"><a href=\"\">$profileImg</a></div>
                  <span>Photo uploaded by <a href=\"$profileUrl\">$name</a></span><br>
                  <span class=\"feed-item-time\">uploaded on $time</span>
                </div>";
                ?>
              </div>
            </div>
          </div>
          <!-- /END Photo -->
          <!-- Likes -->
          <div class="panel panel-primary">
            <div class="panel-body">
              <div class="row">
                <div class="col-xs-12 col-sm-1">
                  <button class="btn btn-default btn-emoji">👋</button>
                </div>
                <div class="col-xs-12 col-sm-11">
                  <?php
                  // Get a list of names of people who acknowledged it
                  $names = [];
                  foreach ($photo->getAnnotations() as $user) {
                    $profileUrl = $user->getUrlToProfile();
                    $name = $user->getFullName();
                    $names[] = "<a href=\"$profileUrl\">$name</a>";
                  }
                  if (count($names) == 0) {
                    echo "No acknowledgments yet.";
                  } else {
                    echo "Acknowledged by " . join(", ", $names) . ".";
                  }
                  ?>
                </div>
              </div>
            </div>
          </div>
          <!-- /END Likes -->
          <!-- Comments -->
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h4 class="panel-title">Comments</h4>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-xs-12">
                  <form class="comment-form">
                    <div class="form-group">
                      <textarea class="form-control" rows="2" placeholder="Leave a comment..."></textarea>
                    </div>
                    <button class="btn btn-primary pull-right" type="submit">Send comment</button>
                  </form>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <?php
                  // Get the comments for this photo
                  $comments = $photo->getComments();
                  foreach ($comments as $comment) {
                    echo getHtmlForComment($comment);
                  }
                  ?>
                </div>
              </div>
            </div>
          </div>
          <!-- /END Comments -->
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
                  <h4 class="panel-title">More photos from <?php echo $photo->user->firstName; ?></h4>
                </div>
                <div class="panel-body">
                  <div class="row">
                    <div class="col-xs-12">
                      <div class="row">
                        <?php
                        $somePhotos = getRandomPhotosFromUser($photo->user, 6);
                        foreach ($somePhotos as $aPhoto) {
                          $photoUrl = $aPhoto->getURLToPhoto();
                          echo "<div class=\"col-xs-4\"><div style=\"margin-bottom:10px\">
                                  <a href=\"$photoUrl\">" . getHtmlForSquareImage($aPhoto->src) . "</a>
                                </div></div>";
                        }
                        ?>
                      </div>
                      <div class="row">
                        <div class="col-xs-12">
                          <a href="photos.php?u=<?php echo $photo->user->id; ?>">See more</a>
                        </div>
                      </div>
                    </div>
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
