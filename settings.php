<?php include "imports.php";
//Ensures user is logged in before displaying page
checkLoggedIn();
$email = "B@G.com"; //Default value
$lastName = "Grock";
$firstName = "Brian";
?>

<!DOCTYPE html>

<html lang="en-gb">
<?php echo getHtmlForHead(); ?>
<body>
<?php echo getHtmlForTopNavbar(); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-md-8">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">Personal Settings</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-10">
                                            First Name:
                                            <form id="first-name-form" action="settings.php" method="POST">
                                                <div class="form-group">
                                                    <input class="form-control" name="first-name" value="<?php echo htmlspecialchars($firstName)?>">
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-xs-2">
                                            <br>
                                            <button class="btn btn-primary" type="submit" name="first-name-submit" value="Submit" form="first-name-form">Change</button>
                                        </div>
                                    </div>
                                    Last Name:
                                    <form id="last-name-form" action="settings.php" method="POST">
                                        <div class="form-group">
                                            <input class="form-control" name="last-name" value="<?php echo htmlspecialchars($lastName)?>">
                                        </div>
                                    </form>
                                    Email:
                                    <form id="email-form" action="settings.php" method="POST">
                                        <div class="form-group">
                                            <input class="form-control" name="email" value="<?php echo htmlspecialchars($email)?>">
                                        </div>
                                    </form>
                                    <h4>Change Password:</h4>
                                    <form id="password-form" action="settings.php" method="POST">
                                        <div class="form-group">
                                            <input class="form-control" name="old-password" placeholder="Old Password">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="new-password" placeholder="New Password">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="confirm-new-password" placeholder="Confirm New Password">
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <button class="btn btn-primary" type="submit" name="password-submit" value="Submit" form="password-form">Change Password</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">Account Settings</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
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
        <div class="col-xs-12 col-md-4">
            <div class="row">
                <div class="col-xs-12">
                    <?php echo getHtmlForNavigationPanel(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo getHtmlForJavascriptImports(); ?>
</body>
</html>