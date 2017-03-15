<?php include "imports.php";
echo getHtmlForJavascriptImports();

//Ensures user is logged in before displaying page
checkLoggedIn(); ?>
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
            <div class="panel-body">
              <div class="row">
                <div class="col-xs-12 visible-xs-block">
                  <div class="circle-title">
                    <?php   $circleID = $_GET["c"];
                      $circle = getCircleWithID($circleID);?>
                      <input type = "hidden" id = "circleID" value="<?php echo $circleID; ?>">
                    <span class="h1 circle-title"><?php echo $circle->name;?></span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-3 hidden-xs">
                  <?php
                  $circleID = $_GET["c"];
                    $circle = getCircleWithID($circleID);
                     echo getHtmlForCircleShape($circle);?>

                </div>
                <div class="col-xs-12 col-sm-9">
                  <form>
                    <div class="form-group">
                      <textarea id="msg" name="msg" class="form-control" rows="2" placeholder="Send a message..."></textarea>

                    <div id="alert" class="alert alert-danger" role="alert"></div>
                    <div id="success" class="alert alert-success" role="alert"></div>
                    <script>
                    $('#alert').hide();
                    $('#success').hide();
                    </script>
                    </div>
                  </form>
                  <div class="row">
                    <div class="col-xs-12">
                      <button id ="postMsg" name="postMsg" class="btn btn-primary pull-right" type="submit">Post</button>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /END Add new post component -->
          <!-- Recent activity component -->
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h4 class="panel-title">Messages</h4>
            </div>
            <div id = "msg-panel" class="panel-body">
              <?php
              // Get the array of messages

              $messages = getMessagesInCircle($circle);

              // Output each one
              foreach ($messages as $messageID => $message) {
                echo getHtmlForCircleMessage($message);
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

              <?php
              echo getHtmlForJavascriptImports();
              echo getHtmlForCircleUsersPanel($circle);
              echo getHtmlForCirclePanel();
              echo getHtmlForNewCircle();
              echo messagingScript();
              ?>

            </div>
          </div>
        </div>
      </div>
      </div>

  </body>
</html>
