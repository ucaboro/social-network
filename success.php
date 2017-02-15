<?php include "imports.php"; ?>
<!DOCTYPE html>

<html lang="en-gb">
  <?php echo getHtmlForHead(); ?>
  <body>
    <?php echo getHtmlForTopNavbar(); ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-offset-3 col-sm-6 col-xs-12">
          <?php
          // Get the variables passed through POST
          $firstName = $_POST["first-name"]; // using the 'name' attribute in the <input> tags on register.php
          $lastName = $_POST["last-name"];
          $email = $_POST["email"];
          $password = $_POST["password"];
          $confirmPassword = $_POST["confirm-password"];

          // TODO: Do stuff with variables here, e.g. write to database

          // Just outputting for now to prove it works
          echo "First name: $firstName<br>
                Last name: $lastName<br>
                Email: $email<br>
                Password: $password<br>
                Confirm password: $confirmPassword";
          ?>
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
