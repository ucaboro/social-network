<?php include "imports.php"; ?>
<!DOCTYPE html>

<html lang="en-gb">
  <?php echo getHtmlForHead(); ?>
  <body>
    <?php echo getHtmlForTopNavbar(); ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
          <!-- Search bar -->
          <div class="panel panel-primary">
            <div class="panel-body">
              <form action="search.php" method="get">
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["search"])) {
                      $searchTerm = $_GET["search"];
                      $results = getUsers($searchTerm);
                    }
                    ?>
                    <input class="form-control" name="search" placeholder="Search all users..." value="<?php echo $searchTerm; ?>">
                  </div>
                </div>
                <button class="btn btn-primary" action"submit">Search</button>
              </form>
            </div>
          </div>
          <!-- /END Search bar -->
          <!-- Friends list -->
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h4 class="panel-title"><?php echo count($results); ?> users found</h4>
            </div>
            <div class="panel-body">
              <?php
              // Output each result
              $thisUser = getUser();
              foreach ($results as $user) {
                echo getHtmlForUserSummary($user, areUsersFriends($thisUser, $user));
              }
              ?>
            </div>
          </div>
          <!-- /END Friends list -->
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
