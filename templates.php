<?php

/* Gets the HTML for the head tag for every page */
function getHtmlForHead() {
  return "<head>
    <meta charset=\"utf-8\">
  	<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
  	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
  	<title>Connect - the social network</title>

  	<!-- Bootstrap CSS -->
  	<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\" integrity=\"sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u\" crossorigin=\"anonymous\">

  	<!-- Custom CSS -->
  	<link href=\"style.css\" rel=\"stylesheet\">
  </head>";
}

/*
 * Gets the HTML for the navbar at the top of every page.
 */
function getHtmlForTopNavbar() {
  return "<nav class=\"navbar navbar-connect\">
    <div class=\"container-fluid\">
      <div class=\"navbar-header\">
        <a class=\"navbar-brand navbar-brand-center\" href=\"index.php\">
          <img alt=\"Connect\" src=\"img/logo.png\" >
        </a>
      </div>
      <form class=\"navbar-form navbar-left\">
        <div class=\"form-group has-feedback\">
          <input class=\"form-control\" placeholder=\"Search everywhere...\">
          <span class=\"glyphicon glyphicon-search form-control-feedback\" aria-hidden=\"true\"></span>
        </div>
      </form>
      <div class=\"navbar-form navbar-right\">
        <!-- Split button taken from getbootstrap.com/components -->
        <div class=\"btn-group\">
          <button type=\"button\" class=\"btn btn-default\">Steve Smith</button>
          <button type=\"button\" class=\"btn btn-default dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
            <span class=\"caret\"></span>
            <span class=\"sr-only\">Toggle Dropdown</span>
          </button>
          <ul class=\"dropdown-menu\">
            <li><a href=\"#\">My profile</a></li>
            <li><a href=\"#\">Settings</a></li>
            <li role=\"separator\" class=\"divider\"></li>
            <li><a href=\"#\">Log out</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>";
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
  $user = $message->user;
  $name = $user->getFullName();
  $profileUrl = $user->getUrlToProfile();
  $img = getHtmlForSquareImage($user->photoSrc);
  $time = $message->time->format("d M Y H:i");
  return "<div class=\"message-container\">
            <div>
              <div class=\"feed-profile-image\"><a href=\"$profileUrl\">$img</a></div>
            </div>
            <div class=\"message-content\">
              <span class=\"feed-item-title\"><a href=\"$profileUrl\">$name</a></span>
              <span class=\"feed-item-time\">$time</span><br>
              $message->text
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

?>
