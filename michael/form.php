<?php
    //Include database connection
    require_once("db.php");

    //Include functions file
    require_once("funct.php");
    if(isset($_POST['submit']))
    {
        //form has been submitted
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        //$message = "Logging in : {$email}";
        if(checkLogin($email, $password)){
            $message = "Logging in";
            //redirectTo("homepage.php");
        }
        else{
            $message = "Incorrect email password pair";
        }
    }
    else{
        $email = "";
        $message = "Please log in";
    }

?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Title</title>
    </head>
    <body>
    <?php
    echo $message;
    ?><br />
    <form action="form.php" method="post">
        Email: <input type="text" name="email" value ="<?php echo htmlspecialchars($email) ?>" /><br/>
        Password: <input type="password" name="password" value=""/><br/>
        <br />
        <input type="submit" name="submit" value="Submit" />
    </form>

    </body>
    </html>
