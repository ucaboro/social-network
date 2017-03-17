<?php
    //$page = "friends"
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
                <h4 class="panel-title"><?php echo count($results); ?> Recommendations found</h4>
            </div>
<?php
    }
    else{
        $results = getUsersCollaborativeSearch();
?>
        <!--Otherwise display all relevant users-->
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title">Recommendations</h4>
        </div>
<?php
    }
?>
        <div class="panel-body">
            <?php
            echo '<h4 class="panel-title">' . count($results) . " recommendations found</h4> <br>";
            // Output each result
            $thisUser = getUser();
            //Array of users ordered according to commonalities, with number of commonalities stored in same array
            foreach ($results as $userCommonArray)
            //for($i = 0; $i<10; $i++)
            {
                //$userCommonArray = $results[$i];
                //User being outputted
                $user = $userCommonArray[1];
                //Number of interests in common with logged in user
                $commonInterest = $userCommonArray[2];
                //Number of friends in common with logged in user
                $commonFriends = $userCommonArray[3];
                $areFriends = false;
                $sentRequest = isFriendRequestPending($thisUser, $user);
                $receivedRequest = isFriendRequestPending($user, $thisUser);
                //Text to be displayed below user panel
                $blurb = "";
                if($commonInterest > 0 && $commonFriends > 0){
                    $blurb = $blurb. "You have " . $commonInterest . " interests in common and " . $commonFriends . " friends in common";
                }
                else if($commonInterest > 0){
                    $blurb ="You have " . $commonInterest . " interests in common.";
                }
                else if($commonFriends > 0){
                    "You have " . $commonFriends . " friends in common.";
                }
                //Echo user panel
                echo getHtmlForUserSummarySearchResult($user, $areFriends, $sentRequest, $receivedRequest, "recommended-friend");
                //Echo text
                echo "<span class=\"recommendation\">" . $blurb . "</span>
                 <br> <br>";
            }
            ?>
        </div>
    </div>
   <!-- /END Reccomendations -->
