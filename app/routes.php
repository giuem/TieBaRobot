<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->group('/', function (){
    $this->get('', function (Request $request,Response $response) {
        return '1';
    });

    $this->get('login', 'AuthController::logout');
})->add(new \Middleware\AuthMiddleware($container));

$app->group('/auth', function () {
    $this->get('/login', 'AuthController:index')->setName('auth.index');
    $this->post('/login', 'AuthController::login');
});