<?php
header('Content-Type: text/html; charset=utf-8');

class COOKIES{

  var $fmt;

  function __construct($fmt){
    $this->fmt = $fmt;
  }

  function set_cookie($nombre,$valor,$int="24 * 60 * 60"){
    setcookie($nombre,$valor,time() + $int);
    //echo "aqui";
  }

  function borrar_cookie($nombre){
    setcookie("name","value",time()-1);
  }

  function get_cookie($nombre){
    return $_COOKIE[$nombre];
  }

}