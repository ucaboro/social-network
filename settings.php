<?php include "imports.php";
//Ensures user is logged in before displaying page
checkLoggedIn();
$email = ""; //Default value
$lastName = "Brian";
$firstName = "Grock";
?>

<!DOCTYPE html>

<html lang="en-gb">
<?php echo getHtmlForHead(); ?>
<body>
<?php echo getHtmlForTopNavbar(); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">Personal Settings</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">Account Settings</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <form id="registration-form" action="register_one_page.php" method="POST">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="first-name" value="<?php echo htmlspecialchars($firstName)?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="last-name" value="<?php echo htmlspecialchars($lastName)?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($lastName)?>">
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


                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">Privacy Settings</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-xs-12">
                    <?php echo getHtmlForNavigationPanel(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php echo getHtmlForJavascriptImports(); ?>
</body>
</html>