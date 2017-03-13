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
                        <form id="location_form" action="settings.php" method="POST">
                            <div class="form-group">
                                <input class="form-control" name="location" value="<?php echo htmlspecialchars($location)?>">
                            </div>
                        </form>
                    </div>
                    <div class="col-xs-2">
                        <br>
                        <button class="btn btn-primary" type="submit" name="location_submit" value="Submit" form="location_form">Submit</button>
                    </div>
                </div>
                <div>
                    When were you born?
                    <form action="settings.php" method="post">
                        <div class="row">
                            <div class="col-xs-10">
                                <div class="form-group ">
                                    <div class="input-group date">
                                        <input type="text" name="dob" class="form-control" value="<?php echo htmlspecialchars($dob)?>"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <div>
                                        <button class="btn btn-primary" name="dob_submit" type="submit" value="Submit">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>