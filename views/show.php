<?php defined('RENDER') or die('<h1>403 Forbidden</h1>');

$menu = '<li><input type="text" name="terms" accesskey="f" value="' . $terms . '"><button name="search" type="submit" title="Searches for the term in existing pages">Search</button> <button name="new" type="submit" title="Creates a page with the specified name, or leads to the page if already exists">Create</button></li>
         <li><a href="/kwik/All" accesskey="q" title="Lists all pages this wiki stores">All pages</a></li>
         <li><a href="/kwik/' . $page . '/edit" accesskey="e" title="Changes this page to edition mode">Edit page</a></li>';

require_once 'helpers/wikiformatter.php';
require_once 'layout.php';
