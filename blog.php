<?php include "imports.php";
//Ensures user is logged in before displaying page
checkLoggedIn();
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
          echo getHtmlForSmallUserSummaryPanel($user, "Blog");
          ?>
          <!-- /END Profile summary -->
          <!-- Blog Posts -->
          <?php
          echo getHtmlForBlogPostsListPanel($user, 0, false);
          ?>
          <!-- /END Photos -->
        </div>
        <div class="col-md-4">
          <div class="row">
            <div class="col-xs-12">
              <?php echo getHtmlForNavigationPanel(); ?>
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
