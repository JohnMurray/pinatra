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
    if (isset($method) && isset($uri) && $method != null && $uri != null) {
      $method = strtolower($method);
      $actions = $method == 'get' ? $get_actions : $post_actions;
      foreach ($actions as $match => $callback) {
        $match_value = preg_match($match, $uri);
        if ($match_value === false) {
          // TODO: do something real here??
          echo 'ERROR on match';
        }
        else if ($match_value !== 0) {
          $callback();
          return;
        }
      }
    }

    // TODO: do something real here??
    echo 'no matching route found';
  }


  /**
   * Given a match-pattern (regex) and a callback function, register the
   * action with the $get_actions to be called by the `action` function.
   */
  public function get($match, $callback) {
    if (isset($match) && isset($callback)) {
      $get_actions[$match] = $callback;
    }
  }

  /**
   * Given a match-pattern (regex) and a callback function, register the
   * action with the $post_actions to be called by the `action` function.
   */
  public function post($match, $callback) {
    if (isset($match) && isset($callback)) {
      $post_actions[$match] = $callback;
    }
  }


  /**
   * This is how we are going to load our paths in a Sinatra-classic-like way.
   * The user will define a file of routes calling the [get, post] methods and
   * we will include them in the class (calling them) and defining all of our
   * routes to match against.
   */
  public function load_routes($file_path) {
    include $file_path;
  }
}

?>