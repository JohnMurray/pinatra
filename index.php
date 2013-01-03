<?php

include 'lib/Pinatra.php';

$curPage = $_SERVER['REQUEST_URI'];
echo "<p>Page URL: ${curPage}</p>";
?>