<?php
    $page = "friends"
?>

<!-- Search bar -->
<div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="panel-title">Recommended Friends</h4>
    </div>
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
<!--Recommendations-->
<?php
    if ($isSearch) {
        $results = getUsersCollaborativeSearch($searchTerm);
        ?>
        <!-- If searching -->
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo count($results); ?> results found</h4>
            </div>
<?php
    }
    else{
        $results = getUsersCollaborativeSearch();
?>
        <!--Otherwise display all relevant users add a limit?!-->
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title">Recommendations</h4>
        </div>
<?php
    }
?>
        <div class="panel-body">
            <?php
            echo '<h4 class="panel-title">' . count($results) . " users found</h4> <br>";
            // Output each result
            $thisUser = getUser();
            foreach ($results as $user) {
                $areFriends = areUsersFriends($thisUser, $user);// false
                $sentRequest = isFriendRequestPending($thisUser, $user);
                $receivedRequest = isFriendRequestPending($user, $thisUser);
                echo getHtmlForUserSummarySearchResult($user, $areFriends, $sentRequest, $receivedRequest);
            }
            ?>
        </div>
    </div>
   <!-- /END Reccomendations -->


