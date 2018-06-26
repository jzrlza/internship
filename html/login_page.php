<?php
$session = new \SlimSession\Helper;
?>

<html>
  <head>
    <title>Login Please</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    <script>  
        function statusChangeCallback(response) {
          console.log("statusChangeCallback");
          console.log(response);

          if (response.status === "connected") {
            // Logged into your app and Facebook.
            runAPI();
          } else {
            // The person is not logged into your app or we are unable to tell.
          }
        }
      
        // This function is called when someone finishes with the Login
        // Button.  See the onlogin handler attached to it in the sample
        // code below.
        function checkLoginState() {
          FB.getLoginStatus(function(response) {
            statusChangeCallback(response);
          });
        }
      
        window.fbAsyncInit = function() {
          FB.init({
            appId      : "400378810456321",
            cookie     : true,  // enable cookies to allow the server to access the session
            xfbml      : true,  // parse social plugins on this page
            version    : "v3.0" // use graph api version 2.8
          });
      
          FB.getLoginStatus(function(response) {
            statusChangeCallback(response);
          });

          FB.Event.subscribe("auth.login", function(response) {
            window.location.reload();
          });
      
        };

        var username;

        // Here we run a very simple test of the Graph API after login is
        // successful.  See statusChangeCallback() for when this call is made.
        function runAPI() {
          console.log("Welcome!  Fetching your information.... ");
          FB.api("/me", function(response) {
            console.log("Successful login for: " + response.name);

              //$session->set('user_id', response.id);
              //$session->set('user_name', response.name);
              username = response.name;
            
          });
          FB.api('me/picture?redirect=false',function(response) {
            
              //$session->set('user_img', response.data.url);
              //$session->set('user_fullname', null);
            
          });

          window.location.href = "login.php/login_facebook?fb_user_name="+username;
        }
      
      </script>
  </head>

  <body>
      <div id="fb-root"></div>
      <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0&appId=400378810456321&autoLogAppEvents=1";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, "script", "facebook-jssdk"));
      </script>

    <h3>Login Please</h3>

    <form method="post" action="login.php/login">
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

      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored">
        Login
      </button>
    </form>

    <form action="login.php/regis">
      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
        Register
      </button>
    </form>

    <br><br>

    <div class="fb-login-button" data-max-rows="1" data-size="large" data-button-type="continue_with" data-show-faces="false" data-auto-logout-link="true" data-use-continue-as="true" action="statusChangeCallback(response)"></div>

  </body>
</html>