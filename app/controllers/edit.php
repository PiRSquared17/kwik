<?php

$form_for = 'action="/kwik/' . $page . '/edit" method="post"';

function get() {
    global $page, $content;

    if ($page == 'All' || empty($page)) { //prevents All from being edited: it is a special page, not to be found in the filesystem
        redirect_to("/kwik/$page");
    }

    if (file_exists("pages/$page")) { //if Preview neither Save have been clicked, loads page from filesystem
        $content = file_get_contents("pages/$page");
    } else {
        $content = 'Start here to write the page content.';
    }
}

function put() {
    global $page, $content;

    if ($page == 'All' || empty($page)) { //prevents All from being edited: it is a special page, not to be found in the filesystem
        redirect_to("/kwik/$page");
    }

    $content = $_REQUEST['content'];
    if (get_magic_quotes_gpc ()) { //we're not interested in magic_quotes_gpc, and cannot be disabled at runtime
        $content = stripslashes($content);
    }

    if (!array_key_exists('preview', $_REQUEST)) {
        @mkdir('pages');
        file_put_contents("pages/$page", $content);
        redirect_to("/kwik/$page");
    }
}

function delete() {
    global $page;

    if ($page != 'Main_page' || $page != 'All') { //some pages are protected against deletion
        `cd pages; rm $page`;
    }

    redirect_to('/kwik/');
}