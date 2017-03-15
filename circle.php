<?php include "imports.php";
echo getHtmlForJavascriptImports();

//Ensures user is logged in before displaying page
checkLoggedIn(); ?>
<!DOCTYPE html>

<html lang="en-gb">
  <?php echo getHtmlForHead(); ?>
  <body>
    <?php echo getHtmlForTopNavbar(); ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
          <!-- Add new post component -->
          <div class="panel panel-primary">
            <div class="panel-body">
              <div class="row">
                <div class="col-xs-12 visible-xs-block">
                  <div class="circle-title">
                    <?php   $circleID = $_GET["c"];
                      $circle = getCircleWithID($circleID);?>
                      <input type = "hidden" id = "circleID" value="<?php echo $circleID; ?>">
                    <span class="h1 circle-title"><?php echo $circle->name;?></span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-3 hidden-xs">
                  <?php
                  $circleID = $_GET["c"];
                    $circle = getCircleWithID($circleID);
                     echo getHtmlForCircleShape($circle);?>

                </div>
                <div class="col-xs-12 col-sm-9">
                  <form>
                    <div class="form-group">
                      <textarea id="msg" name="msg" class="form-control" rows="2" placeholder="Send a message..."></textarea>

                    <div id="alert" class="alert alert-danger" role="alert"></div>
                    <div id="success" class="alert alert-success" role="alert"></div>
                    <script>
                    $('#alert').hide();
                    $('#success').hide();
                    </script>
                    </div>
                  </form>
                  <div class="row">
                    <div class="col-xs-12">
                      <button id ="postMsg" name="postMsg" class="btn btn-primary pull-right" type="submit">Post</button>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /END Add new post component -->
          <!-- Recent activity component -->
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h4 class="panel-title">Messages</h4>
            </div>
            <div id = "msg-panel" class="panel-body">
              <?php
              // Get the array of messages

              $messages = getMessagesInCircle($circle);
              // Output each one
              foreach ($messages as $messageID => $message) {
                echo getHtmlForCircleMessage($message);
              }
              ?>
            </div>
          </div>
          <!-- /END Recent activity component -->
        </div>
        <div class="col-md-4">
          <div class="row">
            <div class="col-xs-12">
              <?php echo getHtmlForNavigationPanel(); ?>
            </div>
          </div>
          <div class="row">
            <div id="users" class="col-xs-12">
              <?php
              echo getHtmlForCircleUsersPanel($circle);
              echo messagingScript();
              ?>
              <div class="row">
                <div id="outer"  class="col-xs-12">
                  <?php  echo getHtmlForCirclePanel();?>
                </div>
            </div>

          </div>
        </div>
      </div>
      </div>
<?php   echo getHtmlForNewCircle() ;?>

<div class="modal fade" id="addUsers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
      <div class="modal-header">
        <button id="close" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="addTitle">Add users to the circle</h4>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Friends:</label>

            <div class="panel-body">
              <?php
                //send array variables to ajax
                //header("Content-type: text/javascript");

                $friends = getUser()->getFriends();

                $allUserIds = array();
              // Output each one
              foreach ($friends as $friend) {

                  //condition to push the user to th JS for object creation
                  //if it doesnt exists in the db yet

                  switch (isInTheCircle($friend->id,$circleID)) {
                    case '0':
                      echo getHtmlForAddUserResult($friend, true, false, false);
                        array_push($allUserIds,  $friend->id);
                      break;

                    case '1':
                      continue;
                  }

              }

                $allUserIds_json = json_encode($allUserIds);

              ?>
            </div>

          </div>

      <div class="modal-footer">
        <button id="close" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>";
  </body>
</html>
<script type='text/javascript'>
//ADD NEW USER


//retrieving array from php
var circle = $('#circleID').val();
var obj = JSON.parse('<?= $allUserIds_json; ?>');
console.log(("There are " + obj));

//recieve array from php
function tick(){
  var id = this.id;
  var sign = document.getElementById(id).className;
  document.getElementById(id).className ="glyphicon glyphicon-ok";


    //send to the db
    var xhr = new XMLHttpRequest();
   xhr.open('POST','ajax/addUsers.php', true);
   xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
   xhr.onreadystatechange = function (){

     if (xhr.readyState == 4 && xhr.status ==200){
          var target = document.getElementById("users");
                 target.innerHTML = xhr.responseText;
     }
  }
  xhr.send("id="+id+"&circle="+circle);
  }



//on close change all back to plus
function restoreSign(){
document.location.reload();
  for (i = 0; i < obj.length; i++) {
  document.getElementById(obj[i]).className="glyphicon glyphicon-plus";
  }
}

//create close button
document.getElementById("close").onclick = restoreSign;

//create add button
document.getElementById("add").onclick = addUsers;


//create add button
for (i = 0; i < obj.length; i++) {
  var id = document.getElementById(obj[i]);
  id.addEventListener("click", tick);
}




</script>
