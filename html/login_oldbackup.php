<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;

//http://localhost/internship/html/login.php/hello/12345
$app->get('/', function (Request $request, Response $response, array $args)
{
  $response->getBody()->write("Hello");
  return $response;
});

$app->get('/{user_name}/{password}', function (Request $request, Response $response, array $args)
{
  $user_name = $args['user_name'];
  $password = $args['password'];
  if ($user_name === 'hello' && $password === '12345')
  { //has in database
    $response->getBody()->write("Hello, $user_name.");
    return $response;
  }
});

$app->post('/register', function (Request $request, Response $response, array $args)
{
  $user_name = $request->getParsedBody()['user_name'];
  $password = $request->getParsedBody()['password'];
  $response->getBody()->write("Registered $user_name. With $password as password");
  return $response;
});

$app->put('/edit/{user_name}', function (Request $request, Response $response, array $args)
{
  //do not use 'form-data' to test, use 'x-www-form-urlencoded' instead
  $user_name = $args['user_name'];
  $password = $request->getParsedBody()['password'];
  $response->getBody()->write("Edited $user_name's password to $password");
});

$app->run();
