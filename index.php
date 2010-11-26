<?php
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


////////////////////////////////////////////////////////////////////////////////
//
// PLEASE EDIT THE FOLLOWING ARRAY TO DEFINE AUTHORIZED USERS
//
////////////////////////////////////////////////////////////////////////////////


$users = array(
            'user'  => 'password'
        //, 'user2' => 'password'
);




////////////////////////////////////////////////////////////////////////////////
//
// KWIK CONTROL CODE BEGINS
//
////////////////////////////////////////////////////////////////////////////////


//user auth
if (!(array_key_exists($_SERVER['PHP_AUTH_USER'], $users) && $_SERVER['PHP_AUTH_PW'] == $users[$_SERVER['PHP_AUTH_USER']])) {
    header('WWW-Authenticate: Basic realm="kwik"');
    header('HTTP/1.1 401 Unauthorized');
    die('401 Unauthorized');
}

$terms = '';
$form = '/kwik/search';

$page = $_REQUEST['page'];
if (empty($page))
    $page = 'Main_page';

if (strpos($page, ' ') !== false) { //requested page has spaces
    header('HTTP/1.1 302 Found');
    header('Location: /' . str_replace('%20', '_', $page));
    die;
}

$page_pretty = str_replace('_', ' ', $page);

define('RENDER', true); //allow views to be rendered

switch ($_REQUEST['action']) { //frontend controller
    case 'show':
        if ($page == 'All') {
            $content = "==All pages==\n*[[Main_page]]\n\n";
            $pages = array();
            if ($h = @opendir('pages')) {
                while (false !== ($f = readdir($h))) {
                    if ($f[0] != '.' && $f[0] != 'Main_page') {
                        $pages[] = $f;
                    }
                }
                closedir($h);
            } else @mkdir('pages');
            natsort($pages);
            foreach ($pages as $p) {
                $content .= "*[[$p]]\n";
            }
        } else {
            if (file_exists("pages/$page")) {
                $content = file_get_contents("pages/$page");
            } else {
                $terms = $page;
                $content = "Page doesn't exist. Click on the link to create it.";
            }
        }

        require_once 'views/show.php'; //renders view
        break;

    case 'edit':
        if ($page == 'All') { //prevents All from being edited: it is a special page, not to be found in the filesystem
            header('HTTP/1.1 302 Found');
            header("Location: /kwik/$page");
            die;
        }

        $form = $_SERVER['REQUEST_URI'];
        $content = $_REQUEST['content'];
        if (get_magic_quotes_gpc()) { //we're not interested in magic_quotes_gpc, and cannot be disabled at runtime
            $content = stripslashes($content);
        }
        
        if (array_key_exists('content', $_REQUEST) && array_key_exists('save', $_REQUEST)) {
            file_put_contents("pages/$page", $content);
            header('HTTP/1.1 302 Found');
            header("Location: /kwik/$page");
            die;
        }
        if (array_key_exists('delete', $_REQUEST)) {
            if ($page != 'Main_page') { //disallows Main page deletion
                `cd pages; rm $page`;
                header('HTTP/1.1 302 Found');
                header("Location: /kwik/");
                die;
            }
        }

        if (!array_key_exists('preview', $_REQUEST)) {
            if (file_exists("pages/$page")) { //if Preview neither Save have been clicked, loads page from filesystem
                $content = file_get_contents("pages/$page");
            } else {
                $content = 'Start here to write the page content.';
            }
        }

        require_once 'views/edit.php'; //renders view
        break;

    case 'search':
        if (array_key_exists('new', $_REQUEST)) {
            header('HTTP/1.1 302 Found');
            header("Location: /kwik/{$_REQUEST['terms']}/edit");
            die;
        }

        $terms = empty($_REQUEST['terms']) ? $post : $_REQUEST['terms'];

        $page_pretty = 'Page search...';

        $content = "==Search results==\n";
        $content .= "===Page name matches===\n";
        $search = `cd pages; ls`;
        foreach (explode("\n", $search) as $l) {
            if (strpos(strtolower($l), strtolower($terms)) !== false)
                $content .= "*[[$l]]\n";
        }

        $content .= "\n===Page content matches===\n";
        $search = `cd pages; grep '$terms' *`; //TODO case insensitive search
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

        require_once 'views/show.php'; //renders view
        break;

    default:
        die('<h1>404 Not Found</h1>');
}