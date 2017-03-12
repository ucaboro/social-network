<?php
require_once "imports.php";
//Ensures user is logged in before displaying page
checkLoggedIn();
$location = "Donny";
if(isset($_POST['blog_privacy_submit']))
{
    echo "holla yo!";
}
if(isset($_POST['dob-submit']))
{
    $date = $_POST['date'];
    echo $_POST['date'];
}
?>
<!DOCTYPE html>
<html lang="en-gb">
<!-- I'm forced to use a earlier version of bootstrap, as apparently the new version doesn't like datepicker or something. No time to work out why.-->
<script src="bootstrap-3.3.7-dist/js/jquery-3.1.1.min.js" crossorigin="anonymous"></script>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script type='text/javascript' src='//code.jquery.com/jquery-1.8.3.js'></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker3.min.css">
<script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.min.js"></script>
<script type='text/javascript'>
    $(function(){
        $('.input-group.date').datepicker({
            calendarWeeks: true,
            todayHighlight: true,
            autoclose: true
        });
    });
</script>
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
                                            <button class="btn btn-primary" type="submit" name="location-submit" value="Submit" form="location-form">Submit</button>
                                        </div>
                                    </div>
                                    <div>
                                        <form action="settings.php" method="post">
                                            <div class="row">
                                                <div class="col-xs-10">
                                                    <div class="form-group ">
                                                        <div class="input-group date">
                                                            <input type="text" name="date" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-2">
                                                    <div class="form-group">
                                                        <div>
                                                            <button class="btn btn-primary" name="dob-submit" type="submit" value="Submit">Submit</button>
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

                    <?php echo getHtmlFromPHPFile("templates/accountSettings.php")?>

                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-4">
            <div class="row">
                <div class="col-xs-12">
                    <?php echo getHtmlForNavigationPanel(); ?>
                    <!--Html and php for the privary settings box-->
                    <?php include "templates/privacySettings.php"; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type='text/javascript'> //Degree of duplication here, can resolve later
    $(document).ready(function(){
        var date_input=$('input[name="date"]'); //our date input has the name "date"
        var options={
            format: 'mm/dd/yyyy',
            todayHighlight: true,
            autoclose: true,
        };
        date_input.datepicker(options);
</script>
</body>
</html>