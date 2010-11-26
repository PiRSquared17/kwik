<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title><?php echo $page_pretty?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="icon" href="/kwik/public/favicon.png" type="image/png" />
        <link rel="shortcut icon" href="/kwik/public/favicon.png" type="image/png" />
        <link type="text/css" rel="stylesheet" media="all" href="/kwik/public/kwik.css" />
    </head>
    <body>
        <form action="<?php echo $form?>" method="post">
            <div id="menubg">
                <div id="menu">
                    <h1><?php echo $page_pretty?></h1>
                    <p>powered by kwik</p>
                    <ul>
                        <?php echo $menu?>
                    </ul>
                </div>
            </div>
            <div id="contents">
                <?php echo $yield?>
                <?php wikiformatter($content)?>
            </div>
        </form>
        <?php echo $js?>
    </body>
</html>