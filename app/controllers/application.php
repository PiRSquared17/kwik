<?php

$page = $_REQUEST['page'];
if (empty($page))
    $page = 'Main_page';

if (strpos($page, ' ') !== false) { //requested page has spaces
    redirect_to('/' . str_replace('%20', '_', $page));
}

$page_pretty = str_replace('_', ' ', $page);
