<?php

/**
 * Singleton that I copied from the Traits' page-comments on the PHP
 * documentation site. Looked nifty.
 */
trait singleton {    
    
  public static function instance() {
    static $_instance = null;
    $class = __CLASS__;
    return $_instance ?: $_instance = new $class;
  }
  
  public function __clone() {
      trigger_error('Cloning '.__CLASS__.' is not allowed.',E_USER_ERROR);
  }
  
  public function __wakeup() {
      trigger_error('Unserializing '.__CLASS__.' is not allowed.',E_USER_ERROR);
  }
}

?>
