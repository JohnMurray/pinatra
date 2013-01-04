<?php

include 'lib/pinatra.php';


// Some test routes
Pinatra::get('/hello', function() {
  return $this->json(['key' => 'hello-route has been matched!']);
});

Pinatra::run();

?>