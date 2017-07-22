<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();

require_once __DIR__ . '/database.php';

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
    ]
]);

$container = $app->getContainer();

$container['AuthController'] = new \Controllers\AuthController($container);

require __DIR__ . '/../app/routes.php';