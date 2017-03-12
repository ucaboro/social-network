<?php
    require_once "imports.php";
    //Ensures user is logged in before displaying page
    checkLoggedIn();
    include "templates/settingsPhp.php";
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
<!--Body-->
<div class="container-fluid">
    <div class="row">
        <!-- Left column -->
        <div class="col-xs-12 col-md-8">
            <div class="row">
                <div class="col-xs-12">
                    <!--Html and php for the personal settings box-->
                    <?php include "templates/personalSettings.php"; ?>
                    <!--Html for account settings box-->
                    <?php include "templates/accountSettings.php"; ?>
                </div>
            </div>
        </div>
        <!--Right column-->
        <div class="col-xs-12 col-md-4">
            <div class="row">
                <div class="col-xs-12">
                    <!--Navigation Box-->
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