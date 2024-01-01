<?php

namespace Core;

use Core\Middlewares\Middleware;

class Router
{
  protected $routes = [];

  public function add(string $method, $uri, $controller)
  {
    $this->routes[] = [
      "uri" => $uri,
      "controller" => $controller,
      "method" => $method,
      "middleware" => null
    ];

    return $this;
  }

  public function get($uri, $controller)
  {
    return $this->add("GET", $uri, $controller);
  }

  public function post($uri, $controller)
  {
    return $this->add("POST", $uri, $controller);
  }

  public function put($uri, $controller)
  {
    return $this->add('PUT', $uri, $controller);
  }

  public function patch($uri, $controller)
  {
    return $this->add('PATCH', $uri, $controller);
  }

  public function delete($uri, $controller)
  {
    return $this->add('DELETE', $uri, $controller);
  }

  public function only($key)
  {
    $this->routes[array_key_last($this->routes)]['middleware'] = $key;
    return $this;
  }

  public function previousUrl()
  {
    // $_SERVER['HTTP_REFERER'] is a PHP superglobal variable that contains the URL of the page that 
    // the user came from. When you use this value for redirection, it's essentially telling the browser 
    // to go to the specified URL using a GET request.
    return $_SERVER['HTTP_REFERER'];
  }
  public function route($uri, $method)
  {
    foreach ($this->routes as $route) {
      if ($uri === $route['uri'] && $route['method'] === strtoupper($method)) {
        Middleware::resolve($route['middleware']);
        return require base_path('Http/controllers/' . $route['controller']);
      }
    }

    abort();
  }


}