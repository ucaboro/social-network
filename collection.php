<?php include "imports.php";
//Ensures user is logged in before displaying page
checkLoggedIn();

$photoStorageLocation="img/";
//TODO: all this photo upload code has to be edited so that the photo is uploaded to the correct collection / generally checked, i just coped it
$isPhotoUploaded=false;
if(isset($_FILES['image'])){
    // Initialises an empty array for storing photo upload errors.
    $photoUploadErrors= array();

    //  Extracts the photo file details
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];

    //  splits the filename seperated by period and stores it into an array.
    $file_name_Array=explode('.',$file_name);
    //  selects the last element of that array, which is the extension.
    $file_ext=end($file_name_Array);
    //  converts the extension into fully lower case so that it is easier to compare against valid formats.
    $file_ext_lower_case=strtolower($file_ext);

    //  The valid photo extensions.
    $extensions= array("jpeg","jpg","png");

    //  Checks if the uploaded photo has a valid extension.
    if(in_array($file_ext_lower_case,$extensions)=== false){
        $photoUploadErrors[]="The file you uploaded is not in a valid format, please choose a JPEG or PNG file.";
    }

    //  Checks if the photo is under the size limit.
    //  if($file_size > 2097152) {
    //     $photoUploadErrors[]='File size must be less than 2 MB';
    //  }

    // Assigns a random number for the photoname and runs through a loop to make the random file name assigned doesn't already exist.
    $randomName = RAND(1,50000);
    while (isPhotoNameExist($randomName.$file_ext)) {
        $randomName = RAND(1,50000);
    }

    // Checks if any errors exist, if not then it transfers the photo to the storage location and registers the photo info into the database.
    if(empty($photoUploadErrors)==true) {
        move_uploaded_file($file_tmp,$photoStorageLocation.$randomName.".".$file_ext);
        addPhotoToDB($randomName.".".$file_ext);
        $lastAddedPhotoID =   $db->lastInsertId();
        $collectionID = getValueFromGET("c");
        addPhotoToCollection($lastAddedPhotoID,$collectionID);
        $isPhotoUploaded=TRUE;
    }
}
$collectionID = getValueFromGET("c");
if (isset($_POST["delete-photo"])) {
  removePhotoFromCollection($_POST["delete-photo"], $collectionID);
}
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
            $collection = getPhotoCollectionFromID($collectionID);
            $me = getUser();
            $user = $collection->user;
            $userID = $user->id;
            echo getHtmlForSmallUserSummaryPanel($user, "Photo collection '$collection->name'");
            ?>
            <div class="checkbox">
                <label><input type="checkbox" id="Collection_FOF_checkbox2" value="">Visible to Friends of Friends</label>
            </div>
            <div class="checkbox">
                <label><input type="checkbox" id="Collection_circle_checkbox2" value="">Visible to Circles</label>
            </div>
            <!-- /END Profile summary -->

            <!-- Upload Photos to collection -->
            <?php
            $isMe = ($me->id == $userID);
            if ($isMe) {
                ?>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">Upload photos to '<?php echo $collection->name; ?>'</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <form action="collection.php" method="POST" enctype="multipart/form-data">
                                <input class="btn col-xs-6 col-md-4" type="file" name="image" />
                                <input class="btn btn-info" type="submit"/>
                            </form>
                        </div>
                        <?php
                        //If photo upload submitted
                        if(isset($_FILES['image']))
                        {
                            // Show success message if no errors occured or else display errors as alerts
                            if ($isPhotoUploaded) {
                                echo "
                        <div class=\" col-xs-12 panel-body alert alert-success\" role=\"alert\">
                        Photo Successfully uploaded</span>
                        </div>";
                            } else {
                                echo "
                        <div class=\" col-xs-12 panel-body alert alert-danger\" role=\"alert\"> Photo upload unsuccessful: <br>";
                                foreach ($photoUploadErrors as $error)
                                {
                                    echo $error . "<br>";
                                }
                                echo "</div>";
                            }
                        }

                        // Initialises an empty array for storing photo upload errors.
                        $photoUploadErrors= array();

                        $_FILES['image']=null;
                        unset($GLOBALS['_SESSION'][$_FILES['image']]);
                        ?>

                    </div>
                </div>
                <?php
            }
            ?>
            <!-- /END Upload Photos -->

            <!-- Photos in Collection -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">Photos in '<?php echo $collection->name; ?>'</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <?php
                        // Get the array of photos
                        $photos = $collection->getPhotos();
                        if (count($photos) > 0) {
                            // Output each one
                            foreach ($photos as $photoID => $photo) {
                              echo "<div class=\"col-xs-6 col-sm-3\" style=\"padding:8px 15px;\">";
                              if ($isMe) {
                                echo "<button type=\"button\" class=\"close close-img\" aria-label=\"Close\" data-toggle=\"modal\" data-target=\"#remove-photo\" data-photo-id=\"$photo->id\"><span aria-hidden=\"true\">&times;</span></button>";
                              }
                              echo "<a href=\"photo.php?p=$photoID\">" . getHtmlForSquareImage($photo->src) . "</a>
                          </div>";
                            }
                        } else {
                            if ($isMe) {
                                echo "<div class=\"col-xs-12\">You haven't uploaded any photos to this collection yet.</div>";
                            } else {
                                echo "<div class=\"col-xs-12\">This user hasn't uploaded any photos to this collection yet.</div>";
                            }
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

<!-- Delete collection modal popup -->
<!-- Adapted from http://getbootstrap.com/javascript/#modals -->
<div id="remove-photo" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Remove photo</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to remove this photo from the collection? The photo will not be deleted.</p>
      </div>
      <div class="modal-footer">
        <form method="POST">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button id="modal-remove-photo" name="delete-photo" value="" class="btn btn-primary">Remove</button>
        </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php
echo getHtmlForJavascriptImports();
?>

<script>
  $('#remove-photo').on('show.bs.modal', function (event) {
    // Get all the necessary data
    var button = $(event.relatedTarget); // Button that triggered the modal
    var id = button.data('photo-id'); // Extract info from data-* attributes
    var modal = $(this);
    // Update the collection ID
    modal.find('#modal-remove-photo').attr('value', id);
  });
</script>

<script>
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
</body>
</html>
