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


/**
 * Something to try and make serving JSON content just a litle simpler
 */
trait JSONUtils {

  public function json($object) {
    // set appropriate headers
    header('Content-Type: application/json', true);

    // TODO: pretty-print JSON based on config settings
    // return the serialized object
    return json_encode($object);
  }

}


/**
 * If we're going to rip off Sinatra properly, then we'll need to have some
 * proper URI's as well. This means a proper parser for those URIs. The kind
 * of URIs that I am talking about are of the type:
 *
 *   /blogs
 *   /blogs/:id
 *   /blogs/:blog_id/comments/:comment_id
 *   /*
 *   /*.css
 *   etc.
 *
 * We're going to take URIs of those styles and transfer them into proper
 * (Perl) regular expressions for matching against real-URIs.
 */
trait Routing {

  private $URIParser_PLACEHOLDER = '([^\/]+)';
  private $URIParser_GLOB = '*';


  /**
   * Private: Generate a PHP (Perl) regular expression given the
   *          Sinatra-style expression in the get/post/put/etc. functions.
   */
  private function compute_regex($match) {
    // get the URI parts of the match-pattern given
    $parts = array_filter(explode('/', $match), function ($val) { 
      return !empty($val);
    });

    // build our pattern-matching regex from given route
    $regex= '/^';

    foreach ($parts as $part) {
      if ($part[0] === ':') {
        $regex .= '\/' . $this->URIParser_PLACEHOLDER;
      }
      else if ($part[0] === '*'){
        $regex .= '\/' . $this->URIParser_GLOB;
      }
      else {
        $regex .= '\/' . $part;
      }
    }
    $regex .= '\/?$/';
    return $regex;
  }
}


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

  public function get($match, $callback) {
    $app = Pinatra::instance();
    $app->register('get', $match, $callback);
  }
  public function post($match, $callback) {
    $app = Pinatra::instance();
    $app->register('post', $match, $callback);
  }
  public function before($match, $callback) {
    $app = Pinatra::instance();
    $app->register_before($match, $callback);
  }
  public function after($match, $callback) {
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
