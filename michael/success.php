<?php
    require_once "imports.php";
    require_once "db.php";
?>
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
          $firstName = trim($_POST["first-name"]); // using the 'name' attribute in the <input> tags on register.php
          $lastName = trim($_POST["last-name"]);
          $email = trim($_POST["email"]);
          $password = trim($_POST["password"]);
          $confirmPassword = trim($_POST["confirm-password"]);

          // TODO: VALDIATION

          //Assuming validation was fine:
          //$query = "INSERT INTO User (email, firstName, lastName, password) VALUES (\"batman123@gmail.com\",\"Charlie\", \"Kaufmann\", \"Brucey\")";
          // 2. Perform database query
          $query  = "INSERT INTO User ";
          $query .= "  (email, firstName, lastName, password)";
          $query .= " VALUES ";
          $query .= "('{$email}', '{$firstName}', '{$lastName}', '{$password}')";

          $result = mysqli_query($connection, $query);

          if (!$result) {
              printf("Errormessage: %s\n", mysqli_error($connection));
              die("Database query failed.");
          }
          else{
              echo "PASSPASSPASS";
          }

          displayUsers($connection);
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

<?php
mysqli_close($connection);
?>