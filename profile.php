<?php include "imports.php";
//Ensures user is logged in before displaying page
checkLoggedIn();?>
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
                      $userID = getValueFromGET("u");
                      $user = ($userID == NULL) ? getUser() : getUserWithID($userID);
                      //If no value provided from get, it takes value from currently logged in user (i.e. defaults to your profile)
                      $userID = $user->id;
                      if(areUsersFriendsWithID($userID, $_SESSION['userID'])) {
                          $areFiends = true;
                          $areFriendsOfFriends = true;
                      }
                      else if(areUsersWithIDFriendsOfFriends($userID, $_SESSION['userID'])){
                          $areFiends = false;
                          $areFriendsOfFriends = true;
                      }
                      else{
                          $areFiends = false;
                          $areFriendsOfFriends = false;
                      }
                    echo getHtmlForSquareImage($user->photoSrc);
                  ?>
                </div>
                <div class="col-xs-9">
                  <div class="row">
                    <div class="col-xs-12">
                      <span class="h2"><?php echo $user->getFullName(); ?></span><br>
                      <?php
                      if(displayInfo($user, $areFiends, $areFriendsOfFriends))
                      {
                          $age = $user->getAge();
                          $age = ($age == 0) ? "Age unknown" : $age . " years old";
                          $location = $user->location;
                          $location = ($location == "") ? "location unknown" : $location;
                          ?><span class="h5"><?php echo $age . ", " . $location; ?> </span><?php
                      }
                      else{
                          echo "<h5>This person's profile is currently private. <i class=\"glyphicon glyphicon-lock\"></i></h5>";
                      }
                      ?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-12">
                      <?php
                          $isMe = ( $_SESSION['userID'] == $userID); //Checks to see if the profile being view is the same as the currently logged in user
                          if ($isMe) {
                            echo "This is you.";
                          }
                          else if($areFiends)
                          {
                             echo "You are friends.";
                          }
                          else if($areFriendsOfFriends){
                                echo "You aren't friends but you have friends in common.";
                          }
                          else{
                            echo "You aren't friends.";
                          }
                      ?>
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
                if (count($photos) > 0) {
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
                } else {
                  if ($isMe) {
                    echo "<div class=\"col-xs-12\">You haven't uploaded any photos yet.<br><a href=\"photos.php?u=$user->id\">Upload a photo</a></div>";
                  } else {
                    echo "<div class=\"col-xs-12\">This user hasn't uploaded any photos yet.</div>";
                  }
                }
                ?>
              </div>
            </div>
          </div>
          <!-- /END Photos -->
          <!-- Blog Posts -->
            <?php
                if(displayBlog($user, $areFiends, $areFriendsOfFriends)){
                    echo getHtmlForBlogPostsListPanel($user, 6, true);
                }
                else{
                    echo "<div class=\"panel panel-primary\">
                            <div class=\"panel-heading\">
                                <h4 class=\"panel-title\">Blog posts</h4>
                            </div>
                            <div class=\"panel-body\">
                                <div class=\"row\">
                                  <div class=\"col-xs-12\">
                                    <h5>This user's blog posts are currently private. <i class=\"glyphicon glyphicon-lock\"></i></h5>
                                  </div>
                                </div>
                            </div>
                          </div>";
                }
            ?>
          <!-- END Blog Posts -->
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
