<?php

class Pinatra
{

  private $get_actions = array();
  private $post_actions = array();

  /**
   * Given a URI, do a lookup of all available actions that we currently
   * have defined (from the top-down) and execute the action that results
   * in a match.
   */
  public function action($method, $uri) {
    
  }


  /**
   * Given a match-pattern (regex) and a callback function, register the
   * action with the $get_actions to be called by the `action` function.
   */
  public function get($match, $callback) {
    if (isset($match) && isset($callback)) {
      array_push($get_actions, array($match, $callback));
    }
  }

  /**
   * Given a match-pattern (regex) and a callback function, register the
   * action with the $post_actions to be called by the `action` function.
   */
  public function post($match, $callback) {
    if (isset($match) && isset($callback)) {
      array_push($post_actions, array($match, $callback));
    }
  }
}

?>