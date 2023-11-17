<?php

use Core\Errors\ValidationException;
use Core\Session;

const BASE_PATH = __DIR__ . "/../";

session_start();

require BASE_PATH . 'vendor/autoload.php';
require BASE_PATH . 'utils/error_handler.php';
require BASE_PATH . 'utils/functions.php';
require BASE_PATH . 'bootstrap.php';

$router = new \Core\Router();
require BASE_PATH . 'routes.php';

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

try {
  $router->route($uri, $method);
} catch (ValidationException $e) {
  Session::flash('errors', $e->errors);
  Session::flash('old', $e->old);
  // in case of redirecting, we need to be dynamic(using $_SERVER['HTTP_REFERER'] that would be "localhost:8888/login")
  // we are redirecting to previous url
  return redirect($router->previousUrl());
}

// after routing, we want to delete/flush some session data eg. $_SESSION['_flash']['error']
// I just deleting the page error after navigating away and returning to the page
Session::unflash();