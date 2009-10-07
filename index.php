<?
require_once 'wikiformatter.php';

//TODO limpiar $_POST['terms'] para prevenir inyecciones

if (($_SERVER['REQUEST_METHOD'] == 'POST') && array_key_exists('new', $_POST)) {
	header('HTTP/1.1 302 Found');
	header("Location: $path/{$_POST['terms']}/edit");
}

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
    </head>
    <body>
        <form action="<?=$path?>/" method="post">
            <div id="menubg">
                <div id="menu">
                    <h1><?=$page?></h1>
                    <p>powered by phiki, <strong>ph</strong>p w<strong>iki</strong></p>
                    <ul>
                        <li><input type="text" name="terms" value="<?=(!file_exists("pages/$page"))?$page:$_POST['terms']?>" /><button name="search" type="submit">Search</button> <button name="new" type="submit">Create</button></li>
                        <li><a href="<?=$path?>/Todas">All pages</a></li>
                        <li><a href="<?=$path?>/<?=$page?>/edit">Edit page</a></li>
                    </ul>
                </div>
            </div>
        </form>
            <div id="contents">
<?
if (($_SERVER['REQUEST_METHOD'] == 'POST') && array_key_exists('search', $_POST)) {
    $search = `cd pages; grep {$_POST['terms']} *`;
    $content = "==Resultados de la búsqueda==\n";
    $el_ant = '';
    foreach (explode("\n", $search) as $l) {
        $el = explode(':', $l, 2);
        if (count($el) > 1) {
            if ($el_ant != $el[0]) {
                $content .= "*[[{$el[0]}]]\n";
            }
            $content .= " {$el[1]} \n";
            $el_ant = $el[0];
        }
    }
    wikiformatter($content);
} else {
    if ($page=='Todas') {
        $content = "==Todas las páginas==\n";
        if ($h = opendir('pages')) {
            while (false !== ($f = readdir($h))) {
                if ($f != '.' && $f != '..') {
                    $content .= "*[[$f]]\n";
                }
            }
            closedir($h);
        }
        wikiformatter($content);
    } else {
        if (file_exists("pages/$page")) {
            $content = file_get_contents("pages/$page");
            wikiformatter($content);
        } else {
            echo 'La página no existe. Pulse sobre el enlace superior para crearla.';
        }
    }
}
?>
        </div>
    </body>
</html>
