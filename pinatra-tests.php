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
 * The main class for our Sinatra clone. Where all of the (not-so-much-)magic
 * happens!  :-]
 */
class Pinatra {

  use singleton;
  use JSONUtils;

  public $before_hooks = [];
  public $after_hooks = [];
  public $routes = [];

  private function __construct() {}

  /**
   * Public: Register a user-defined handler with a particular regex to
   *         match with and a callback that will handle the results.
   */
  public function register($method, $match, $callback) {
    $this->routes[$method][$match] = $callback;
  }

  /**
   * Public: Register a callback that will be run before any routes
   *         for a particular request. A match is also given such
   *         that it can be applied to a number of routes.
   */
  public function register_before($match, $callback) {
    if (empty($match)) $match = '/*/';
    $before_hooks[$match] = $callback;
  }

  /**
   * Public: Register a callback that will be run after any routes
   *         for a particular request. A match is also given such
   *         that it can be applied to a number of routes.
   */
  public function register_after($match, $callback) {
    if (empty($match)) $match = '/*/';
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



  public static function handle_request() {
    // TODO: combine with routing code that I wrote at work...

    // TODO: remove test-code (but take not of the bindTo method)
    $app = Pinatra::instance();
    $callback = $app->routes['get']['/hello/'];
    $callback = $callback->bindTo($app);
    echo $callback();
  }

}



// Some test routes
Pinatra::get('/hello/', function() {
  return $this->json(['key' => 'hello-route has been matched!']);
});
Pinatra::handle_request();
//var_dump(Pinatra::instance());
?>
