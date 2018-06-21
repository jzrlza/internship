<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;

//http://localhost/internship/html/login.php/hello/12345
$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("Hello");
    return $response;
});
$app->get('/{user_name}/{password}', function (Request $request, Response $response, array $args) {
    $user_name = $args['user_name'];
    $password = $args['password'];

    if ($user_name === 'hello' && $password === '12345'){
        $response->getBody()->write("Hello, $user_name.");
        return $response;
    }
});
$app->post('/{user_name}/{password}', function (Request $request, Response $response, array $args) {
    $user_name = $args['user_name'];
    $password = $args['password'];
    
    $response->getBody()->write("Registered $user_name.");
    return $response;
});
$app->put('/{user_name}/{password}', function (Request $request, Response $response, array $args) {
    $user_name = $args['user_name'];
    $password = $args['password'];

    if ($user_name === 'hello' && $password === '12345'){
        $response->getBody()->write("Edited $user_name.");
        return $response;
    }
});
$app->run();
