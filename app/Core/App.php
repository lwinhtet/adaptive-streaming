<?php

namespace Core;

class App
{

  protected static $container;

  public static function setContainer($container)
  {
    static::$container = $container;
  }

  public static function getContainer()
  {
    return static::$container;
  }

  public static function bind($key, $resolver)
  {
    // here we first access our container, and use container method bind
    static::getContainer()->bind($key, $resolver);
  }

  public static function resolve($key)
  {
    // here we first access our container, and use container method resolve
    return static::getContainer()->resolve($key);
  }
}