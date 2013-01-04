<?php

include 'lib/pinatra.php';

//$uri = str_replace(PConf::BASE_DIR, '', $_SERVER['REQUEST_URI']);
Pinatra::handle_request('get', '/hello');

?>