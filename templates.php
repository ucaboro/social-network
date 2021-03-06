<?php

/* Gets the HTML for the head tag for every page */
function getHtmlForHead() {
  return getHtmlFromPHPFile("templates/head.php");
}

/*
 * Gets the HTML for the navbar at the top of every page.
 */
function getHtmlForTopNavbar() {
  return getHtmlFromPHPFile("templates/navbar.php");
}

/*
 * Gets the HTML for the script tags loaded at the end of every page.
 */
function getHtmlForJavascriptImports() {
  return getHtmlFromPHPFile("templates/script.php");
}

/*
 * Reads the contents of a PHP file and returns it as a string.
 */
function getHtmlFromPHPFile($filename) {
  ob_start();
  include($filename);
  $str = ob_get_contents();
  ob_end_clean();
  return $str;
}

/*
 * Returns the HTML for a clickable circle representing a 'Circle' (group).
 */
function getHtmlForCircleButton($circle) {
  $url = getUrlToCircle($circle->id);
  $name = $circle->name;
  $color = getCircleColor($circle);
  return "<div class=\"circle-container\">
            <a class=\"no-underline\" href=\"$url\"><div class=\"circle\" style=\"background-color:$color; \"><div class=\"circle-text\">$name</div></div></a>
          </div>";


}

/*
 * Returns the HTML for a non-clickable circle representing a 'Circle' (group).
 */
function getHtmlForCircleShape($circle) {
  $color = getCircleColor($circle);
  return "<div class=\"circle-container\">
            <div class=\"circle\" style=\"background-color: $color;\"><div class=\"circle-text hidden-xs\">$circle->name</div></div></a>
          </div>";
}

/*
 * Returns the HTML for a single photo item in the activity feed.
 */
function getHtmlForPhotoFeedItem(photo $photo) {
  $photoUrl = $photo->getURLToPhoto();
  return getHtmlForFeedItem($photo, "uploaded a <a href=\"$photoUrl\">photo</a>.", "<a href=\"$photoUrl\"><img src=\"$photo->src\"></a>");
}

/*
 * Returns the HTML for a new circle message item in the activity feed.
 */
function getHtmlForCircleMessageFeedItem(message $message) {
  $circle = $message->circle;
  $circleUrl = $circle->getUrlToCircle();
  $messageText = strlen($message->text) > 400 ? substr($message->text, 0, 350) . "... <a href=\"$circleUrl\">Continue reading</a>" : $message->text;
  return getHtmlForFeedItem($message, "sent a message to <a href=\"$circleUrl\">$circle->name</a>.", $message->text);
}

/*
 * Returns the HTML for a blog post item in the activity feed.
 */
function getHtmlForBlogPostFeedItem(blogPost $blogPost) {
  $blogUrl = $blogPost->getURLToBlog();
  $blogBody = strlen($blogPost->body) > 400 ? substr($blogPost->body, 0, 350) . "... <a href=\"$blogUrl\">Continue reading</a>" : $blogPost->body;
  return getHtmlForFeedItem($blogPost, "wrote a new post on their <a href=\"$blogUrl\">blog</a>.", $blogBody);
}

/*
 * Returns the HTML for a single generic item in the activity feed.
 */
function getHtmlForFeedItem(interaction $item, string $titleHtml, string $bodyHtml) {
  $user = $item->user;
  $name = $user->getFullName();
  $profileUrl = $user->getUrlToProfile();
  $time = $item->time->format("d M Y H:i");
  $img = getHtmlForSquareImage($user->photoSrc);
  return "<div class=\"feed-item\">
            <div>
              <div class=\"feed-profile-image\"><a href=\"$profileUrl\">$img</a></div>
              <span class=\"feed-item-title\"><a href=\"$profileUrl\">$name</a> $titleHtml</span><br>
              <span class=\"feed-item-time\">$time</span>
            </div>
            <div class=\"feed-item-content\">
              $bodyHtml
            </div>
          </div>";
}

function getHtmlForCircleMessage($message) {
  return getHtmlForComment($message);
}

function getHtmlForComment($comment) {
  $user = $comment->user;
  $name = $user->getFullName();
  $profileUrl = $user->getUrlToProfile();
  $img = getHtmlForSquareImage($user->photoSrc);
  $time = $comment->time->format("d M Y H:i");
  return "<div class=\"message-container\">
            <div>
              <div class=\"feed-profile-image\"><a href=\"$profileUrl\">$img</a></div>
            </div>
            <div class=\"message-content\">
              <span class=\"feed-item-title\"><a href=\"$profileUrl\">$name</a></span>
              <span class=\"feed-item-time\">$time</span><br>
              $comment->text
            </div>
          </div>";
}

/*
 * Returns the URL to the main page for a specific circle.
 */
function getUrlToCircle($circleID) {
  return "circle.php?c=$circleID";
}

/*
 * Returns the HTML for the sidebar panel which displays a list of the users in a particular circle.
 */
function getHtmlForCircleUsersPanel($circle) {
  // Generate the start of the HTML (setting up the panel, panel title)
  $html = "<div class=\"panel panel-primary\">
            <div class=\"panel-heading\" \">
              <h5 style=\"display: inline; \"class=\"panel-title\">People in this circle</h5>

              <i id = \"add\" type=\"button\" data-toggle=\"modal\" data-target=\"#addUsers\"  class = \"glyphicon glyphicon-plus\"  style=\"display: inline; float: right; color:BLACK; font-size: 15px; cursor: pointer; \"></i>

              <i id = \"delete\" type=\"button\" data-toggle=\"modal\" data-target=\"#deleteUsers\"  class = \"glyphicon glyphicon-minus\"  style=\"display: inline; float: right; color:BLACK; font-size: 15px; cursor: pointer; \">&nbsp</i>
            </div>
            <div class=\"panel-body\">
              <div class=\"row\">";

  // Add a button for each user
  foreach ($circle->getUsers() as $id => $user) {
    $img = getHtmlForSquareImage($user->photoSrc);
    $html = $html .
                "<div class=\"col-xs-3\">
                  <a href=\"{$user->getUrlToProfile()}\">$img</a>
                  <a href=\"{$user->getUrlToProfile()}\" class=\"no-underline\"><div class=\"profile-name\">{$user->getFullName()}</div></a>
                </div>";
  }

// Close div tags and return the HTML
return $html . "
            </div>
        </div>
      </div>";
}

  /*
   * Returns the HTML for the sidebar panel which displays a list of the friends of a particular user.
   */
  function getHtmlForUsersFriendsPanel($user) {
    // Generate the start of the HTML (setting up the panel, panel title)
    $html = "<div class=\"panel panel-primary\">
              <div class=\"panel-heading\">
                <h4 class=\"panel-title\">Friends of this person</h4>
              </div>
              <div class=\"panel-body\">
                <div class=\"row\">";

    // Add a button for each user
    $friends = $user->getFriends();
    if (is_null($friends)){
      $html = $html .
                "<div>
                <span class=\"text-center\" ><p>    There is currently no friends </p></span>
                </div>";
    } else {
      foreach ($friends as $id => $friend) {
        $img = getHtmlForSquareImage($friend->photoSrc);
        $html = $html .
                    "<div class=\"col-xs-3\">
                      <a href=\"{$friend->getUrlToProfile()}\">$img</a>
                      <a href=\"{$friend->getUrlToProfile()}\" class=\"no-underline\"><div class=\"profile-name\">{$friend->getFullName()}</div></a>
                    </div>";
      }
    }


  // Close div tags and return the HTML
  return $html . "
              </div>
          </div>
        </div>";
}

function getHtmlForSquareImage($src) {
  return "<div class=\"img-thumb\" style=\"background-image:url('$src')\"></div>";
}

/*
 * Returns the HTML for the panel which displays a list of circles.
 */
function getHtmlForCirclePanel(bool $mainPanel = false) {
  $bootstrapClass = $mainPanel ? "col-xs-3" : "col-xs-4";
  // Generate the start of the HTML (setting up the panel, panel title)
  $html = "<div id=\"circles\" class=\"panel panel-primary\">
            <div class=\"panel-heading\">
              <h4 class=\"panel-title\">Circles</h4>
            </div>
            <div class=\"panel-body\">
              <div class=\"row\">";


  //Add a button for each circle
  //change 1 to getUserID after login is implemented

  foreach (getUserCircles(getUser()->id) as $id => $value) {
  $CircleIDs =$id;

  foreach (getCircleNames($CircleIDs) as $id => $circle) {

    $html = $html . "<div class=\"$bootstrapClass\">" . getHtmlForCircleButton($circle) . "</div>";

  }




}
  // Close div tags and return the HTML and Adding NEW circle
  return $html . "
  <div class = \"$bootstrapClass\">
  <div class=\"circle-container\" >
      <div class = \"circle\" style=\"border-color: white; background-color:white; display: flex; justify-content: center; align-items: center;\">
      <i id = \"add\" type=\"button\" data-toggle=\"modal\" data-target=\"#addModal\"  class = \"glyphicon glyphicon-plus-sign\"  style=\"color:BLACK; font-size: 45px; cursor: pointer; \"></i>
      </div>
  </div></div>
</div>
</div>
</div>";
}

/*
 * Returns the color of the circle.
 * The colors are changed to a more bright, appealing versions
 */
 function getCircleColor($circle) {
   $color = $circle->color;

if($color=="blue"){
  $color = "#337ab7";
} elseif ($color=="red") {
  $color = "#FF4136";
} elseif ($color=="green") {
  $color = "#2ECC40";
} elseif ($color=="YELLOW") {
  $color = "#FFB90F";
} elseif ($color=="orange") {
  $color = "#FF851B";
}elseif ($color=="aqua") {
  $color = "#8FD8D8";}

return "$color";
 }

/*
 * Returns the HTML for a single user on the friends and search pages.
 * $user: The user to display in the search results.
 * $isFriend: true if the current user is a friend of $user, false otherwise.
 * $sentRequest: true if the current user has sent $user a friend request which hasn't been responded to yet, false otherwise.
 * $receivedRequest: true if the current user has recieved a friend request from $user which they haven't responded to yet, false otherwise.
 */
function getHtmlForUserSummarySearchResult(user $user, bool $isFriend, bool $sentRequest, bool $receivedRequest, string $class): string {
  $profileUrl = $user->getUrlToProfile();
  $img = getHtmlForSquareImage($user->photoSrc);
  $name = $user->getFullName();
  $age = $user->getAge();
  if ($isFriend) {
    $buttonHtml = "<button title=\"Delete friend\" class=\"btn btn-link\" data-toggle=\"modal\" data-target=\"#change-friendship\" data-user-name=\"$name\" data-user-id=\"$user->id\" data-change-type=\"0\"><span class=\"glyphicon glyphicon-trash\"></span></button>";
  } elseif ($sentRequest) {
    $buttonHtml = "Friend request sent";
  } elseif ($receivedRequest) {
    $buttonHtml = "Friend request received";
  } else {
    $buttonHtml = "<button title=\"Add friend\" class=\"btn btn-link\" data-toggle=\"modal\" data-target=\"#change-friendship\" data-user-name=\"$name\" data-user-id=\"$user->id\" data-change-type=\"1\"><span class=\"glyphicon glyphicon-plus\"></span></button>";
  }
  return "<div class=\"" . $class . "\">
          <div class=\"row\">
            <div class=\"col-xs-2\" style=\"padding-right:5px\">
                <div class=\"friend-profile-image\"><a href=\"$profileUrl\">$img</a></div>
            </div>
            <div class=\"col-xs-7\">
              <a href=\"$profileUrl\"><span class=\"h4\">$name</span></a><br>
              <span class=\"subtitle\">$age years old, $user->location</span>
            </div>
            <div class=\"col-xs-3 text-right\">
              <div class=\"friend-action\" data-user-id=\"$user->id\">
                $buttonHtml
              </div>
            </div>
          </div>
        </div>";
}

/*
 * Return users for the Add Users to cirlce modal
 */
function getHtmlForAddUserResult(user $user, bool $isFriend, bool $sentRequest, bool $receivedRequest, string $sign): string {
  $profileUrl = $user->getUrlToProfile();
  $img = getHtmlForSquareImage($user->photoSrc);
  $name = $user->getFullName();
  $age = $user->getAge();
  $id = $user->id;


  return "<div class=\"friend\">
          <div class=\"row\">
            <div class=\"col-xs-2\" style=\"padding-right:5px\">
                <div class=\"friend-profile-image\"><a href=\"\">$img</a></div>
            </div>
            <div class=\"col-xs-7\">
              <a href=\"$profileUrl\"><span class=\"h4\">$name</span></a><br>
              <span class=\"subtitle\">$age years old, $user->location</span>
            </div>
            <div class=\"col-xs-3 text-right\">
              <div  class=\"friend-action\" data-user-id=\"$user->id\">
                <span id = \"$id\" type=\"button\" style = \"cursor:pointer\"  class=\"$sign\"></span>

              </div>
            </div>
          </div>
        </div>";
}


/*
 * Returns the HTML for the navigation panel at the side of every page.
 */
function getHtmlForNavigationPanel() {
  echo "<div class=\"panel panel-primary\">
          <div class=\"panel-body\">
            <ul>
             <li><a href=\"index.php\">Home</a></li>
             <li><a href=\"search.php\">Search</a></li>
             <li><a href=\"photos.php\">Photos</a></li>
             <li><a href=\"blog.php\">Blogs</a></li>
             <li><a href=\"friends.php\">Friends</a></li>
             <li><a href=\"friendsOfFriends.php\">Friends of friends</a></li>
             <li><a href=\"circles.php\">Circles</a></li>
             <li><a href=\"settings.php\">Settings</a></li>
           </ul>
          </div>
        </div>";
}



/*
 * Returns the HTML for the smaller version of the user summary panel, which appears at the top of the photos and blog pages.
 */
function getHtmlForSmallUserSummaryPanel(user $user, string $title) {
  $profileUrl = $user->getUrlToProfile();
  $img = getHtmlForSquareImage($user->photoSrc);
  $name = $user->getFullName();
  return "<div class=\"panel panel-primary\">
    <div class=\"panel-body\">
      <div class=\"row\">
        <div class=\"col-xs-2\">
          <a href=\"$profileUrl\">$img</a>
        </div>
        <div class=\"col-xs-10\">
          <div class=\"row\">
            <div class=\"col-xs-12\">
              <span class=\"h2\"><a class=\"no-formatting\" href=\"$profileUrl\">$name</a></span><br>
              <span class=\"h4\">$title</span>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>";
}

function getHtmlForSmallUserSummaryPanelCollection(user $user, string $title) {
    $profileUrl = $user->getUrlToProfile();
    $img = getHtmlForSquareImage($user->photoSrc);
    $name = $user->getFullName();
    return "<div class=\"panel panel-primary\">
    <div class=\"panel-body\">
      <div class=\"row\">
        <div class=\"col-xs-2\">
          <a href=\"$profileUrl\">$img</a>
        </div>
        <div class=\"col-xs-10\">
          <div class=\"row\">
            <div class=\"col-xs-12\">
              <span class=\"h2\"><a class=\"no-formatting\" href=\"$profileUrl\">$name</a></span><br>
              <span class=\"h4\">$title</span>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>";
}

/*
 * Returns the HTML for the list of the blog posts for a user, limited to $limit entries.
 * When $isSummary is set to true, the blog previews are hidden and the show more link is visible.
 */
function getHtmlForBlogPostsListPanel(user $user, int $limit, bool $isSummary) {
  // Get the array of blog posts by this user
  $posts = getBlogPostsByUser($user, $limit);

  $postsHtml = "";
  if (count($posts) > 0) {
    // Get the html for each blog post
    foreach ($posts as $postID => $post) {
      $postsHtml = $postsHtml . getHtmlForBlogPostSummary($post, !$isSummary);
    }
    if ($isSummary) {
      // Output the see more icon
      $postsHtml = $postsHtml . "
              <div class=\"col-xs-12\">
                <a href=\"blog.php?u=$user->id\">See more</a>
              </div>";
    }
  } else {
    // If there's no blog posts yet
    $postsHtml = $postsHtml . "
            <div class=\"col-xs-12\">
              This user hasn't posted on their blog yet!
            </div>";
  }

  return "<div class=\"panel panel-primary\">
            <div class=\"panel-heading\">
              <h4 class=\"panel-title\"><a href=\"blog.php?u=$user->id\">Blog posts</a></h4>
            </div>
            <div class=\"panel-body\">
              <div class=\"row\">
                $postsHtml
              </div>
            </div>
          </div>";
}

function getHtmlForBlogPostSummary(blogPost $post, bool $includePreview) {
  $url = $post->getURLToPost();
  $time = $post->time->format('d M Y H:i');
  if ($includePreview) {
    $preview = "<p>$post->body <a href=\"$url\">Continue reading</a></p>";
  } else {
    $preview = "";
  }
  return "<div class=\"col-xs-12\">
            <div class=\"blog-post-summary\">
              <a href=\"$url\"><span class=\"h4\">$post->headline</span></a><br>
              <span class=\"feed-item-time\">$time</span>$preview
            </div>
          </div>";
}

/*
 * Returns the HTML for the current user's friend requests panel, or an empty string if there are no friend requests.
 */
function getHtmlForFriendRequestsPanel() {
  // Get top of panel
  $html = "<div class=\"panel panel-primary\">
            <div class=\"panel-heading\">
              <h4 class=\"panel-title\">Friend requests</h4>
            </div>
            <div class=\"panel-body\">";

  // Get requests
  $requests = getFriendRequests();
  // If there's no requests, return an empty string.
  if (count($requests) == 0) { return ""; }

  // Create HTML for each request
  $requestHtmls = [];
  foreach ($requests as $userID => $time) {
    $user = getUserWithID($userID);
    $url = $user->getUrlToProfile();
    $img = getHtmlForSquareImage($user->photoSrc);
    $name = $user->getFullName();
    $strTime = $time->format("d M y H:i");
    $requestHtmls[] = "<div class=\"row\">
                        <div class=\"col-xs-8\">
                          <div class=\"feed-profile-image\"><a href=\"$url\">$img</a></div>
                          <span class=\"feed-item-title\"><a href=\"$url\">$name</a></span><br>
                          <span class=\"feed-item-time\">Request sent on $strTime</span>
                        </div>
                        <div class=\"col-xs-4\">
                          <div class=\"text-center\">
                            <button class=\"btn btn-success btn-xs btn-block\" onclick=\"respondToFriendRequest(this, $userID, true)\">Accept</button>
                            <button class=\"btn btn-danger btn-xs btn-block\" onclick=\"respondToFriendRequest(this, $userID, false)\">Decline</button>
                          </div>
                        </div>
                      </div>";
  }

  // Concatenate the strings
  $html .= join("<div class=\"spacer-v\"></div>", $requestHtmls) . "</div></div>";

  return $html; //TODO: work out why this line takes a few seconds to run

}

/*
 * Returns a comma-separated list of names of the users who annotated the specified photo.
 */
function getHtmlForAnnotationsList(photo $photo): string {
  $users = $photo->getAnnotations();
  if (count($users) == 0) {
    return "No acknowledgments yet.";
  }
  // Get a list of names of people who annotated it
  $names = [];
  $includesLoggedInUser = false;
  foreach ($users as $user) {
    // If the logged in user annotated it, display their name as 'You'
    if ($user->id == getUserID()) {
      $includesLoggedInUser = true;
    } else {
      // Add their full name to the list
      $profileUrl = $user->getUrlToProfile();
      $name = $user->getFullName();
      $names[] = "<a href=\"$profileUrl\">$name</a>";
    }
  }
  // Work out the right format for the bit at the start that says 'You' if the current user annotated it
  $youPrefix = "";
  if ($includesLoggedInUser) {
    if (count($names) > 0) {
      $youPrefix = "you, ";
    } else {
      $youPrefix = "you";
    }
  }
  return "Acknowledged by " . $youPrefix . join(", ", $names) . ".";
}

function getHtmlForNewCircle(){
  $html = "<div class=\"modal fade\" id=\"addModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalLabel\">
    <div class=\"modal-dialog\" role=\"document\">
      <div class=\"modal-content\">
        <div class=\"modal-header\">
          <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
          <h4 class=\"modal-title\">Create New Circle</h4>
        </div>
        <div class=\"modal-body\">
          <form>
            <div class=\"form-group\">
              <label for=\"circle-name\" class=\"control-label\">Circle Name:</label>
              <input type=\"text\" class=\"form-control\" id=\"circle-name\">
            </div>
            <div class=\"form-group\">
              <label for=\"circle-color\" class=\"control-label\">Circle Color:</label>
              <br>
                  <a id=\"blue\" href=\"#aboutModal\" data-toggle=\"modal\" data-target=\"#myModal\" class=\"btn btn-circle-sm btn-primary\"><span class=\"glyphicon glyphicon-tint\"></span> </a>
                  <a id=\"aqua\" href=\"#aboutModal\" data-toggle=\"modal\" data-target=\"#myModal\" class=\"btn btn-circle-sm btn-info\"><span class=\"glyphicon glyphicon-tint\"></span> </a>
                  <a id=\"green\" href=\"#aboutModal\" data-toggle=\"modal\" data-target=\"#myModal\" class=\"btn btn-circle-sm btn-success\"><span class=\"glyphicon glyphicon-tint\"></span> </a>
                  <a id=\"orange\" href=\"#aboutModal\" data-toggle=\"modal\" data-target=\"#myModal\" class=\"btn btn-circle-sm btn-warning\"><span class=\"glyphicon glyphicon-tint\"></span> </a>
                  <a id=\"red\" href=\"#aboutModal\" data-toggle=\"modal\" data-target=\"#myModal\" class=\"btn btn-circle-sm btn-danger\"><span class=\"glyphicon glyphicon-tint\"></span> </a>
              </br>
              <label id=\"colorinfo\" for=\"circle-color\" class=\"control-label\"></label>
            </div>
          </form>
        </div>
        <div class=\"modal-footer\">
          <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
          <button id=\"create\" type=\"button\" class=\"btn btn-primary\">Create</button>
        </div>
      </div>
    </div>
    <script type=\"text/javascript\">
    //script for new circle creation

    //Creating New Circle functions
    //get color of the button
    var color =\"\";
    var color_click = function(){
        color = this.id;
    }

     function createCircle(){
       var name = $('#circle-name').val();
       if (name.length==0||color.length==0){
          document.getElementById(\"colorinfo\").innerHTML = \"create a name and select a color\";
         }else{

          document.getElementById(\"colorinfo\").innerHTML = \"\";

      var xhr = new XMLHttpRequest();
      xhr.open('POST','ajax/createNewCircle.php', true);
      xhr.setRequestHeader(\"Content-type\",\"application/x-www-form-urlencoded\");
      xhr.onreadystatechange = function (){

        if (xhr.readyState == 4 && xhr.status ==200){
              var target = document.getElementById(\"outer\");

              target.innerHTML = xhr.responseText;


        }
    }
    xhr.send(\"name=\"+name+\"&color=\"+color);
    }

    }

    //assigning colors to buttons
    document.getElementById('blue').onclick = color_click;
    document.getElementById('aqua').onclick = color_click;
    document.getElementById('green').onclick = color_click;
    document.getElementById('orange').onclick = color_click;
    document.getElementById('red').onclick = color_click;

    //create new circle button
    var create = document.getElementById(\"create\");
    create.addEventListener(\"click\", createCircle);

    </script>
  </div>";


  return $html;

}

function messagingScript(){
  $script = " <script>
  //script fot messaging functionality
    var crcl = $('#circleID').val();
    var alert = document.getElementById(\"alert\");
    var success = document.getElementById(\"success\");

      function sendMsg(){
        var msg = $('#msg').val();

        if (msg.length == 0){
           $('#alert').show();
          alert.innerHTML = \"type your message first\";
        } else{
          $('#alert').hide();
          $('#success').show();
          success.innerHTML = \"sent!\";
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'ajax/sendMessage.php', true);
        xhr.setRequestHeader(\"Content-type\",\"application/x-www-form-urlencoded\");
        xhr.onreadystatechange = function (){
          if (xhr.readyState == 4 && xhr.status ==200){
              var target = document.getElementById(\"msg-panel\");
              target.innerHTML = xhr.responseText;
              console.log(crcl);
              console.log(msg);
          }
        }
        xhr.send(\"msg=\"+msg+\"&crcl=\"+crcl);
      }
    }

    var button = document.getElementById(\"postMsg\");
    button.addEventListener(\"click\", sendMsg);

    //function that renews the messages panel every 3 seconds
    var renew = function () {

        var xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/updateMessage.php', true);
    xhr.setRequestHeader(\"Content-type\",\"application/x-www-form-urlencoded\");
    xhr.onreadystatechange = function (){

      if (xhr.readyState == 4 && xhr.status ==200){
        var target = document.getElementById(\"msg-panel\");
        target.innerHTML = xhr.responseText;
        console.log(\"renewing\");
    }
    }
      xhr.send(\"crcl=\"+crcl);
       setTimeout(renew, 3000);
    };

    // UNCOMMENT THE NEXT LINE FOR DYNAMIC MESSAGES
    renew();
    </script>";

  return $script;
}

?>
