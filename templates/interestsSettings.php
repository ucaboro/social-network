<?php
    //$existingInterestsObjectArray = getExistingInterests();
    $existingInterestNamesArray = getExistingInterestNames();
    if(isset($_POST['new_interests_submit']) || isset($_POST['existing_interests_submit'])){
        $user = getUser();
    }
    //$myInterestsArray = $user -> getInterestNames();
    $myInterestsArray = $user -> getInterests();
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
                <div class="panel panel-default">
                  <div class="panel-body">
                    <?php
                    if (empty($myInterestsArray)) {
                      echo "No interests yet.";
                    } else {
                      echo "<ul>";
                      foreach($myInterestsArray as $interest){
                          echo "<li>" . $interest -> getName();  /*. " <button>
                                                                      <span class=\"glyphicon glyphicon-trash\"></span>
                                                                  </button></li>"; // user deleteInterestWithID($interest->getID())*/
                      }
                      echo "</ul>";
                    }
                    ?>
                    <!-- <button class='glyphicon glyphicon-trash aria-label'></button>-->

                  </div>
                </div>

                <!--Add new Interests-->
                <h5>Add new interests:</h5>
                <!--From dropdown of interests already in the database-->
                <div class="row">
                    <div class="col-xs-10">
                        <form id="existing_interests_form" action="settings.php" method="post">
                            <select class="form-control" name="existing_interests">
                                <?php
                                    foreach($existingInterestNamesArray as $interestName){
                                        echo " <option value=\"" . $interestName . "\"?> " . $interestName  ." </option>";
                                    }
                                ?>
                            </select>
                        </form>
                    </div>
                    <div class="col-xs-2">
                        <button class="btn btn-primary" type="submit" name="existing_interests_submit" value="Submit" form="existing_interests_form">Add</button>
                    </div>
                </div>

                <br>
                <!--Allow user to add new custom interest-->
                <div class="row">
                    <div class="col-xs-10">
                        Custom Interests:
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
