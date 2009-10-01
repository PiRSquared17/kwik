<?
require_once 'wikiformatter.php';

$page = $_GET['page'];

if (empty($page)) die;

if (($_SERVER['REQUEST_METHOD'] == 'POST') && array_key_exists('content', $_POST) && array_key_exists('save', $_POST)) {
    file_put_contents("pages/$page", $_POST['content']);
	header('HTTP/1.1 302 Found');
	header("Location: /$page");
}

//if (array_key_exists('delete', $_POST))

if (array_key_exists('preview', $_POST)) $content = $_POST['content'];
else $content = file_get_contents("pages/$page");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Editing <?=$page?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="icon" href="<?=$path?>/res/favicon.png" type="image/png" />
        <link rel="shortcut icon" href="<?=$path?>/res/favicon.png" type="image/png" />
		<link type="text/css" rel="stylesheet" media="all" href="<?=$path?>/res/phiki.css" />
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
        <form action="<?=$path?>/<?=$page?>/edit" method="post">
            <div id="contents">
                <ul id="menu">
                    <li><button name="preview" type="submit">Preview</button></li>
                    <li><button name="save" type="submit">Save</button></li>
                    <li><button name="delete" type="submit">Delete</button></li>
                </ul>
                <textarea name="content" rows="25" cols="80"><?=$content?></textarea>
                <a href="#" class="filaa" title="enlarge box" onclick="$('textarea').attr('rows',$('textarea').attr('rows')+1);return false;">more</a>
                <a href="#" class="filad" title="shrink box" onclick="$('textarea').attr('rows',$('textarea').attr('rows')-1);return false;">less</a>
                <hr />
                <?wikiformatter($content)?>
            </div>
        </form>
    </body>
</html>
