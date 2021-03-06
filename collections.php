<?php include "imports.php";
//Ensures user is logged in before displaying page
checkLoggedIn();

// Delete photo collection if necessary
if(isset($_POST['collection-id'])) {
  deletePhotoCollectionWithID($_POST['collection-id']);
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
          $userID = getValueFromGET("u");
          $me = getUser();
          $user = ($userID == NULL) ? $me : getUserWithID($userID);
          $userID = $user->id;
          echo getHtmlForSmallUserSummaryPanel($user, "Photo Collections");
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
                      $photoSrc = $collection->getCoverPhotoSrc();
                      $img = getHtmlForSquareImage($photoSrc);
                      $url = $collection->getURLToCollection();
                      // Code for close button taken from getbootstrap.com/css
                      echo "<div class=\"col-xs-6\">
                      <div class=\"photo-collection\">";
                      if ($isMe) {
                        echo "<button type=\"button\" class=\"close close-img\" aria-label=\"Close\" data-toggle=\"modal\" data-target=\"#delete-collection\" data-collection-id=\"$collection->id\" data-collection-name=\"$collection->name\"><span aria-hidden=\"true\">&times;</span></button>";
                      }
                      echo "<a href=\"$url\">$img</a>
                        <a href=\"$url\">$collection->name</a>
                      </div>
                    </div>";
                  }
                  // "<button title=\"Delete collection\" class=\"btn btn-link\" data-toggle=\"modal\" data-target=\"#change-friendship\" data-user-name=\"$name\" data-user-id=\"$user->id\" data-change-type=\"0\"><span class=\"glyphicon glyphicon-trash\"></span></button>";
              } else {
                  if ($isMe) {
                      echo "<div class=\"col-xs-12\">You haven't created any photo collections yet.</div> <br><br>";
                  } else {
                      echo "<div class=\"col-xs-12\">This user hasn't added any photo collections yet.</div>";
                  }
              }
              ?>
              <?php
              if (getUserID() == $user->getUserID()) {
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

<!-- Delete collection modal popup -->
<!-- Adapted from http://getbootstrap.com/javascript/#modals -->
<div id="delete-collection" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Delete collection</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete the photo collection '<span id="modal-collection-name">ERROR</span>'?</p>
      </div>
      <div class="modal-footer">
        <form method="POST">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <input id="collection-id" name=collection-id value="0" hidden>
          <button id="modal-delete-collection" type="submit" class="btn btn-primary">Delete</button>
        </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php
echo getHtmlForJavascriptImports();
?>

<script>
  $('#delete-collection').on('show.bs.modal', function (event) {
    // Get all the necessary data
    var button = $(event.relatedTarget); // Button that triggered the modal
    var name = button.data('collection-name'); // Extract info from data-* attributes
    var id = button.data('collection-id');
    var modal = $(this);

    // Update the collection name
    modal.find('#modal-collection-name').text(name);

    // Update the collection ID
    modal.find('#collection-id').attr('value', id);
  });
</script>

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
</body>
</html>
