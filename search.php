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
              <form action="search.php" method="get">
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                    <?php
                    $searchTerm = getValueFromGET("search");
                    $isSearch = !is_null($searchTerm);
                    ?>
                    <input class="form-control" name="search" placeholder="Search all users..." value="<?php if ($isSearch) { echo $searchTerm; } ?>">
                  </div>
                </div>
                <button class="btn btn-primary" action"submit">Search</button>
              </form>
            </div>
          </div>
          <!-- /END Search bar -->
          <?php
          if ($isSearch) {
            $results = getUsers($searchTerm);
            $blogResults = getBlogsFromSearchTerm($searchTerm);
          ?>
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
                  $areFriends = areUsersFriends($thisUser, $user);
                  $sentRequest = isFriendRequestPending($thisUser, $user);
                  $receivedRequest = isFriendRequestPending($user, $thisUser);
                  echo getHtmlForUserSummarySearchResult($user, $areFriends, $sentRequest, $receivedRequest);
                }
                echo "<br><br>";
                foreach ($blogResults as $blog) {
                    echo getHtmlForBlogPostSummary($blog, true);
                }
                ?>
              </div>
            </div>
          <?php
          }
          ?>
          <!-- /END Friends list -->
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

    <!-- Add friend modal popup -->
    <!-- Adapted from http://getbootstrap.com/javascript/#modals -->
    <div id="add-friend" class="modal fade" tabindex="-1" role="dialog">
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
            <button id="add-friend-confirm-button" type="button" class="btn btn-primary" data-dismiss="modal" onclick="">Add friend</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <?php echo getHtmlForJavascriptImports(); ?>

    <!-- Javascript for showing the modal -->
    <!-- Adapted from http://getbootstrap.com/javascript/#modals -->
    <script>
      $('#add-friend').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var recipient = button.data('user-name'); // Extract info from data-* attributes
        var id = button.data('user-id');
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this);
        modal.find('#add-friend-name').text(recipient);
        modal.find('#add-friend-confirm-button').attr('onclick', 'sendFriendRequest(' + id + ')');
      });
    </script>

  </body>
</html>
