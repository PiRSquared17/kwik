<?
$page = $_GET['page'];

if (strpos($page, ' ') !== false) { //la página solicitada tiene espacios
    header('HTTP/1.1 302 Found');
    header('Location: ' . str_replace('%20', '_', $_SERVER['REQUEST_URI']));
    die;
}

require_once 'wikiformatter.php';

if ($page == 'Todas') { //impide editar Todas, pues es una página especial
	header('HTTP/1.1 302 Found');
	header("Location: $path/$page");
	die;
}

if (empty($page)) die;

if (array_key_exists('content', $_POST) && array_key_exists('save', $_POST)) {
    $content = $_POST['content']; //no asumo register globals
    if (get_magic_quotes_gpc()) { //no nos interesa el magic_quotes_gpc y no es desactivable en ejecución
        $content = stripslashes($content);
    }
    file_put_contents("pages/$page", $content);
	header('HTTP/1.1 302 Found');
	header("Location: $path/$page");
	die;
}

if (array_key_exists('delete', $_POST)) {
    if ($page != 'Portada') { //impide borrar portada
        `cd pages; rm $page`;
        header('HTTP/1.1 302 Found');
        header("Location: $path/");
        die;
    }
}

if (array_key_exists('preview', $_POST)) $content = $_POST['content'];
else if (file_exists("pages/$page")) { //si no es preview ni guardar, es que quiero ver el contenido del fichero
    $content = file_get_contents("pages/$page");
} else {
    $content = 'Edite el contenido de la nueva página ' . $page;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Editing <?=str_replace('_', ' ', $page)?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="icon" href="<?=$path?>/res/favicon.png" type="image/png" />
        <link rel="shortcut icon" href="<?=$path?>/res/favicon.png" type="image/png" />
		<link type="text/css" rel="stylesheet" media="all" href="<?=$path?>/res/kwik.css" />
    </head>
    <body>
        <form action="<?=$path?>/<?=$page?>/edit" method="post">
            <div id="menubg">
                <div id="menu">
                    <h1><?=str_replace('_', ' ', $page)?></h1>
                    <p>powered by kwik</p>
                    <ul>
                        <li><a href="<?=$path?>/" title="Cancels page edition">Cancel</a></li>
                        <li><button name="preview" type="submit" title="Page preview, without saving">Preview</button></li>
                        <li><button name="save" type="submit" title="Saves changes to this page">Save</button></li>
                        <li><button name="delete" type="submit" title="Deletes current page from disk, forever">Delete</button></li>
                    </ul>
                </div>
            </div>
            <div id="contents">
                <span class="resizer" id="filaa" title="keep pressing to enlarge textbox">more</span>
                <span class="resizer" id="filad" title="keep pressing to shrink textbox">less</span>
                <textarea name="content" rows="25" cols="80" class="prettyprint"><?=$content?></textarea>
                <?wikiformatter($content)?>
            </div>
        </form>
        <script type="text/javascript" src="<?=$path?>/res/jquery.js"></script>
        <script type="text/javascript">
        <!--
            var add = 0;
            $('.resizer').mousedown(function(){
                if ($(this).attr('id') == 'filaa') add = 1;
                else add = -1;
                resizer();
            }).mouseup(function(){
                add = 0;
            });
            function resizer() {
                $('textarea').attr('rows',$('textarea').attr('rows') + add);
                if (add != 0) setTimeout(resizer, 30);
            }
            
            $('button[name=delete]').click(function(){
                return confirm('This will delete <?=$page?>. Are you sure?');
            });
        -->
        </script>
    </body>
</html>
