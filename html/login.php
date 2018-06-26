<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;

$app->add(new \Slim\Middleware\Session());
$session = new \SlimSession\Helper; //from https://github.com/bryanjhv/slim-session

/**
 * Routes
 */
$app->get('/', function (Request $request, Response $response, array $args)
{
  $session = new \SlimSession\Helper;

  if (endsWith($_SERVER["REQUEST_URI"],'/')) {
    return $response->withRedirect(substr($_SERVER["REQUEST_URI"], 0,-1));
  }
  if ($session->exists('user_name')) {
    return $response->withRedirect("login.php/welcome");
  }

  $file = parsePHP('login_page.php');

  $response->getBody()->write($file);

  return $response;
});

$app->get('/{user_name}/{password}', function (Request $request, Response $response, array $args)
{
  $user_name = $args['user_name'];
  $password = $args['password'];
  
  $conn = connect_db();
 
  authenticate($conn, $response, $user_name, $password);

  $conn->close();

  return $response;
});

$app->get('/regis', function (Request $request, Response $response, array $args)
{
  //Make sure the session is non-existant before run this page
  $session = new \SlimSession\Helper;

  if (endsWith($_SERVER["REQUEST_URI"],'/')) {
    return $response->withRedirect(substr($_SERVER["REQUEST_URI"], 0,-1));
  }
  if ($session->exists('user_name')) {
    return $response->withRedirect("welcome");
  }

  $file = parsePHP('regis_page.php');

  $response->getBody()->write($file);

  return $response;
});

$app->post('/register', function (Request $request, Response $response, array $args)
{
  $user_name = $request->getParsedBody()['user_name'];
  $password = $request->getParsedBody()['password'];
  $password_confirm = $request->getParsedBody()['password_confirm'];
  $email = $request->getParsedBody()['email'];
  $full_name = $request->getParsedBody()['full_name'];
  $nick_name = $request->getParsedBody()['nick_name'];
 
  $image_src = basename($_FILES["image_upload"]["name"]);

  /*
  $check = getimagesize($_FILES["image_upload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    */
  
  //Password Match?
  if ($password !== $password_confirm) {
    return $response->withRedirect('regis');
  }

  //Connect
  $conn = connect_db();

  //No duplicate user_name allowed
  $found_user = $conn->query("SELECT user_name FROM users WHERE user_name = '$user_name'");
  if ($found_user->num_rows > 0) {
    return $response->withRedirect('regis');
  }

  //No duplicate email allowed
  $found_email = $conn->query("SELECT email FROM users WHERE email = '$email'");
  if ($found_email->num_rows > 0) {
    return $response->withRedirect('regis');
  }

  //Upload the profile image

  //The real insertion
  $sql = "INSERT INTO users (id, user_name, password, email, full_name, nick_name, image_src, facebook_user_id, banned) 
          VALUES (NULL, '".$user_name."', '".$password."', '".$email."', '".$full_name."', '".$nick_name."', '".$image_src."', NULL, '0');";
  $result = $conn->query($sql);

  $response->getBody()->write("Registered $user_name. $image_src");

  $conn->close();

  return $response->withRedirect('register_success');
});

$app->get('/register_success', function (Request $request, Response $response, array $args)
{
  //Make sure the session is non-existant before run this page
  $session = new \SlimSession\Helper;

  if (endsWith($_SERVER["REQUEST_URI"],'/')) {
    return $response->withRedirect(substr($_SERVER["REQUEST_URI"], 0,-1));
  }
  if ($session->exists('user_name')) {
    return $response->withRedirect("welcome");
  }

  $file = parsePHP('regis_success.php');

  $response->getBody()->write($file);

  return $response;
});

$app->post('/login', function (Request $request, Response $response, array $args)
{
  $user_name = $request->getParsedBody()['user_name'];
  $password = $request->getParsedBody()['password'];

  // Create connection, be careful of variable name conflicts!!
  $conn = connect_db();

  if (authenticate($conn, $response, $user_name, $password)) {
    return $response->withRedirect("welcome");
  } else {
    $conn->close();
    return $response->withRedirect("../login.php");
  }

});

$app->get('/login_facebook', function (Request $request, Response $response, array $args)
{
  $response->getBody()->write("Hello? ".$_GET['fb_user_name']);
  return $response;
  //return $response->withRedirect("welcome");
});

$app->post('/logout', function (Request $request, Response $response, array $args)
{
  $session = new \SlimSession\Helper;

  $session::destroy();

  $response->getBody()->write("Logged out".$session["user_name"]);

  return $response->withRedirect("../login.php");
});

$app->get('/welcome', function (Request $request, Response $response, array $args)
{
  $session = new \SlimSession\Helper;
  if (!$session->exists('user_name')) {
    return $response->withRedirect("../login.php");
  }

  $file = parsePHP('hello.php');

  $response->getBody()->write($file);
});

$app->put('/edit/{user_name}', function (Request $request, Response $response, array $args)
{
  //do not use 'form-data' to test, use 'x-www-form-urlencoded' instead
  $user_name = $args['user_name'];
  $password = $request->getParsedBody()['password'];
  $response->getBody()->write("Edited $user_name's password to $password");
});

/**
 * Functions
 */
function getTemplate($file) {

  ob_start(); // start output buffer

  include $file;
  $template = ob_get_contents(); // get contents of buffer
  ob_end_clean();
  return $template;

}

// Create connection, be careful of variable name conflicts!! This is for D.I.Y principle
function connect_db(){
  $db_servername = "localhost";
  $db_user_name = "root";
  $db_pass = "root";
  $db_name = "internship";
  $conn = new mysqli($db_servername, $db_user_name, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Database Connection failed: " . $conn->connect_error);
  } 

  return $conn;
}

function authenticate($conn, $response, $user_name, $password){
  $sql = "SELECT * FROM users WHERE user_name = '$user_name' AND password = '$password' AND banned = '0'";
  $result = $conn->query($sql);

  if ($result->num_rows === 0) {
    //$response->getBody()->write("Invalid Username or Password, Try again");
    return false;
  } else {
    $row = $result->fetch_assoc();

    $session = new \SlimSession\Helper;
    $session->set('user_id', $row["id"]);
    $session->set('user_name', $row["user_name"]);
    $session->set('user_img', $row["image_src"]);
    $session->set('user_fullname', $row["full_name"]);
    $session->set('is_FB', false);
    
    return true;
  }
}

function endsWith($string, $subString)
{
  $strlen = strlen($string);
  $subStringLength = strlen($subString);

  if ($subStringLength > $strlen) {
      return false;
  }

  return substr_compare($string, $subString, $strlen - $subStringLength, $subStringLength) === 0;
}

function parsePHP($file_path) {
  return getTemplate($file_path);
}

/**
 * Run
 */
$app->run();
