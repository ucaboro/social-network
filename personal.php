<?php include "imports.php";
//Ensures user is logged in before displaying page
checkLoggedIn();?>
<!DOCTYPE html>

<html lang="en-gb">
<?php echo getHtmlForHead(); ?>
<body>
<?php echo getHtmlForTopNavbar(); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">

            <div class="container">
                <div class="row">
                    <div class='col-sm-6'>
                        <div class="form-group">
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' class="form-control" />
                                <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(function () {
                            $('.datetimepicker').datetimepicker();
                        });
                    </script>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class='col-sm-6'>
                        <div class="input-group date" data-provide="datepicker">
                            <input type="text" class="form-control">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                            $('.datepicker').datepicker();
                    </script>
                </div>
            </div>

            <form method="post">
                <div class="form-group"> <!-- Date input -->
                    <label class="control-label" for="date">Date</label>
                    <input class="form-control" id="date" name="date" placeholder="MM/DD/YYY" type="text"/>
                </div>
                <div class="form-group"> <!-- Submit button -->
                    <button class="btn btn-primary " name="submit" type="submit">Submit</button>
                </div>
            </form>
            <script type="text/javascript">
                $(document).ready(function(){
                    var date_input=$('input[name="date"]'); //our date input has the name "date"
                    var options={
                        format: 'mm/dd/yyyy',
                        container: container,
                        todayHighlight: true,
                        autoclose: true,
                    };
                    date_input.datepicker(options);
                })
            </script>

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
