<?php
    require_once "funct.php";
    require_once "validation.php";
    require_once "db.php";

    //////////If form submitted
    if(isset($_POST['submit']))
    {
        //Retrieve values from post and trim whitespace
        $firstName = trim($_POST["first-name"]); // using the 'name' attribute in the <input> tags on register.php
        $lastName = trim($_POST["last-name"]);
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);
        $confirmPassword = trim($_POST["confirm-password"]);

        $fields = array("First Name" => $firstName, "Surname" => $lastName, "Email" => $email, "Password" => $password, "Confirm Password" => $confirmPassword);
        /////////VALIDATION//////
        //Array of strings, where each element is a warning for a given error, i.e. a sentence specifying the error
        $errors = array();
        $presenceErrors = array();
        //Presence
        foreach ($fields as $key => $field) {
            if (!presence($field)) {
                //Append error to errors array
                $presenceErrors[$key] = false;
                $errors[] = $key . " field is empty, required fields cannot be left blank. ";
            }
        }

        //Email
        //Only test for further errors if field is non-empty //Better to do with with !presence? or some other way?
        if (!isset($presenceErrors["Email"])) {
            //Invalid email address
            if (!validEmail($email)) {
                $errors[] = "Please provide a valid email address.";
            }
            //
            if (!uniqueEmail($email)) {
                $errors[] = "There already exists and account for this email address";
            }
        }
        //Names
        /*if (!isset($presenceErrors["First Name"])) {

        }
        if (!isset($presenceErrors["Surame"])) {

        }*/
        //Password
        //Only test for further errors if field is non-empty
        if (!isset($presenceErrors["Password"]) && !isset($presenceErrors["Confirm Password"]))
        {
            //Password matching
            if(!matching($password, $confirmPassword)){
                $errors[] = "Passwords do not match.";
            }
            //Security
            $length = 7;
            if(!minLength($password, $length) || !specialChars($password)){
                $errors[] = "Passwords must be at least " . $length . " characters long and contain at least one non-alphabetical character, i.e. a number of symbol.";
            }

        }

        //If no validation errors, upload to database //Should really do email confirmation or something
        if(empty($errors))
        {
            register($firstName, $lastName, $email, $password);
        }
        else{
            echo "Registration unsuccessful <br>";
            print_r($errors);
        }


    }

    require_once "imports.php"; // I don't know what's in here so i put it here, in case there is anything that is anything sent that would stop the redirect
?>
<!DOCTYPE html>

<html lang="en-gb">
  <?php echo getHtmlForHead(); ?>
  <body>
    <?php echo getHtmlForTopNavbar(); ?>
    <?php

    ?>
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
              <form id="registration-form" action="register_one_page.php" method="POST">
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
              <button class="btn btn-primary" type="submit" name="submit" value="Submit" form="registration-form">Register</button>
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