<?php
    $possibleInterestsArray = array("Golfing","Zookeeping","Swimming","Gardening", "Extreme eating","Yoga","Rock climbing","Philanthropy");
    $myInterestsArray = array("Zookeeping","Swimming","Rock climbing", "Philanthropy");
?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="panel-title">Interests</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-12">
                <h5>My Interests:</h5>
                    <ul>
                    <?php
                        foreach($myInterestsArray as $interest){
                            echo "<li>" . $interest . "</li>";
                        }
                    ?>
                    </ul>
                <ul class="dropdown-menu">
                    <li><a href="profile.php">My profile</a></li>
                    <li><a href="settings.php">Settings</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="logout.php" onclick="return confirm('Are you sure you want to log out?')">Log out</a></li>
                </ul>
                <h5>Add new interests:</h5>
                <form action="settings.php" method="post">
                    <select class="form-control" name="interests_settings">
                        <?php
                            foreach($possibleInterestsArray as $interest){
                                echo " <option value=\"" . $interest ."\"?> " . $interest ." </option>";
                            }

                        ?>
                    </select>
                    <br>
                    <input type="submit" name="interests_settings_submit" value="Submit" />
                </form>
            </div>
        </div>
    </div>
</div>