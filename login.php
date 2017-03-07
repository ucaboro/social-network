<?php
    //Include functions file
    require_once("funct.php");
    //
    require_once "imports.php";
    $error = false;
    if(isset($_POST['submit']))
    {
        //form has been submitted
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        //$message = "Logging in : {$email}";
        if(checkLogin($email, $password)){
            redirectTo("index.php");
        }
        else{
            $error = true;
        }
    }
    else{
        $email = "";
    }

?>

<!DOCTYPE html>
<html lang="en">
    <?php echo getHtmlForHead(); ?>
    <body>
        <?php echo getHtmlForTopNavbar(); ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-offset-3 col-sm-6 col-xs-12">
                    <div class="row">
                        <div class="col-xs-12">
                            <h1>Login</h1>
                            <h4>Enter your email and password below</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <br />
                            <form id="login-form" action="login.php" method="post">
                                <div class="form-group">
                                    Email: <input type="text" class="form-control" name="email" value ="<?php echo htmlspecialchars($email) ?>" /><br/>
                                </div>
                                <div class="form-group">
                                    Password: <input type="password" class="form-control" name="password" value=""/><br/>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class=""col-xs-12">
                            <button class="btn btn-primary" type="submit" name="submit" value="Submit" form="login-form">Log In</button>
                        </div>
                    </div>
                    <?php
                        if($error){
                            echo "<div class=\"alert alert-danger\" role=\"alert\">Login Unsuccessful.</div>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
