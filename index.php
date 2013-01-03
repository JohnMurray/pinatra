<?php

include 'lib/pinatra.php';
include 'app/conf.php';


$uri = str_replace(PConf::BASE_DIR, '', $_SERVER['REQUEST_URI']);
$app = new Pinatra();
$app->load_routes('app/app.php');

echo "<p>Page URL: ${uri}</p>";

?>