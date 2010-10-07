<?
/*
Copyright (c) 2009 Daniel Cruz Horts

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

require_once 'wikiformatter.php';
//TODO clean up $_POST['terms'] to prevent injection

if (array_key_exists('new', $_POST)) {
	header('HTTP/1.1 302 Found');
	header("Location: $path/{$_POST['terms']}/edit");
	die;
}

$page = $_GET['page'];
if (empty($page)) $page = 'Main_page';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?=str_replace('_', ' ', $page)?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="icon" href="<?=$path?>/res/favicon.png" type="image/png" />
        <link rel="shortcut icon" href="<?=$path?>/res/favicon.png" type="image/png" />
		<link type="text/css" rel="stylesheet" media="all" href="<?=$path?>/res/kwik.css" />
    </head>
    <body>
        <form action="<?=$path?>/" method="post">
            <div id="menubg">
                <div id="menu">
                    <h1><?=str_replace('_', ' ', $page)?></h1>
                    <p>powered by kwik</p>
                    <ul>
                        <li><input type="text" name="terms" accesskey="f" value="<?=(!file_exists("pages/$page"))?$page:$_POST['terms']?>" /><button name="search" type="submit" title="Searches for the term in existing pages">Search</button> <button name="new" type="submit" title="Creates a page with the specified name, or leads to the page if already exists">Create</button></li>
                        <li><a href="<?=$path?>/All" accesskey="q" title="Lists all pages this wiki stores">All pages</a></li>
                        <li><a href="<?=$path?>/<?=$page?>/edit" accesskey="e" title="Changes this page to edition mode">Edit page</a></li>
                    </ul>
                </div>
            </div>
        </form>
        <div id="contents">
<?
if (array_key_exists('search', $_POST)) {
    $content = "==Search results==\n";
    $content .= "===Page name matches===\n";
    $search = `cd pages; ls`;
    foreach (explode("\n", $search) as $l) {
        if (strpos(strtolower($l), strtolower($_POST['terms'])) !== false)
            $content .= "*[[$l]]\n";
    }

    $content .= "\n===Page content matches===\n";
    $search = `cd pages; grep {$_POST['terms']} *`;
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
    if ($page == 'All') {
        $content = "==All pages==\n";
        $pages = array();
        if ($h = @opendir('pages')) {
            while (false !== ($f = readdir($h))) {
                if ($f != '.' && $f != '..') {
                    $pages[] = $f;
                }
            }
            closedir($h);
        } else @mkdir('pages');
        natsort($pages);
        foreach ($pages as $p) {
            $content .= "*[[$p]]\n";
        }
        wikiformatter($content);
    } else {
        if (file_exists("pages/$page")) {
            $content = file_get_contents("pages/$page");
            wikiformatter($content);
        } else {
            echo "Page doesn't exist. Click on the link to create it.";
        }
    }
}
?>
        </div>
    </body>
</html>
