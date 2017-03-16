<?php
    require_once "imports.php";
    //Ensures user is logged in before displaying page
    checkLoggedIn();
    include "templates/settingsPhp.php";
?>
<!DOCTYPE html>
<html lang="en-gb">
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
                    <!--Html for interests box -->
                    <?php include "templates/interestsSettings.php"; ?>
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
                    <!--Html and php for the personal settings box-->
                    <?php include "templates/personalSettings.php"; ?>
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
