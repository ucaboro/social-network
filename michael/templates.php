<?php


function getHtmlForHead() {
  return "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\" integrity=\"sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u\" crossorigin=\"anonymous\">";
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
?>
