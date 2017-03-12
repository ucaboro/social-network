
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
                        <form id="first_name_form" action="settings.php" method="POST">
                            <div class="form-group">
                                <input class="form-control" name="first_name" value="<?php echo htmlspecialchars($firstName)?>">
                            </div>
                        </form>
                    </div>
                    <div class="col-xs-2">
                        <br>
                        <button class="btn btn-primary" type="submit" name="first_name_submit" value="Submit" form="first_name_form">Change</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-10">
                        Last Name:
                        <form id="last_name_form" action="settings.php" method="POST">
                            <div class="form-group">
                                <input class="form-control" name="last_name" value="<?php echo htmlspecialchars($lastName)?>">
                            </div>
                        </form>
                    </div>
                    <div class="col-xs-2">
                        <br>
                        <button class="btn btn-primary" type="submit" name="last_name_submit" value="Submit" form="last_name_form">Change</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-10">
                        Email:
                        <form id="email_form" action="settings.php" method="POST">
                            <div class="form-group">
                                <input class="form-control" name="email" value="<?php echo htmlspecialchars($email)?>">
                            </div>
                        </form>
                    </div>
                    <div class="col-xs-2">
                        <br>
                        <button class="btn btn-primary" type="submit" name="email_submit" value="Submit" form="email_form">Change</button>
                    </div>
                </div>
                <h5>Change Password:</h5>
                <form id="password_form" action="settings.php" method="POST">
                    <div class="form-group">
                        <input class="form-control" name="old_password" placeholder="Old Password">
                    </div>
                    <div class="form-group">
                        <input class="form-control" name="new_password" placeholder="New Password">
                    </div>
                    <div class="form-group">
                        <input class="form-control" name="confirm_new_password" placeholder="Confirm New Password">
                    </div>
                </form>
                <div class="row">
                    <div class="col-xs-12">
                        <button class="btn btn-primary" type="submit" name="password_submit" value="Submit" form="password_form">Change Password</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>