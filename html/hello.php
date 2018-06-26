<?php
$session = new \SlimSession\Helper;
?>

<html>
  <head>
    <title>Hi <?php echo $session["user_name"]; ?>!</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
  </head>

  <body>
      <div id="fb-root">
        <p>Hello, 
          <b id="the_user_name"> <?php echo $session["user_name"]; ?> </b>
          <img id="profile_pic" src="
          <?php 
            if ($session["user_img"] === null) {
              echo '../assets/images/unknown.jpg';
            } else {
              echo $session["user_img"];
            }
          ?>
          " alt="profile picture" width="50" height="50"></p>
      </div>

    <form method="post" action="logout">
      <input type="submit" name="test" id="test" value="Logout" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
    </form>

  </body>

</html>

<?php

function testfun()
{
   echo "Your test function on button click is working";
}

if(array_key_exists('test',$_POST)){
   testfun();
}

?>