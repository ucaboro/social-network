<?php
    //$existingInterestsObjectArray = getExistingInterests();
    $existingInterestNamesArray = getExistingInterestNames();
    $myInterestsArray = $user -> getInterestNames();
?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="panel-title">Interests</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-12">
                <!--Display current interests-->
                <h5>My Interests:</h5>
                    <ul>
                    <?php
                        foreach($myInterestsArray as $interestName){
                            echo "<li>" . $interestName  . "</li>";
                        }
                    ?>
                    </ul>
                <ul class="dropdown-menu">
                    <li><a href="profile.php">My profile</a></li>
                    <li><a href="settings.php">Settings</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="logout.php" onclick="return confirm('Are you sure you want to log out?')">Log out</a></li>
                </ul>

                <!--Add new Interests-->
                <br>
                <h5>Add new interests:</h5>
                <!--From dropdown of interests already in the database-->
                <div class="row">
                    <div class="col-xs-10">
                        Existing Interests:
                        <form action="settings.php" method="post">
                            <select class="form-control" name="existing_interests">
                                <?php
                                    foreach($existingInterestNamesArray as $interestName){
                                        echo " <option value=\"" . $interestName ."\"?> " . $interestName  ." </option>";
                                    }

                                ?>
                            </select>
                        </form>
                    </div>
                    <div class="col-xs-2">
                        <br>
                        <button class="btn btn-primary" type="submit" name="existing_interests_submit" value="Submit" form="existing_interests_form">Add</button>
                    </div>
                </div>

                <br>
                <!--Allow user to add new custom interest-->
                <div class="row">
                    <div class="col-xs-10">
                        New Interests:
                        <form id="new_interests_form" action="settings.php" method="POST">
                            <div class="form-group">
                                <input class="form-control" maxlength="20" name="new_interests" placeholder="Add new interest here..">
                            </div>
                        </form>
                    </div>
                    <div class="col-xs-2">
                        <br>
                        <button class="btn btn-primary" type="submit" name="new_interests_submit" value="Submit" form="new_interests_form">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>