<?php

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

?>
