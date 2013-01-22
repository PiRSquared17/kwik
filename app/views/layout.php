<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $page_pretty ?></title>
        <meta charset="UTF-8">
        <link rel="stylesheet" media="all" href="/kwik/public/kwik.css">
    </head>
    <body>
        <form <?php echo $form_for ?>>
            <div class="navbar navbar-fixed-top">
                <div class="navbar-inner">
                    <div class="container-fluid">
                        <a class="brand" href="#"><?php echo $page_pretty ?></a>
                        <ul class="nav pull-right">
                            <li class="divider-vertical"></li>
                            <li class="navbar-form">
                                <input type="text" name="terms" id="text_field" accesskey="f" value="<?php echo $terms ?>" title="Search or create a term [Ctrl+K]">
                                <button class="btn btn-primary" name="search" type="submit" title="Searches for the term in existing pages">Search</button>
                                <button class="btn" name="new" type="button" title="Creates a page with the specified term, or leads to the page if already exists">Create</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row-fluid">
                    <?php echo $yield ?>
                </div>
            </div>
        </form>
        <script src="/kwik/public/jquery-1.3.2.min.js"></script>
        <script src="/kwik/public/kwik.js"></script>
    </body>
</html>
