<?php
$session = new \SlimSession\Helper;
?>

<html>
  <head>
    <title>Registeration</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    
  </head>

  <body>
    <h3>Registeration</h3>

    <form method="post" action="register" enctype="multipart/form-data">
      <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        <input class="mdl-textfield__input" type="text" id="user_name" name="user_name" required>
        <label class="mdl-textfield__label" for="user_name">Username</label>
      </div>

      <br><br>

      <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        <input class="mdl-textfield__input" type="password" id="password" name="password" required>
        <label class="mdl-textfield__label" for="password">Password</label>
      </div>
    
      <br><br>

      <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        <input class="mdl-textfield__input" type="password" id="password_confirm" name="password_confirm" required>
        <label class="mdl-textfield__label" for="password">Confirm Password</label>
      </div>
    
      <br><br>

      <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        <input class="mdl-textfield__input" type="email" id="email" name="email" required>
        <label class="mdl-textfield__label" for="password">Email</label>
      </div>
    
      <br><br>

      <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        <input class="mdl-textfield__input" type="text" id="full_name" name="full_name">
        <label class="mdl-textfield__label" for="password">Full Name</label>
      </div>
    
      <br><br>

      <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        <input class="mdl-textfield__input" type="text" id="nick_name" name="nick_name">
        <label class="mdl-textfield__label" for="password">Nick Name</label>
      </div>
    
      <br><br>

      <div>
        <b>Profile Image : </b>
        <input type="file" id="image_upload" name="image_upload" class="fileUpload"/>
      </div>

      <br><br>

      <!--//http://www.thaicreate.com/php/php-upload-resize-image.html for image upload and resize-->

      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
        Confirm
      </button>
    </form>

    <form action="../login.php">
      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
        Back
      </button>
    </form>

  </body>
</html>