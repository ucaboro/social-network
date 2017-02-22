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
              // Create some dummy objects, this is just to demo the layout
              $user = new user(0, "Bob", "Berenstain", "img/ex_profile1_thumb.jpg");
              $circle = new circle(0, "Family", "blue", array($user));
              $message = new message(0, $circle, $user, "01 Apr 2017 13:42", "It's one thing to question your mind. It's another to question your eyes and ears. But then again, isn't it all the same? Our senses just mediocre inputs for our brain? Sure, we rely on them, trust they accurately portray the real world around us. But what if the haunting truth is they can't? That what we perceive isn't the real world at all, but just our mind's best guess? That all we really have is a garbled reality, a fuzzy picture we will never truly make out?");
              $message2 = new message(0, $circle, $user, "01 Apr 2017 11:59", "Just signed up for Connect. This website is way better than Facebook!");
              echo getHtmlForCircleMessageFeedItem($user, $message);
              echo getHtmlForPhotoFeedItem($user, "01 Apr 2017 13:45", "img/ex_photo1.jpg", 0);
              echo getHtmlForCircleMessageFeedItem($user, $message2);
              ?>
            </div>
          </div>
          <!-- /END Recent activity component -->
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
              <?php echo getHtmlForCirclePanel(); ?>
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
