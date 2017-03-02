<?php
    //Include database connection
    require_once("db.php");

    //Include functions file
    require_once("funct.php");
    if(isset($_POST['submit']))
    {
        //form has been submitted
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        //$message = "Logging in : {$username}";
        if(checkLogin($username, $password, $connection)){
            $message = "Logging in!";
            //redirectTo("homepage.php");
        }
        else{
            $message = "Incorrect username password pair";
        }
    }
    else{
        $username = "";
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
        Email: <input type="text" name="username" value ="<?php echo htmlspecialchars($username) ?>" /><br/>
        Password: <input type="password" name="password" value=""/><br/>
        <br />
        <input type="submit" name="submit" value="Submit" />
    </form>

    </body>
    </html>


<?php
mysqli_close($connection);
?>