<?php

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

  private function find_route($method, $uri) {
    $return_value = null;

    if ($method != null && !empty($method) 
        && $uri != null && !empty($uri)) {

      $method = strtolower($method);
      $uri = strtolower($uri);

      foreach($this->routes[$method] as $match => $callback) {
        $match_groups = [];
        $match_value = preg_match_all(
          $match, 
          $uri, 
          $match_groups, 
          PREG_SET_ORDER);

        if ($match_value !== 0) {
          $return_value = [
            'callback'  => $callback,
            'arguments' => array_slice($match_groups[0], 1)
          ];
          break;
        }
      }
    }

    return $return_value;
  }
}

?>
