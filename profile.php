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
                <div class="col-xs-3">
                  <?php
                  $userID = $_GET["u"];
                  $user = getUserWithID($userID);
                  echo getHtmlForSquareImage($user->photoSrc);
                  ?>
                </div>
                <div class="col-xs-9">
                  <div class="row">
                    <div class="col-xs-12">
                      <span class="h2"><?php echo $user->getFullName(); ?></span><br>
                      <span class="h5"><?php echo $user->getAge() . " years old, " . $user->location; ?> </span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-12">
                      You are friends.
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
                // Get the array of photos (only the first 7)
                $photos = getPhotosOwnedByUser($user, 7);
                // Output each one
                foreach ($photos as $photoID => $photo) {
                  echo "<div class=\"col-xs-6 col-sm-3\" style=\"padding:8px 15px;\">
                          <a href=\"photo.php?p=$photoID\">" . getHtmlForSquareImage($photo->src) . "</a>
                        </div>";
                }
                // Output the see more icon
                echo "<div class=\"col-xs-6 col-sm-3\" style=\"padding:8px 15px;\">
                        <a href=\"photos.php?u=$user->id\">See more</a>
                      </div>";
                ?>
              </div>
            </div>
          </div>
          <!-- /END Photos -->
          <!-- Blog Posts -->
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h4 class="panel-title">Blog posts</h4>
            </div>
            <div class="panel-body">
              <div class="row">
                <?php
                // Get the array of blog posts by this user
                $posts = getBlogPostsByUser($user);
                // Output each one
                foreach ($posts as $postID => $post) {
                  $time = $post->time->format('d M Y H:i');
                  echo "<div class=\"col-xs-12\">
                          <a href=\"posts.php?p=$postID\"><span class=\"h4\">" . $post->headline . "</span></a><br>
                          <p class=\"feed-item-time\">$time</p>
                        </div>";
                }
                // Output the see more icon
                echo "<div class=\"col-xs-12\">
                        <a href=\"blog.php?u=$user->id\">See more</a>
                      </div>";
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
              <?php
              echo getHtmlForUsersFriendsPanel($user);
              ?>
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
