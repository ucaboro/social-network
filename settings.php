<?php
    require_once "imports.php";
    //Ensures user is logged in before displaying page
    checkLoggedIn();
    //Defaults
    $dob = "";
    $location = "";
    $firstName = "Harry";
    $lastName = "Harpsicord";
    $email = "h@H.com";
    /*Post part for personal settings*/
    if(isset($_POST['dob_submit']))
    {
        echo $_POST['dob'];
        $dob = $_POST['dob'];
    }
    if(isset($_POST['location_submit'])){
        echo $_POST['location'];
        $location = $_POST['location'];
    }
    //Account part
    if(isset($_POST['first_name_submit'])){
        echo $_POST['first_name'];
        $firstName = $_POST['first_name'];
    }
    if(isset($_POST['last_name_submit'])){
        echo $_POST['last_name'];
        $lastName = $_POST['last_name'];
    }
    if(isset($_POST['email_submit'])){
        echo $_POST['email'];
        $email = $_POST['email'];
    }
    if(isset($_POST['password_submit'])){
        echo $_POST['password'];
    }
    //Privacy part
    if(isset($_POST['blog_privacy_submit']))
    {
        echo $_POST['blog_privacy'];
    }
    if(isset($_POST['info_privacy_submit']))
    {
        echo $_POST['info_privacy'];
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