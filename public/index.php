<?php

session_start();

// define document root
define('ROOT', dirname(__DIR__));

// front controller (all requests routed through a single file)
require ROOT . '/app/start.php';

?>