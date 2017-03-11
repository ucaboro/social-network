<?php
require_once "imports.php";
if(isset($_POST['submit']))
{
    $date = $_POST['date'];
    //echo $_POST['date'];
}
else{

}

?>
<!DOCTYPE html>
<html lang="en-gb">
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
    <div class="container">

        <form action="dateForm.php" method="post">
            <div class="form-group ">
                <div class="input-group date">
                    <input type="text" name="date" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <button class="btn btn-primary" name="submit" type="submit" value="Submit">Submit</button>
                </div>
            </div>
        </form>

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
