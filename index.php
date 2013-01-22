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
    'user' => 'password'
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

function redirect_to($location) {
    header('HTTP/1.1 302 Found');
    header("Location: $location");
    die;
}

$controller = $_REQUEST['controller'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    switch ($_REQUEST['_method']) {
        case 'put':
            $http_method = 'put';
            break;
        case 'delete':
            $http_method = 'delete';
            break;
        default:
            $http_method = 'post';
    }
} else {
    $http_method = 'get';
}

include_once 'app/controllers/application.php';

require_once 'app/helpers/wikiformatter.php';    

require_once "app/controllers/$controller.php";

if (!is_callable($http_method))
    die("method $http_method not found in $controller");

$view = call_user_func($http_method);
if (empty($view))
    $view = $controller; //sets default view

    if (!file_exists("app/views/$view.php"))
    die("view $view not found");

ob_start();
require_once "app/views/$view.php"; //renders view
$yield = ob_get_contents();
ob_end_clean();

require_once 'app/views/layout.php';
