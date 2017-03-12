<div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="panel-title">Privacy Settings</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <h5>Who can see my blog posts:</h5>

            <form action="settings.php" method="post">
                <select class="form-control" name="blog_privacy">
                    <option value="0" selected>Everyone</option>
                    <option value="1">Friends and friends of friends</option>
                    <option value="2">Friends only</option>
                    <option value="3">Just me</option>
                </select>
                <input type="submit" name="blog_privacy_submit" value="Submitted"/>
            </form>

            <h5>Who can see my personal info:</h5>
            <form action="settings.php" method="post">
                <select class="form-control" name="info_privacy">
                    <option selected>Everyone</option>
                    <option>Friends and friends of friends</option>
                    <option>Friends only</option>
                    <option>Just me</option>
                </select>
                <input type="submit" name="info_privacy_submit" value="Submitted" />
            </form>

        </div>
    </div>
</div>