<?
require_once 'wikiformatter.php';

$page = $_GET['page'];

if (empty($page)) die;

if (($_SERVER['REQUEST_METHOD'] == 'POST') && array_key_exists('content', $_POST) && array_key_exists('save', $_POST)) {
    echo `touch pages/{$_POST['terms']}`;
    $content = str_replace("\\'", "'", $_POST['content']);
    $content = str_replace('\\"', '"', $content); //debido a magic_quotes_gpc, php escapa las comillas; he de volverlas a su ser
    file_put_contents("pages/$page", $content);
	header('HTTP/1.1 302 Found');
	header("Location: $path/$page");
}

if (array_key_exists('delete', $_POST)) {
    $content = `cd pages; echo $page`;
}

if (array_key_exists('preview', $_POST)) $content = $_POST['content'];
else if (file_exists("pages/$page")) {
    $content = file_get_contents("pages/$page");
} else {
    $content = 'Edite el contenido de la nueva página ' . $page;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Editing <?=$page?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="icon" href="<?=$path?>/res/favicon.png" type="image/png" />
        <link rel="shortcut icon" href="<?=$path?>/res/favicon.png" type="image/png" />
		<link type="text/css" rel="stylesheet" media="all" href="<?=$path?>/res/phiki.css" />
        <script type="text/javascript" src="<?=$path?>/res/jquery.js"></script>
    </head>
    <body>
        <form action="<?=$path?>/<?=$page?>/edit" method="post">
            <div id="menubg">
                <div id="menu">
                    <h1><?=$page?></h1>
                    <p>powered by phiki, <strong>ph</strong>p w<strong>iki</strong></p>
                    <ul>
                        <li><a href="<?=$path?>/">Cancel</a></li>
                        <li><button name="preview" type="submit">Preview</button></li>
                        <li><button name="save" type="submit">Save</button></li>
                        <li><button name="delete" type="submit">Delete</button></li>
                    </ul>
                </div>
            </div>
            <div id="contents">
                <a href="#" class="filaa" title="enlarge box" onclick="$('textarea').attr('rows',$('textarea').attr('rows')+1);return false;">more</a>
                <a href="#" class="filad" title="shrink box" onclick="$('textarea').attr('rows',$('textarea').attr('rows')-1);return false;">less</a>
                <textarea name="content" rows="25" cols="80"><?=$content?></textarea>
                <?wikiformatter($content)?>
            </div>
        </form>
    </body>
</html>
