<?php

session_start();

// define document root and common folders
define('ROOT', dirname(__DIR__));
define('ROUTES', dirname(__DIR__) . '/app/routes');

// front controller (all requests routed through a single file)
require ROOT . '/app/start.php';

?>