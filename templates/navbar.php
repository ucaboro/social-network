<nav class="navbar navbar-connect">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand navbar-brand-center" href="index.php">
        <img alt="Connect" src="img/logo.png" >
      </a>
    </div>
    <form class="navbar-form navbar-left hidden-xs" action="search.php" method="GET">
      <div class="form-group has-feedback">
        <input class="form-control" placeholder="Search everywhere..." name="search">
        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
      </div>
    </form>
    <div class="navbar-form navbar-right hidden-xs">
      <!-- Split button taken from getbootstrap.com/components -->
      <div class="btn-group">
        <?php
        $user = getUser();
        $url = $user->getUrlToProfile();
        $name = $user->getFullName();
        ?>
        <a type="button" class="btn btn-default" href="<?php echo $url; ?>"><?php echo $name; ?></a>
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="caret"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu">
          <li><a href="profile.php">My profile</a></li>
          <li><a href="settings.php">Settings</a></li>
          <li role="separator" class="divider"></li>
          <li><a href="logout.php">Log out</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>
