<?php

/**
 * Something to parse and return the HTML request body based on
 * the method. Additionally, this could (future/theoretically) also
 * be a good place to detect the content-type of the request body
 * and intelligently parse and return that data (form-data, json,
 * xml, etc).
 */
trait HTMLBodyUtils {

  /**
   * Private: Gets the data for the current request. This may require
   *          a little extra work if the HTTP method/verb is no POST
   *          as PHP doesn't do it's automatic parsing of the request-
   *          body.
   *
   * method - The HTTP method (or verb)
   *
   * Returns an associative array
   */
  private function get_body_data($method) {
    switch ($method) {
      case 'post':
        return $_POST;
      case 'put':
      case 'delete':
        $request_body = file_get_contents('php://input');
        $body_values = [];
        parse_str($request_body, $body_values);
        return $body_values;
      default:
        return null;
    }
  }

}

?>
