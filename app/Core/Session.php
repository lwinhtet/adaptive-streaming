<?php

namespace Core;

class Session
{
  public static function has($key)
  {
    // The (bool) is a type cast that converts the result of static::get($key) into a boolean value.
    return (bool) static::get($key);
  }

  public static function put($key, $value)
  {
    $_SESSION[$key] = $value;
  }

  //$_SESSION['_flash'] is for storing error. Eg. When we perform login POST req, success or fail, 
  //we dont want to return a view directly from POST req instead we want to redirect it. It will 
  //prevent Resend alert after refresh or Document Expired Page after navigating away and returning. 
  //One problem is how do pass validation error on next page req if it failed. So we used $_SESSION
  public static function get($key, $default = null)
  {
    return $_SESSION['_flash'][$key] ?? $_SESSION[$key] ?? $default;
  }

  public static function flash($key, $value)
  {
    $_SESSION['_flash'][$key] = $value;
  }

  public static function unflash()
  {
    unset($_SESSION['_flash']);
  }

  public static function flush()
  {
    $_SESSION = [];
  }

  public static function destory()
  {
    static::flush();
    // to destroy our session file on the server
    session_destroy();

    $params = session_get_cookie_params();
    setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    /* setcookie: This is a PHP function used to send cookies to the browser. It takes several parameters 
    to configure the cookie.

    'PHPSESSID': This is the name of the cookie. In this case, it's a common name used for a session ID cookie.
    '': This is the value of the cookie. In this case, it's an empty string, which effectively removes the value 
    associated with the 'PHPSESSID' cookie.
    time() - 3600: This sets the expiration time of the cookie to the past, effectively expiring it immediately. 
    The time() - 3600 expression represents the current time minus 3600 seconds (1 hour).
    $params['path'], $params['domain'], $params['secure'], $params['httponly']: These parameters are optional and 
    can be part of an array ($params) that specifies additional settings for the cookie. These settings include 
    the path on the server where the cookie is available, the domain to which the cookie is available, whether the 
    cookie should only be sent over secure connections ($params['secure']), and whether the cookie should only be 
    accessible through the HTTP protocol ($params['httponly']). */
  }
}