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

$page = $_GET['page'];

if (strpos($page, ' ') !== false) { //requested page has spaces
    header('HTTP/1.1 302 Found');
    header('Location: ' . str_replace('%20', '_', $_SERVER['REQUEST_URI']));
    die;
}

require_once 'wikiformatter.php';

if ($page == 'All') { //prevents All from being edited: it is a special page, not to be found in the filesystem
	header('HTTP/1.1 302 Found');
	header("Location: $path/$page");
	die;
}

if (empty($page)) die;

if (array_key_exists('content', $_POST) && array_key_exists('save', $_POST)) {
    $content = $_POST['content']; //not asumming register globals
    if (get_magic_quotes_gpc()) { //we're not interested in magic_quotes_gpc, and cannot be disabled at runtime
        $content = stripslashes($content);
    }
    file_put_contents("pages/$page", $content);
	header('HTTP/1.1 302 Found');
	header("Location: $path/$page");
	die;
}

if (array_key_exists('delete', $_POST)) {
    if ($page != 'Main_page') { //disallows Main page deletion
        `cd pages; rm $page`;
        header('HTTP/1.1 302 Found');
        header("Location: $path/");
        die;
    }
}

if (array_key_exists('preview', $_POST)) $content = $_POST['content'];
else if (file_exists("pages/$page")) { //if Preview neither Save have been clicked, then the user wants to see a page (file) content
    $content = file_get_contents("pages/$page");
} else {
    $content = 'Start here to write the page content.';
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
                        <li><button name="preview" type="submit" accesskey="p" title="Page preview, without saving">Preview</button></li>
                        <li><button name="save" type="submit" accesskey="s" title="Saves changes to this page">Save</button></li>
                        <li><button name="delete" type="submit" title="Deletes current page from disk, forever">Delete</button></li>
                    </ul>
                </div>
            </div>
            <div id="contents">
                <span class="resizer" id="rowa" title="keep pressing to enlarge textbox">more</span>
                <span class="resizer" id="rowd" title="keep pressing to shrink textbox">less</span>
                <textarea name="content" rows="25" cols="80" class="prettyprint"><?=$content?></textarea>
                <?wikiformatter($content)?>
            </div>
        </form>
        <script type="text/javascript" src="<?=$path?>/res/jquery.js"></script>
        <script type="text/javascript">
        <!--
            var add = 0;
            $('.resizer').mousedown(function(){
                if ($(this).attr('id') == 'rowa') add = 1;
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
