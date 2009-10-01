<?
require_once 'wikiformatter.php';

$page = $_GET['page'];
if (empty($page)) $page = 'Portada';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?=$page?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="icon" href="<?=$path?>/res/favicon.png" type="image/png" />
        <link rel="shortcut icon" href="<?=$path?>/res/favicon.png" type="image/png" />
		<link type="text/css" rel="stylesheet" media="all" href="<?=$path?>/res/phiki.css" />
		<!--script type="text/javascript" src="<?=$path?>/res/jquery.js"></script-->
    </head>
    <body>
        <div id="menubg">
            <div id="menu">
                <h1><?=$page?></h1>
                <p>powered by phiki, <strong>ph</strong>p w<strong>iki</strong></p>
                <ul>
                    <li><a id="kk" href="<?=$path?>/<?=$page?>/edit">Edit page</a></li>
                    <li><a href="<?=$path?>/<?=$page?>/new">New page</a></li>
                </ul>
            </div>
        </div>
        <div id="contents">
<?

$content = file_get_contents("pages/$page");
wikiformatter($content);

?>
        </div>
    </body>
</html>
