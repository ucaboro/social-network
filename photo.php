<?php
include "imports.php";
//Ensures user is logged in before displaying page
checkLoggedIn();
// Get the photo object
$photoID = $_GET["p"];
$photo = getPhotoWithID($photoID);
// Submit the POSTed comment if necessary
if(isset($_POST['comment'])){
  $comment = $_POST['comment'];
  if ($comment <> "") {
    addCommentToPhoto($photo, $comment);
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
          <!-- Photo -->
          <?php

          ?>
          <div class="panel panel-primary">
            <div class="panel-body">
              <div class="row">
                <div class="col-xs-12">
                  <div class="photo-container">
                    <img class="img-responsive center-block" id=image src="<?php echo $photo->src; ?>">
                  </div>
                </div>
              </div>
              <div class="row">
                <?php
                $photoOwner = $photo->user;
                $profileImg = getHtmlForSquareImage($photoOwner->photoSrc);
                $name = $photoOwner->getFullName();
                $time = $photo->time->format("d M Y H:i");
                $profileUrl = $photoOwner->getUrlToProfile();
                echo "<div class=\"col-xs-12 \">
                  <div class=\"feed-profile-image\"><a id=\"profile_img\" href=\"\">$profileImg</a></div>
                  <span>Photo uploaded by <a href=\"$profileUrl\">$name</a></span><br>
                  <span class=\"feed-item-time\">uploaded on $time</span>
                </div>";
                ?>
                <?php
                echo "<div class=\"row\">";
                $userID = getValueFromGET("u");
                $user = ($userID == NULL) ? getUser() : getUserWithID($userID);

                if (getUserID()==$photoOwner->getUserID()) {
                  echo "<button type=\"submit\" name=\"delete_pic\" id=\"delete_pic\" class=\"btn btn-warning col-xs-8 col-xs-push-2 col-sm-3 col-sm-push-4\">Delete this picture</button>";
                }

                if (getUser()->photoSrc==$photo->src) {
                  echo "<button disabled type=\"submit\" id=\"set_profile_pic\" name=\"set_profile_pic\" class=\"btn btn-primary col-xs-8 col-xs-push-2 col-sm-3 col-sm-push-5\">Set as profile picture</button>";
                } else {
                  echo "<button type=\"submit\" id=\"set_profile_pic\" name=\"set_profile_pic\" class=\"btn btn-primary col-xs-8 col-xs-push-2 col-sm-3 col-sm-push-5\">Set as profile picture</button>";
                }
                echo "</div>";
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
                  <button class="btn btn-default btn-emoji" onclick="togglePhotoAnnotation(this, <?php echo $photoID; ?>)">👋</button>
                </div>
                <div class="col-xs-12 col-sm-11">
                  <span class="annotation-list">
                    <?php
                    echo getHtmlForAnnotationsList($photo);
                    ?>
                  </span>
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
                  <form class="comment-form" action="" method="POST">
                    <div class="form-group">
                      <textarea name="comment" class="form-control" rows="2" placeholder="Leave a comment..."></textarea>
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

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script  type="text/javascript">
      $(document).ready(function() {

        $('#set_profile_pic').click(function() {
          $.ajax({
            type:"POST",
            url:'ajax/setProfilePic.php',
            data:{photoID: <?php echo  $photoID; ?>},
            success:function(result){
                alert(result);
                document.getElementById("set_profile_pic").disabled = true;
                if (<?php echo getUserID();?> == <?php echo $photoOwner->getUserID(); ?>) {
                  document.getElementById("profile_img").getElementsByClassName("img-thumb")[0].style.backgroundImage = "url('<?php echo $photo->src; ?>')";
                }
            }
          })
        })

        $('#delete_pic').click(function() {
          $.ajax({
            type:"POST",
            url:'ajax/deletePhoto.php',
            data:{photoID: <?php echo  $photoID; ?>},
            success:function(result){
                alert(result);
                document.getElementById("delete_pic").disabled=true;
            }
          })
        })

      });
    </script>
    <?php echo getHtmlForJavascriptImports(); ?>
  </body>
</html>
