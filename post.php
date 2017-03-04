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
          <?php
          $postID = getValueFromGET("p");
          $post = getBlogPostWithID($postID);
          $time = $post->time->format('d M Y H:i');
          $user = $post->user;
          $img = getHtmlForSquareImage($user->photoSrc);
          $profileUrl = $user->getUrlToProfile();
          echo getHtmlForSmallUserSummaryPanel($user, "Blog");
          ?>
          <!-- /END Profile summary -->
          <!-- The blog post -->
          <div class="panel panel-primary">
            <div class="panel-body">
              <span class="h3"><?php echo $post->headline; ?></span><br>
              <span class="subtitle">Posted on <span class="subtitle-bold"><?php echo $time; ?></span> by <a href="<?php echo $profileUrl; ?>"><span class="subtitle-bold"><?php echo $user->getFullName(); ?></span></a></span><br>
              <div class="blog-post-body"><?php echo $post->body; ?></div>
              <a href="<?php echo $post->getURLToBlog(); ?>">< Back to <?php echo $user->getFullName(); ?>'s blog</a>
            </div>
          </div>
          <!-- /END blog post -->
        </div>
        <div class="col-md-4">
          <div class="row">
            <div class="col-xs-12">
              <?php echo getHtmlForNavigationPanel(); ?>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <?php echo getHtmlForBlogPostsListPanel($user, 0, true); ?>
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
