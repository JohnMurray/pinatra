<?php

include 'lib/pinatra.php';


// Some test routes
Pinatra::get('/hello/:name', function($name) {
  return $name;
  return $this->json(['key' => 'hello-route has been matched!']);
});

Pinatra::run();

?>