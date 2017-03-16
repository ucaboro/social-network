<?php
include "imports.php";
//Ensures user is logged in before displaying page
checkLoggedIn();

$isBlogPosted=false;
if(isset($_POST['blogSubmit'])){
  $blogTitle=$_POST["blog-title"];
  $blogpost=$_POST["blog-post"];
  // $date = new DateTime();
  // $dateString = $date->format('YmdHis');

  $blogPostErrors = array();
  if (empty($blogTitle)) {
    $blogPostErrors[] = "Please provide a title for the blog post.";
  }
  if (empty($blogpost)) {
    $blogPostErrors[] = "Please provide content for the blog post.";
  }
  if(empty($blogPostErrors)) {
    addNewBlogPost($blogTitle,$blogpost);
    $isBlogPosted=true;
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
          <!-- Add new post component -->
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h4 class="panel-title">Write new blog post</h4>
            </div>
            <div class="panel-body">
              <form id="blogpost-form" action="index.php" method="POST">
                <div class="form-group">
                  <input class="form-control" name="blog-title" placeholder="Title">
                </div>
                <div class="form-group">
                  <textarea class="form-control" name="blog-post" rows="2" placeholder="What do you want to share today?"></textarea>
                </div>
              </form>


                <?php
                    //If blog post submitted
                    if(isset($_POST['blogSubmit']))
                    {
                        //And if errors occured, display errors as alerts
                        if(empty($blogPostErrors)){
                          echo "
                          <div class=\" col-xs-12 panel-body alert alert-success\" role=\"alert\">
                          Blog Successfully posted</span>
                          </div>";
                        } else {
                          echo "
                          <div class=\" col-xs-12 panel-body alert alert-danger\" role=\"alert\"> Post Unsuccessful: <br>";
                          foreach ($blogPostErrors as $error)
                          {
                              echo $error . "<br>";
                          }
                          echo "</div>";
                        }
                    }
                ?>
                <div class="row">

                <div class="col-xs-12">
                  <button class="btn btn-primary pull-right" name="blogSubmit" value="blogSubmit" type="submit" form="blogpost-form">Post</button>
                </div>
              </div>
            </div>
          </div>

          <!-- /END Add new post component -->
          <!-- Recent activity component -->
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h4 class="panel-title">Recent activity</h4>
            </div>
            <div class="panel-body">
              <?php
              $feed = getRecentActivityFeed();
              foreach ($feed as $item) {
                if ($item instanceof message) {
                  echo getHtmlForCircleMessageFeedItem($item);
                } elseif ($item instanceof photo) {
                  echo getHtmlForPhotoFeedItem($item);
                } elseif ($item instanceof blogPost) {
                  echo getHtmlForBlogPostFeedItem($item);
                }
              }
              ?>
            </div>
          </div>
          <!-- /END Recent activity component -->
        </div>
        <div class="col-md-4">
          <div class="row">
            <div class="col-xs-12">
              <?php echo getHtmlForNavigationPanel(); ?>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <?php echo getHtmlForFriendRequestsPanel(); ?>
            </div>
          </div>
          <div class="row">
            <div id="outer" class="col-xs-12">
              <?php echo getHtmlForCirclePanel(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
    echo getHtmlForNewCircle();
    echo getHtmlForJavascriptImports();
    ?>
  </body>
</html>
