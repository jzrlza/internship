<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/Controllers/Login.php';
require '../src/Classes/Database.php';

$app = new \Slim\App([
  'settings' => [
      'displayErrorDetails' => true
  ]
]);

$container = $app->getContainer();
$container['renderer'] = new \Slim\Views\PhpRenderer("./");

$app->add(new \Slim\Middleware\Session());
$container['session'] = function ($c) {
  return new \SlimSession\Helper;
};

$container['db'] = new Database();

$container['login'] = new Login($container['db']);

/**
 * Routes
 */
$app->get('/', function (Request $request, Response $response, array $args)
{  
  if ($this->session->exists('user_name')) {
    return $response->withRedirect("login.php/welcome");
  }

  return $this->renderer->render($response, "Views/login_page.phtml", $args);
});

$app->get('/regis', function (Request $request, Response $response, array $args)
{
  //Make sure the session is non-existant before run this page
  if ($this->session->exists('user_name')) {
    return $response->withRedirect("welcome");
  }

  return $this->renderer->render($response, "Views/regis_page.phtml", $args);
});

$app->post('/register', function (Request $request, Response $response, array $args)
{
  $input = $request->getParsedBody();
  
  $regis_result = $this->login->register($input);
  
  if (get_class($regis_result) !== 'Exception') {
    return $response->withRedirect('register-success');
  } else {
    $data['msg'] = $regis_result->getMessage();
    return $response->withJson($data, $regis_result->getCode());
  }
});

$app->get('/register-success', function (Request $request, Response $response, array $args)
{
  if ($this->session->exists('user_name')) {
    return $response->withRedirect("welcome");
  }

  return $this->renderer->render($response, "Views/regis_success.phtml", $args);
});

$app->post('/login/{type}', function (Request $request, Response $response, array $args)
{
  $type = $args['type'];

  if ($type === 'facebook') {
    $fb_user_id = $request->getParsedBody()['fb_user_id'];
    $fb_user_name = $request->getParsedBody()['fb_user_name'];
    $fb_user_img_src = $request->getParsedBody()['fb_user_img_src'];

    $this->session->set('user_id', $fb_user_id);
    $this->session->set('user_name', $fb_user_name);
    $this->session->set('user_img', $fb_user_img_src);
    $this->session->set('user_fullname', null);
    $this->session->set('user_nickname', null);
    $this->session->set('is_FB', true);

    $response->getBody()->write("Hello? ".$fb_user_name . " " . $fb_user_id. ' '. $fb_user_img_src);
    return $response->withRedirect("../welcome");
    //https://www.cloudways.com/blog/add-facebook-login-in-php/
  } else { //guest
    // Create connection, be careful of variable name conflicts!!
    $input = $request->getParsedBody();
    $auth_result = $this->login->authenticate($input);

    if (get_class($auth_result) !== 'Exception') {
      $this->session->set('user_id', $auth_result["id"]);
      $this->session->set('user_name', $auth_result["user_name"]);
      $this->session->set('user_img', $auth_result["image_src"]);
      $this->session->set('user_fullname', $auth_result["full_name"]);
      $this->session->set('user_nickname', $auth_result["nick_name"]);
      $this->session->set('is_FB', false);

      return $response->withRedirect("../welcome");
    } else {
      $data['msg'] = $auth_result->getMessage();
      return $response->withJson($data, $auth_result->getCode());
    }
  }
});

$app->post('/logout', function (Request $request, Response $response, array $args)
{
  $this->session::destroy();

  $response->getBody()->write("Logged out".$this->session["user_name"]);

  return $response->withRedirect("../login.php");
});

$app->get('/welcome', function (Request $request, Response $response, array $args)
{
  if (!$this->session->exists('user_name')) {
    return $response->withRedirect("../login.php");
  }

  return $this->renderer->render($response, "Views/hello.phtml", $args);
});

$app->put('/edit/{user_name}', function (Request $request, Response $response, array $args)
{
  //do not use 'form-data' to test, use 'x-www-form-urlencoded' instead
  $user_name = $args['user_name'];
  $password = $request->getParsedBody()['password'];
  $response->getBody()->write("Edited $user_name's password to $password");
});

/**
 * Run
 */
$app->run();
