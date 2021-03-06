<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

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

//Authenticate
function authenticate($conn, $response, $user_name, $password){
  $sql = "SELECT * FROM users WHERE user_name = '$user_name' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows === 0) {
    $response->getBody()->write("Invalid Username or Password, Try again");
    return false;
  } else {
    $row = $result->fetch_assoc();
    $response->getBody()->write("Hello, ". $row["user_name"]);
    $_SESSION['user_name'] = $user_name;
    return true;
  }
}

$app = new \Slim\App;

//http://localhost/internship/html/login.php/hello/12345
$app->get('/', function (Request $request, Response $response, array $args)
{
  return $response->withBody(new \GuzzleHttp\Psr7\LazyOpenStream('login.html', 'r'));
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

$app->post('/register', function (Request $request, Response $response, array $args)
{
  $user_name = $request->getParsedBody()['user_name'];
  $password = $request->getParsedBody()['password'];
  $response->getBody()->write("Registered $user_name. With $password as password");
  return $response;
});

$app->post('/login', function (Request $request, Response $response, array $args)
{
  $user_name = $request->getParsedBody()['user_name'];
  $password = $request->getParsedBody()['password'];

  // Create connection, be careful of variable name conflicts!!
  $conn = connect_db();

  if (authenticate($conn, $response, $user_name, $password)) {
    $conn->close();
    return $response->withBody(new \GuzzleHttp\Psr7\LazyOpenStream('logged_on.php', 'r'));
  } else {
    $conn->close();
    return $response;
  }

  /*
  List all users code
  $sql = "SELECT id, user_name FROM users"; //be careful of typos in name, if it has underscore for example
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      $response->getBody()->write( "id: " . $row["id"]. " - Name: " . $row["user_name"]. "<br>" );
    }
  }
  */

});

$app->get('/login', function (Request $request, Response $response, array $args)
{
  //$user_name = $request->getParsedBody()['user_name'];
  //$password = $request->getParsedBody()['password'];

  return $response->withBody(new \GuzzleHttp\Psr7\LazyOpenStream('logged_on.php', 'r'));
  
});

$app->put('/edit/{user_name}', function (Request $request, Response $response, array $args)
{
  //do not use 'form-data' to test, use 'x-www-form-urlencoded' instead
  $user_name = $args['user_name'];
  $password = $request->getParsedBody()['password'];
  $response->getBody()->write("Edited $user_name's password to $password");
});

$app->run();
