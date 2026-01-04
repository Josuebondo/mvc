<?php
// index.php - Main entry point of the application

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Helpers.php';

use Core\App;

$app = new App();
$app->run();
