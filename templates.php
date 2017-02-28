<?php

/* Gets the HTML for the head tag for every page */
function getHtmlForHead() {
  return file_get_contents("templates/head.php");
}

/*
 * Gets the HTML for the navbar at the top of every page.
 */
function getHtmlForTopNavbar() {
  return file_get_contents("templates/navbar.php");
}

/*
 * Gets the HTML for the script tags loaded at the end of every page.
 */
function getHtmlForJavascriptImports() {
  return file_get_contents("templates/script.php");
}

/*
 * Returns the HTML for a clickable circle representing a 'Circle' (group).
 */
function getHtmlForCircleButton($circle) {
  $url = getUrlToCircle($circle->id);
  $name = $circle->name;
  return "<div class=\"circle-container\">
            <a class=\"no-underline\" href=\"$url\"><div class=\"circle\"><div class=\"circle-text\">$name</div></div></a>
          </div>";
}

/*
 * Returns the HTML for a non-clickable circle representing a 'Circle' (group).
 */
function getHtmlForCircleShape($circle) {
  return "<div class=\"circle-container\">
            <div class=\"circle\"><div class=\"circle-text hidden-xs\">$circle->name</div></div></a>
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
            <div class=\"panel-heading\">
              <h4 class=\"panel-title\">People in this circle</h4>
            </div>
            <div class=\"panel-body\">
              <div class=\"row\">";

  // Add a button for each user
  foreach ($circle->users as $id => $user) {
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
    foreach ($friends as $id => $friend) {
      $img = getHtmlForSquareImage($friend->photoSrc);
      $html = $html .
                  "<div class=\"col-xs-3\">
                    <a href=\"{$friend->getUrlToProfile()}\">$img</a>
                    <a href=\"{$friend->getUrlToProfile()}\" class=\"no-underline\"><div class=\"profile-name\">{$friend->getFullName()}</div></a>
                  </div>";
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
 * Returns the HTML for the sidebar panel which displays a list of circles.
 */
function getHtmlForCirclePanel() {
  // Generate the start of the HTML (setting up the panel, panel title)
  $html = "<div class=\"panel panel-primary\">
            <div class=\"panel-heading\">
              <h4 class=\"panel-title\">Circles</h4>
            </div>
            <div class=\"panel-body\">
              <div class=\"row\">";

  // Add a button for each circle
  foreach (getCirclesForUser(getUser()) as $id => $circle) {
    $html = $html . "<div class=\"col-xs-4\">" . getHtmlForCircleButton($circle) . "</div>";
  }

  // Close div tags and return the HTML
  return $html . "
              </div>
          </div>
        </div>";
}

/*
 * Returns the HTML for a single user on the friends and search pages.
 */
function getHtmlForUserSummary(user $user, bool $isFriend): string {
  $profileUrl = $user->getUrlToProfile();
  $img = getHtmlForSquareImage($user->photoSrc);
  $name = $user->getFullName();
  $age = $user->getAge();
  if ($isFriend) {
    $buttonHtml = "<button class=\"btn btn-link\"><span class=\"glyphicon glyphicon-trash\"></span></button>";
  } else {
    $buttonHtml = "<button class=\"btn btn-link\"><span class=\"glyphicon glyphicon-plus\"></span></button>";
  }
  return "<div class=\"friend\">
          <div class=\"row\">
            <div class=\"col-xs-2\" style=\"padding-right:5px\">
                <div class=\"friend-profile-image\"><a href=\"$profileUrl\">$img</a></div>
            </div>
            <div class=\"col-xs-9\">
              <a href=\"$profileUrl\"><span class=\"h4\">$name</span></a><br>
              <span class=\"subtitle\">$age years old, $user->location</span>
            </div>
            <div class=\"col-xs-1\">
              $buttonHtml
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
             <li><a href=\"me.php\">My Profile</a></li>
             <li><a href=\"photos.php\">Photos</a></li>
             <li><a href=\"blogs.php\">Blogs</a></li>
             <li><a href=\"friends.php\">Friends</a></li>
           </ul>
          </div>
        </div>";
}

?>
