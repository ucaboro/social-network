<?php
include "cls/circle.php";
include "cls/user.php";
include "cls/message.php";

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
        <a class=\"navbar-brand navbar-brand-center\" href=\"#\">
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
 * Returns the HTML for a clickable circle containing some text.
 */
function getHtmlForCircleButton($text, $href) {
  return "<div class=\"circle-container\">
            <a class=\"no-underline\" href=\"$href\"><div class=\"circle\"><div class=\"circle-text\">$text</div></div></a>
          </div>";
}

/*
 * Returns the HTML for a single photo item in the activity feed.
 */
function getHtmlForPhotoFeedItem($user, $time, $photoSrc, $photoID) {
  $photoUrl = "photo.php?p=$photoID";
  echo getHtmlForFeedItem($user, "uploaded a <a href=\"$photoUrl\">photo</a>.", $time, "<a href=\"$photoUrl\"><img src=\"$photoSrc\"></a>");
}

/*
 * Returns the HTML for a new circle message item in the activity feed.
 */
function getHtmlForCircleMessageItem($user, $time, $message) {
  $circleUrl = getUrlToCircle($message->circle->id);
  $circleName = $message->circle->name;
  $messageText = strlen($message->text) > 400 ? substr($message->text, 0, 350) . "... <a href=\"$circleUrl\">Continue reading</a>" : $message->text;
  echo getHtmlForFeedItem($user, "sent a message to <a href=\"$circleUrl\">$circleName</a>.", $time, $messageText);
}

/*
 * Returns the HTML for a single generic item in the activity feed.
 */
function getHtmlForFeedItem($user, $titleHtml, $time, $bodyHtml) {
  $profileUrl = getUrlToProfile($user->id);
  return "<div class=\"panel-body\">
            <div class=\"feed-item\">
              <div>
                <a href=\"$profileUrl\"><img class=\"profile-image\" src=\"$user->photoSrc\"></a>
                <span class=\"feed-item-title\"><a href=\"$profileUrl\">$user->firstName $user->lastName</a> $titleHtml</span><br>
                <span class=\"feed-item-time\">$time</span>
              </div>
              <div class=\"feed-item-content\">
                $bodyHtml
              </div>
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
 * Returns the URL to the profile page for a specific user.
 */
function getUrlToProfile($userID) {
  return "profile.php?u=$userID";
}

?>
