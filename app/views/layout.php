<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $page_pretty?></title>
        <meta charset="UTF-8">
        <link rel="icon" href="/kwik/public/favicon.png" type="image/png">
        <link rel="shortcut icon" href="/kwik/public/favicon.png" type="image/png">
        <link rel="stylesheet" media="all" href="/kwik/public/kwik.css">
    </head>
    <body>
        <form <?php echo $form_for?>>
            <div id="menubg">
                <div id="menu">
                    <h1><?php echo $page_pretty?></h1>
                    <p>powered by kwik</p>
                    <ul>
                        <?php require_once "app/views/$controller.menu.php"?>
                    </ul>
                </div>
            </div>
            <div id="contents">
                <?php echo $yield?>
                <?php wikiformatter($content)?>
            </div>
        </form>
        <script src="/kwik/public/jquery-1.3.2.min.js"></script>
        <script src="/kwik/public/kwik.js"></script>
    </body>
</html>
