<?php

require_once __DIR__.'/../vendor/autoload.php';

use App\Application;

/**
 * APP initialization
 * @var \App\Application
 */
$app = new App\Application([
    'debug' => ($_SERVER['SERVER_NAME'] === 'localhost'),
]);

$app->run();
