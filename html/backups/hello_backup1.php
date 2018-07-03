<?php

require 'facebook-php-sdk-master/src/facebook.php';

$facebook = new Facebook(array('appId', '400378810456321','secret','6bf95c2dd38662f6df9d19a0b48841f0',));

debug_to_console($facebook);

?>

<html>
  <head>
    <title>Hi User!</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    <script>
        // This is called with the results from from FB.getLoginStatus().
        function statusChangeCallback(response) {
          console.log('statusChangeCallback');
          console.log(response);
          if (response.status === 'connected') {
            // Logged into your app and Facebook.
            testAPI();
          } else {
            window.location.replace('/internship/login.php');
          }
        }
      
        function checkLoginState() {
          FB.getLoginStatus(function(response) {
            statusChangeCallback(response);
          });
        }
      
        window.fbAsyncInit = function() {
          FB.init({
            appId      : '400378810456321',
            cookie     : true,  // enable cookies to allow the server to access 
                                // the session
            xfbml      : true,  // parse social plugins on this page
            version    : 'v3.0' // use graph api version 2.8
          });
      
          FB.getLoginStatus(function(response) {
            statusChangeCallback(response);
          });
      
        };

      </script>
  </head>

  <body>
      <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0&appId=400378810456321&autoLogAppEvents=1';
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));</script>

      <div id="fb-root">
        <p>Hello, <b id="the_user_name"></b> <img id="profile_pic" src="" alt="profile picture"></p>
      </div>

      <button onclick="logout();" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
        Logout
      </button>


  </body>
  <script>
      // Here we run a very simple test of the Graph API after login is
      // successful.  See statusChangeCallback() for when this call is made.
        function testAPI() {
          console.log('Welcome!  Fetching your information.... ');
          FB.api('/me', function(response) {
            console.log('Successful login for: ' + response.name);
            document.getElementById('the_user_name').innerHTML = response.name;
            //document.getElementById('fb_login_button').innerHTML = 'Continue as ' + response.name;
          });

          FB.api('me/picture?redirect=false',function(response) {
            document.getElementById('profile_pic').src = response.data.url;
          });
        }

        function logout() {
          FB.getLoginStatus(function(response) {
            if (response.status === 'connected') {
              var accessToken = response.authResponse.accessToken;
            } 
            console.log(accessToken);
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open("GET", "https://api.facebook.com/restserver.php?method=auth.expireSession&format=json&access_token="+accessToken, false);
            xmlHttp.send(null);
            //FB.api('me/permissions?method=delete&access_token='+accessToken, function(response) {
            //  console.log(response);
            //});
          });
        }
  </script>
</html>