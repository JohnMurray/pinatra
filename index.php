<?php

include 'lib/pinatra.php';

Pinatra::configure(function ($conf) {
  $config['base_path'] = '/bank';
  return $config;
});

Pinatra::before('*', function () {
  header('test: me');
});

// Some test routes
Pinatra::get('/hello/:name', function($name) {
  return $name;
  return $this->json(['key' => 'hello-route has been matched!']);
});

Pinatra::run();

?>