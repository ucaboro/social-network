<div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="panel-title">Privacy Settings</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-12">
                Who can see my blog posts:

                <div class="row">
                    <div class="col-xs-9">
                        <form id="blog_privacy_form" action="settings.php" method="post">
                            <select class="form-control" name="blog_privacy">
                                <option value="0" <?php if($blogPrivacy == 0){ echo 'selected'; }?> >Everyone</option>
                                <option value="1" <?php if($blogPrivacy == 1){ echo 'selected'; }?> >Friends and friends of friends</option>
                                <option value="2" <?php if($blogPrivacy == 2){ echo 'selected'; }?> >Friends only</option>
                                <option value="3" <?php if($blogPrivacy == 3){ echo 'selected'; }?> >Just me</option>
                            </select>
                        </form>
                    </div>
                    <div class="col-xs-3">
                        <button class="btn btn-primary" type="submit" name="blog_privacy_submit" value="Submit" form="blog_privacy_form">Add</button>
                    </div>
                </div>

                <br>
                <div class="row">
                    <div class="col-xs-9">
                        Who can see my personal info:
                        <form id="info_privacy_form" action="settings.php" method="post">
                            <select class="form-control" name="info_privacy">
                                <option value="0" <?php if($infoPrivacy == 0){ echo 'selected'; }?> >Everyone</option>
                                <option value="1" <?php if($infoPrivacy == 1){ echo 'selected'; }?> >Friends and friends of friends</option>
                                <option value="2" <?php if($infoPrivacy == 2){ echo 'selected'; }?> >Friends only</option>
                                <option value="3" <?php if($infoPrivacy == 3){ echo 'selected'; }?> >Just me</option>
                            </select>
                        </form>
                    </div>
                    <div class="col-xs-3">
                        <br>
                        <button class="btn btn-primary" type="submit" name="info_privacy_submit" value="Submit" form="info_privacy_form">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>