<?php include "imports.php";
include "templates\script.php";
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
            <div class="col-xs-12">

              <?php
              echo getHtmlForCircleUsersPanel($circle);
              echo getHtmlForCirclePanel();
              ?>

            </div>
          </div>
        </div>
      </div>
    </div>


<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Create New Circle</h4>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="circle-name" class="control-label">Circle Name:</label>
            <input type="text" class="form-control" id="circle-name">
          </div>
          <div class="form-group">
            <label for="circle-color" class="control-label">Circle Color:</label>
            <br>
                <a id="blue" href="#aboutModal" data-toggle="modal" data-target="#myModal" class="btn btn-circle-sm btn-primary"><span class="glyphicon glyphicon-tint"></span> </a>
                <a id="aqua" href="#aboutModal" data-toggle="modal" data-target="#myModal" class="btn btn-circle-sm btn-info"><span class="glyphicon glyphicon-tint"></span> </a>
                <a id="green" href="#aboutModal" data-toggle="modal" data-target="#myModal" class="btn btn-circle-sm btn-success"><span class="glyphicon glyphicon-tint"></span> </a>
                <a id="orange" href="#aboutModal" data-toggle="modal" data-target="#myModal" class="btn btn-circle-sm btn-warning"><span class="glyphicon glyphicon-tint"></span> </a>
                <a id="red" href="#aboutModal" data-toggle="modal" data-target="#myModal" class="btn btn-circle-sm btn-danger"><span class="glyphicon glyphicon-tint"></span> </a>
            </br>
            <label id="colorinfo" for="circle-color" class="control-label"></label>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button id="create" type="button" class="btn btn-primary">Create</button>
      </div>
    </div>
  </div>
</div>




    <!-- JQuery javascript -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!-- Custom JavaScript -->
    <!--<script src="script.js"></script>-->

    <script>
    // TODO: PUSH BELOW CODE INTO sript.js after proper testing

    //get color of the button
    var color ="";
    var color_click = function(){
        color = this.id;
    }

     function createCircle(){
       var name = $('#circle-name').val();
       if (name.length==0||color.length==0){
          document.getElementById("colorinfo").innerHTML = "create a name and select a color";
         }else{

          document.getElementById("colorinfo").innerHTML = "";

      var xhr = new XMLHttpRequest();
      xhr.open('POST','ajax/createNewCircle.php', true);
      xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      xhr.onreadystatechange = function (){

        if (xhr.readyState == 4 && xhr.status ==200){
              var target = document.getElementById("circles");
              target.innerHTML = xhr.responseText;
              //location.reload();
        }
    }
    xhr.send("name="+name+"&color="+color);
}

}
    //assigning colors to buttons
    document.getElementById('blue').onclick = color_click;
    document.getElementById('aqua').onclick = color_click;
    document.getElementById('green').onclick = color_click;
    document.getElementById('orange').onclick = color_click;
    document.getElementById('red').onclick = color_click;

    //create new circle button
    var create = document.getElementById("create");
    create.addEventListener("click", createCircle);

    //script fot messaging functionality
    var crcl = $('#circleID').val();
    var alert = document.getElementById("alert");
    var success = document.getElementById("success");

      function sendMsg(){
        var msg = $('#msg').val();

        if (msg.length == 0){
           $('#alert').show();
          alert.innerHTML = "type your message first";
        } else{
          $('#alert').hide();
          $('#success').show();
          success.innerHTML = "sent!";
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'ajax/sendMessage.php', true);
        xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhr.onreadystatechange = function (){
          if (xhr.readyState == 4 && xhr.status ==200){
              var target = document.getElementById("msg-panel");
              target.innerHTML = xhr.responseText;
              console.log(crcl);
              console.log(msg);
              //console.log(xhr.readyState);
            }
        }
        xhr.send("msg="+msg+"&crcl="+crcl);
      }
    }

    var button = document.getElementById("postMsg");
    button.addEventListener("click", sendMsg);

  //function that renews the messages panel every 3 seconds
    var renew = function () {

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/updateMessage.php', true);
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhr.onreadystatechange = function (){

      if (xhr.readyState == 4 && xhr.status ==200){
        var target = document.getElementById("msg-panel");
        target.innerHTML = xhr.responseText;
        console.log("renewing");
    }
  }
      xhr.send("crcl="+crcl);
       setTimeout(renew, 3000);
    };

    // UNCOMMENT THE NEXT LINE FOR DYNAMIC MESSAGES
    //renew();
    </script>
  </body>
</html>
