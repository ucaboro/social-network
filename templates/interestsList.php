<?php
$interestsArray = $user -> getInterests();
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="panel-title">Interests</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-12">
                <ul>
                    <?php
                    foreach($interestsArray as $interest){
                        echo "<li>" . $interest -> getName();
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>