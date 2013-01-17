<?php

include 'lib/pinatra.php';

Pinatra::configure(function ($conf) {
  $config['base_path'] = '/bank';
  return $config;
});

Pinatra::before('*', function () {
  //header('test: me');
});

Pinatra::after('*', function () {
  //echo 'DONE';
});



// Some test routes
Pinatra::get('/hello/:name', function($name) {
  return $this->json([
    'key' => 'hello-route has been matched!', 
    'name' => $name
  ]);
});

Pinatra::post('/form-submit', function ($data) {
  var_dump($data);
});

Pinatra::head('/hello/:name', function($name) {
  header("x-name: ${name}");
});

Pinatra::delete('/hello/:name', function ($name) {
  header("x-deleted: ${name}");
  return 'deleted!';
});

Pinatra::put('/hello/:name', function ($data, $name) {
  header("x-deleted: ${name}");
  var_dump($data);
});

Pinatra::run();

?>