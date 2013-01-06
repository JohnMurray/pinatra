<?php

require 'traits/singleton.php';
require 'traits/json_utils.php';
require 'traits/routing.php';


/**
 * The main class for our Sinatra clone. Where all of the 
 * (not-so-much-)magic happens!  :-]
 */
class Pinatra {

  use singleton;
  use JSONUtils;
  use Routing;

  protected $before_hooks = [];
  protected $after_hooks = [];
  protected $routes = [];
  protected $config = [];

  private function __construct() {
    $this->config = [
      'base_path'   => ''
    ];
  }

  /**
   * Public: Register a user-defined handler with a particular regex to
   *         match with and a callback that will handle the results.
   */
  private function register($method, $match, $callback) {
    $match = $this->compute_regex($match);
    $this->routes[$method][$match] = $callback;
  }

  /**
   * Public: Register a callback that will be run before any routes
   *         for a particular request. A match is also given such
   *         that it can be applied to a number of routes.
   */
  private function register_before($match, $callback) {
    if (empty($match)) $match = '*';
    $match = $this->compute_regex($match);
    $this->before_hooks[$match] = $callback;
  }

  /**
   * Public: Register a callback that will be run after any routes
   *         for a particular request. A match is also given such
   *         that it can be applied to a number of routes.
   */
  private function register_after($match, $callback) {
    if (empty($match)) $match = '*';
    $match = $this->compute_regex($match);
    $this->after_hooks[$match] = $callback;
  }

  /**
   * Public: Allow the user to change any configuration options
   *         that will be used by Pinatra. Also, any custom options
   *         that they just want to provide can also be stored.
   */
  private function user_configuration($callback) {
    $this->config = $callback($this->config);
  }


  /*
   * REGISTRATION METHODS
   * 
   * This is pretty self-explanatory... honestly.
   */

  public static function configure($callback) {
    $app = Pinatra::instance();
    $app->user_configuration($callback);
  }
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
   *
   * TODO: This method needs refactoring (badly)
   */
  public static function handle_request($method, $uri) {
    $app = Pinatra::instance();


    // find and call all before-hooks
    $before_matches = $app->find_all_routes($app->before_hooks, $uri);
    foreach ($before_matches as $match) {
      $match['callback']->bindTo($app);
      call_user_func_array($match['callback'], $match['arguments']);
    }

    // find and call route-handler
    $route_match = $app->find_route($app->routes, $method, $uri);
    if ($route_match !== null) {
      $route_match['callback']->bindTo($app);
      echo call_user_func_array(
        $route_match['callback'], 
        $route_match['arguments']);
    }

    // find and call all after-hooks
    $after_matches = $app->find_all_routes($app->after_hooks, $uri);
    foreach ($after_matches as $match) {
      $match['callback']->bindTo($app);
      call_user_func_array($match['callback'], $match['arguments']);
    }
    
  }


  /**
   * Used to start the application (after everything has been initialized)
   */
  public static function run() {
    $app = Pinatra::instance();

    $uri = str_replace(
      $app->config['base_path'], 
      '', 
      $_SERVER['REQUEST_URI']);

    Pinatra::handle_request('get', $uri);
  }

}





?>
