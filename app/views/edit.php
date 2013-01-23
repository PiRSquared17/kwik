<form action="/<?php echo $page ?>/edit" method="post">
    <div class="span3" id="panel">

        <?php require '_breadcrumbs.php' ?>

        <input type="hidden" name="_method" value="put">

        <div class="well clearfix">
            <div class="pull-left">
                <button class="btn btn-primary" name="save" type="submit" accesskey="s" title="Saves changes to this page [Ctrl+S]">Save</button>
                <label class="checkbox"><input type="checkbox" name="preview" id="check_box" accesskey="p" title="Click to preview page instead of definitely saving" value="1"> Preview changes</label>
            </div>

            <div class="pull-right">
                <a class="btn" href="/<?php echo $page ?>" title="Cancels page edition">Cancel</a>
                <button class="btn btn-danger" name="delete" type="submit" title="Deletes current page from disk, forever">Delete</button>
            </div>
        </div>

        <?php echo wikiformatter($unparsed_content) ?>

    </div>

    <div class="span9">

        <textarea name="content" rows="25" class="prettyprint"><?php echo $unparsed_content ?></textarea>

        <?php echo $content ?>

    </div>
</form>