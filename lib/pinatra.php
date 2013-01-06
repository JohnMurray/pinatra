<?php

require 'traits/singleton.php';
require 'traits/json_utils.php';
require 'traits/routing.php';


/**
 * The main class for our Sinatra clone. Where all of the (not-so-much-)magic
 * happens!  :-]
 */
class Pinatra {

  use singleton;
  use JSONUtils;
  use Routing;

  public $before_hooks = [];
  public $after_hooks = [];
  public $routes = [];

  private function __construct() {}

  /**
   * Public: Register a user-defined handler with a particular regex to
   *         match with and a callback that will handle the results.
   */
  public function register($method, $match, $callback) {
    $match = $this->compute_regex($match);
    $this->routes[$method][$match] = $callback;
  }

  /**
   * Public: Register a callback that will be run before any routes
   *         for a particular request. A match is also given such
   *         that it can be applied to a number of routes.
   */
  public function register_before($match, $callback) {
    if (empty($match)) $match = '*';
    $match = $this->compute_regex($match);
    $before_hooks[$match] = $callback;
  }

  /**
   * Public: Register a callback that will be run after any routes
   *         for a particular request. A match is also given such
   *         that it can be applied to a number of routes.
   */
  public function register_after($match, $callback) {
    if (empty($match)) $match = '*';
    $match = $this->compute_regex($match);
    $after_hooks[$match] = $callback;
  }


  /*
   * REGISTRATION METHODS
   * 
   * This is pretty self-explanatory... honestly.
   */

  public static function get($match, $callback) {
    $app = Pinatra::instance();
    $app->register('get', $match, $callback);
  }
  public static function post($match, $callback) {
    $app = Pinatra::instance();
    $app->register('post', $match, $callback);
  }
  public static function before($match, $callback) {
    $app = Pinatra::instance();
    $app->register_before($match, $callback);
  }
  public static function after($match, $callback) {
    $app = Pinatra::instance();
    $app->register_after($match, $callback);
  }



  /**
   * Method that is called when we actually want to process an incoming
   * request based on the method and uri provided. This method (expecting
   * to be given a URI and method) can also be used for re-routing requests
   * internally.
   */
  public static function handle_request($method, $uri) {
    // TODO: combine with routing code that I wrote at work...
    $app = Pinatra::instance();
    if ($method != null && !empty($method) && $uri != null && !empty($uri)) {
      $method = strtolower($method);
      foreach($app->routes[$method] as $match => $callback) {
        $match_value = preg_match($match, $uri);
        if ($match_value === false) {
          // TODO: do something real here??
          echo 'ERROR on match.';
        }
        else if ($match_value !== 0) {
          $callback = $callback->bindTo($app);
          echo $callback();
          return;
        }
      }
    }
  }


  public static function run() {
    $uri = str_replace('', '', $_SERVER['REQUEST_URI']);
    Pinatra::handle_request('get', $uri);
  }

}





?>