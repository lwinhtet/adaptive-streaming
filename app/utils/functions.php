<?php

function dd($value)
{
  echo "<pre>";
  var_dump($value);
  echo "</pre>";

  die();
}
function base_path($path)
{
  return BASE_PATH . $path;
}

function env($key, $default = false)
{
  return $_ENV[$key] ?? $default ?? throw new InvalidArgumentException("Undefined env variable '{$key}'!!");
}

function view($path, $attributes = [])
{
  extract($attributes);

  require base_path("views/" . $path);
}

function urlIs($url)
{
  return $_SERVER['REQUEST_URI'] === $url;
}

function redirect($path)
{
  header("location: {$path}");
  exit;
}

function authorize($condition, $status = Response::FORBIDDEN)
{
  if (!$condition) {
    abort($status);
  }

  return true;
}

function abort($code = 404)
{
  http_response_code($code);
  require base_path("views/errors/{$code}.php");
  die();
}

function old($key, $default = '')
{
  return Core\Session::get('old')[$key] ?? $default;
}

function getDevEnv()
{
  return $_ENV['APP_ENV'];
}

function my_echo($message)
{
  /*PHP_EOL: This is a predefined constant in PHP representing the end-of-line character sequence appropriate 
  for the platform (e.g., \n on Unix-based systems, \r\n on Windows). */
  echo $message . PHP_EOL;
}

