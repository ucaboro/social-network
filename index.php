<?php include "imports.php"; ?>
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
              <form>
                <div class="form-group">
                  <input class="form-control" placeholder="Title">
                </div>
                <div class="form-group">
                  <textarea class="form-control" rows="2" placeholder="What do you want to share today?"></textarea>
                </div>
              </form>
              <div class="row">
                <div class="col-xs-12">
                  <button class="btn btn-primary pull-right" type="submit">Post</button>
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
            <div class="col-xs-12">
              <?php echo getHtmlForCirclePanel(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php echo getHtmlForJavascriptImports(); ?>
  </body>
</html>