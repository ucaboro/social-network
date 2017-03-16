<?php
    $page = "friends"
?>

<!-- Search bar -->
<div class="panel panel-primary">
    <div class="panel-body">
        <form action="<?echo $page?>.php" method="get">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                    <?php
                    $searchTerm = getValueFromGET("search"); //GET?
                    $isSearch = !is_null($searchTerm);
                    ?>
                    <input class="form-control" name="search" placeholder="Search for new recommended friends.." value="<?php if ($isSearch) { echo $searchTerm; } ?>">
                </div>
            </div>
            <button class="btn btn-primary" action"submit">Search</button>
        </form>
    </div>
</div>
<!-- /END Search bar -->
<?php
if ($isSearch) {
    $results = getUsersCollaborative();
    ?>
    <!-- Search Results -->
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><?php echo (count($results) + count($results)); ?> results found</h4>
        </div>
        <div class="panel-body">
            <?php
            echo '<h4 class="panel-title">' . count($results) . " users found</h4> <br>";
            // Output each result
            $thisUser = getUser();
            foreach ($results as $user) {
                $areFriends = areUsersFriends($thisUser, $user);
                $sentRequest = isFriendRequestPending($thisUser, $user);
                $receivedRequest = isFriendRequestPending($user, $thisUser);
                echo getHtmlForUserSummarySearchResult($user, $areFriends, $sentRequest, $receivedRequest);
            }
            ?>
        </div>
    </div>
    <?php
}
?>
            <!-- /END Search Results -->


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

<!-- Javascript for showing the modal -->
<!-- Adapted from http://getbootstrap.com/javascript/#modals -->
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

</body>
</html>