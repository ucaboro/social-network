<?php include "imports.php";
//Ensures user is logged in before displaying page
checkLoggedIn();
$email = "B@G.com"; //Default value
$lastName = "Grock";
$firstName = "Brian";
$location = "Donny";
?>

<!DOCTYPE html>

<html lang="en-gb" xmlns="http://www.w3.org/1999/html">
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
                                            Where do you live?
                                            <form id="location-form" action="settings.php" method="POST">
                                                <div class="form-group">
                                                    <input class="form-control" name="location" value="<?php echo htmlspecialchars($location)?>">
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-xs-2">
                                            <br>
                                            <button class="btn btn-primary" type="submit" name="location-submit" value="Submit" form="location-form">Change</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-10">
                                            When were you born?
                                            <form id="dob-form" action="settings.php" method="POST">
                                                <div class="form-group">
                                                    <input class="form-control" name="dob" value="">
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-xs-2">
                                            <br>
                                            <button class="btn btn-primary" type="submit" name="dob-submit" value="Submit" form="dob-form">Change</button>
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
                                    <div class="row">
                                        <div class="col-xs-10">
                                            Last Name:
                                            <form id="last-name-form" action="settings.php" method="POST">
                                                <div class="form-group">
                                                    <input class="form-control" name="last-name" value="<?php echo htmlspecialchars($lastName)?>">
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-xs-2">
                                            <br>
                                            <button class="btn btn-primary" type="submit" name="last-name-submit" value="Submit" form="last-name-form">Change</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-10">
                                            Email:
                                            <form id="email-form" action="settings.php" method="POST">
                                                <div class="form-group">
                                                    <input class="form-control" name="email" value="<?php echo htmlspecialchars($email)?>">
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-xs-2">
                                            <br>
                                            <button class="btn btn-primary" type="submit" name="email-submit" value="Submit" form="email-form">Change</button>
                                        </div>
                                    </div>
                                    <h5>Change Password:</h5>
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
                    <!-- Bootstrap Date-Picker Plugin -->
                    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
                    <?php echo getHtmlForNavigationPanel(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo getHtmlForJavascriptImports(); ?>
</body>
</html>