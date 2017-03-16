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
          <!-- Search bar -->
          <div class="panel panel-primary">
            <div class="panel-body">
              <form action="friends.php" method="get">
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                    <?php
                    $searchTerm = getValueFromGET("search");
                    $isSearch = !is_null($searchTerm);
                    ?>
                    <input class="form-control" name="search" placeholder="Search friends..." value="<?php if ($isSearch){ echo $searchTerm;} ?>">
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
              <h4 class="panel-title">Friends</h4>
            </div>
            <div class="panel-body">
              <?php
              // Get the array of friends
              if ($isSearch) {
                $friends = getFriendsOfUser(getUser(), $searchTerm);
                $user = getUser();
                echo $user -> firstName;
                echo "here";
              } else {
                //$friends = getUser()->getFriends();
                  $friends = getFriendsOfUser(getUser());
              }

              // Output each one
              foreach ($friends as $friend) {
                  echo $friend -> firstName;
                echo getHtmlForUserSummarySearchResult($friend, true, false, false);
              }
              ?>
            </div>
          </div>
          <!-- /END Friends list -->
        </div>
        <div class="col-md-4">
          <div class="row">
            <div class="col-xs-12">
              <?php echo getHtmlForNavigationPanel();
                include "templates/findFriends.php";
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add friend modal popup -->
    <!-- Adapted from http://getbootstrap.com/javascript/#modals -->
    <div id="change-friendship" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Send friend request</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to add <span id="add-friend-name">ERROR</span> as a friend?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button id="modal-confirm-button" type="button" class="btn btn-primary" data-dismiss="modal" onclick="">Add friend</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <?php echo getHtmlForJavascriptImports(); ?>

    <script>
        $('#change-friendship').on('show.bs.modal', function (event) {
            // Get all the necessary data
            var button = $(event.relatedTarget); // Button that triggered the modal
            var name = button.data('user-name'); // Extract info from data-* attributes
            var id = button.data('user-id');
            var modal = $(this);

            // Set up the modal depending on what type of button was clicked
            if (button.data('change-type') == 1) {
                setUpModalForAddFriend(modal, id, name);
            } else {
                setUpModalForDeleteFriend(modal, id, name);
            }
        });
    </script>
    <!-- JQuery javascript -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!-- Custom JavaScript -->
    <!--<script src="script.js"></script>-->
  </body>
</html>
