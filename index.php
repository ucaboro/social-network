<?php include "imports.php"; ?>
<!DOCTYPE html>

<html lang="en-gb">
  <?php echo getHtmlForHead(); ?>
  <body>
    <?php echo getHtmlForTopNavbar(); ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xs-12">
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
              stuff goes here...
            </div>
          </div>
          <!-- /END Recent activity component -->
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
