<div class="span3" id="panel">

    <?php require '_breadcrumbs.php' ?>

    <div class="well">
        <a class="btn btn-primary" href="/<?php echo $page ?>/edit" accesskey="e" title="Changes this page to edition mode">Edit page</a>
    </div>

    <?php echo wikiformatter($unparsed_content) ?>

</div>

<div class="span9">

    <?php echo $content ?>

</div>
