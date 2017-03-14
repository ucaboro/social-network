<?php include "imports.php";
//Ensures user is logged in before displaying page
checkLoggedIn();

?>
<!DOCTYPE html>

<html lang="en-gb">
<?php echo getHtmlForHead(); ?>
<body>
<?php echo getHtmlForTopNavbar(); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <!-- Profile summary -->
            <?php
                $userID = getValueFromGET("u");
                $me = getUser();
                $user = ($userID == NULL) ? $me : getUserWithID($userID);
                $userID = $user->id;
                echo getHtmlForSmallUserSummaryPanel($user, "Photos");
                $isMe = ($me->id == $userID);
            ?>
            <!-- /END Profile summary -->

            <!-- Collections -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">Photo Collections</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <?php
                        $collections = getPhotoCollectionsByUser($user);
                        if (count($collections) > 0) {
                            foreach ($collections as $collection) {
                                $photos = $collection->getPhotos();
                                $photo = $photos[0];
                                $img = getHtmlForSquareImage($photo->src);
                                $url = $collection->getURLToCollection();
                                echo "<div class=\"col-xs-6\">
                                <div class=\"photo-collection\">
                                  <a href=\"$url\">$img</a>
                                  <a href=\"$url\">$collection->name</a>
                                </div>
                              </div>";
                            }

                        } else {
                            if ($isMe) {
                                //TODO: Replace this line with a + button?
                                echo "<div class=\"col-xs-12\">You haven't created any photo collections yet.</div> <br><br>";
                            } else {
                                echo "<div class=\"col-xs-12\">This user hasn't added any photo collections yet.</div>";
                            }
                        }
                        ?>
                        <?php
                        if (getUserID()==$user->getUserID()) {
                            echo "<div class=\"col-xs-6\">
                              <button type=\"button\" id=\"add_collection_invoke\" class=\"btn btn-primary btn-sm\">
                              <span class=\"glyphicon glyphicon-plus\" aria-hidden=\"true\"></span> Add New Collection
                              </button></div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- /END Photos -->

        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-xs-12">
                    <?php echo getHtmlForNavigationPanel(); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div id="add_collection_panel" class="panel panel-primary hidden">
                        <div class="panel-heading">
                            <h4 class="panel-title">Add New Photo Collection</h4>
                        </div>
                        <div class="panel-body">

                            <form id="new-photo-collection-form" action="index.php" method="POST">
                                <div class="form-group">
                                    <input class="form-control" id="collection_name_input" name="blog-title" placeholder="Name of the Collection">
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" id="Collection_FOF_checkbox" value="">Visible to Friends of Friends</label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" id="Collection_circle_checkbox" value="">Visible to Circles</label>
                                </div>
                                <button id="new_collection" class="btn btn-primary center-block" type="submit">Add Collection</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script  type="text/javascript">
    $(document).ready(function() {

        $('#add_collection_invoke').click(function() {
            document.getElementById("add_collection_panel").classList.remove("hidden");
        })

        $('#new_collection').click(function() {
            $.ajax({
                type:"POST",
                url:'ajax/addPhotoCollection.php',
                data:{collection_name:document.getElementById('collection_name_input').value ,
                    collection_FOF_visibility:document.getElementById('Collection_FOF_checkbox').checked,
                    collection_circle_visibility:document.getElementById('Collection_circle_checkbox').checked},
                success:function(result){
                    // alert(result);
                    // document.getElementById("delete_pic").disabled=true;
                }
            })
        })

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
