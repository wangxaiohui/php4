<?php

namespace Core;

class Application
{
  public static function run()
  {    
      //定义字符集  
       self::setCharset();
      //定义错误显示

      //开启SESSION
      self::openSession();
      //定义路由常量
       self::defineRouteConst();
      //定义路径常量 
       self::definePathConst();
      //定义自动加载 
       self::defineAutoload();
      //定义路由分发
       self::dispatchRoute();
  } 
  
  protected static function setCharset()
  {
       header("Content-type:text/html;charset=utf-8");
  }

  protected static function openSession()
  {
    return session_start();
  }

  protected static function defineAutoload() 
  {
       spl_autoload_register('self::load');  
   } 
 
  protected static function load($classname)
  {
        $filename = str_replace("\\", "/", $classname) . ".php"; 
        if (is_file($filename)) {
          require $filename;
        }    
   } 

  protected static function definePathConst()
  {
        define('VIEW_PATH','App/View');
        define('CONFIG_PATH', 'App/Config');
  }
 
  protected static function defineRouteConst()
  {
         $p = isset($_GET['p']) ? $_GET['p'] : 'Frontend';
         define('PLAT', $p);
         $c = isset($_GET['c']) ? $_GET['c'] : 'Article';
         define('CONTROLLER', $c);
         $a = isset($_GET['a']) ? $_GET['a'] : 'select';
         define('ACTION', $a);
  }       
        
  protected static function dispatchRoute()
  {     
        $c = "App\\Controller\\" . PLAT . "\\" . CONTROLLER."Controller";
        $obj = new $c();
        $a = ACTION;
        $obj->$a();
  }
 

}
