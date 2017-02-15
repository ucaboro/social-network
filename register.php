<?php include "imports.php"; ?>
<!DOCTYPE html>

<html lang="en-gb">
  <?php echo getHtmlForHead(); ?>
  <body>
    <?php echo getHtmlForTopNavbar(); ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-offset-3 col-sm-6 col-xs-12">
          <div class="row">
            <div class="col-xs-12">
              <h1>Register</h1>
              <h4>Fill in your details below</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <form id="registration-form" action="success.php" method="POST">
                <div class="form-group">
                  <input type="text" class="form-control" name="first-name" placeholder="First name">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" name="last-name" placeholder="Last name">
                </div>
                <div class="form-group">
                  <input type="email" class="form-control" name="email" placeholder="Email">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control" name="password" placeholder="Password">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control" name="confirm-password" placeholder="Confirm password">
                </div>
              </form>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <button class="btn btn-primary" type="submit" form="registration-form">Register</button>
            </div>
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
  </body>
</html>
