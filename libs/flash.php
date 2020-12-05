<?php

class Flash{

  public static function render($name) {
    if (!isset($_SESSION['messages'][$name])) {
      return null;
    }else{
      $messages = $_SESSION['messages'][$name];
      unset($_SESSION['messages'][$name]);
      return $messages;
    }
  }
  
  public static function add($name,$data) {
    if (!isset($_SESSION['messages'])) {
      $_SESSION['messages'] = array();
    }
    $_SESSION['messages'][$name] = $data;
  }

}