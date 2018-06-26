<html>
  <head>
    <title>Login Please</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    <script>
        // This is called with the results from from FB.getLoginStatus().
        function statusChangeCallback(response) {
          console.log('statusChangeCallback');
          console.log(response);
          // The response object is returned with a status field that lets the
          // app know the current login status of the person.
          // Full docs on the response object can be found in the documentation
          // for FB.getLoginStatus().
          if (response.status === 'connected') {
            // Logged into your app and Facebook.
            testAPI();
          } else {
            // The person is not logged into your app or we are unable to tell.
            //document.getElementById('status').innerHTML = 'Please log ' +
              //'into this app.';
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
            appId      : '400378810456321',
            cookie     : true,  // enable cookies to allow the server to access 
                                // the session
            xfbml      : true,  // parse social plugins on this page
            version    : 'v3.0' // use graph api version 2.8
          });
      
          // Now that we've initialized the JavaScript SDK, we call 
          // FB.getLoginStatus().  This function gets the state of the
          // person visiting this page and can return one of three states to
          // the callback you provide.  They can be:
          //
          // 1. Logged into your app ('connected')
          // 2. Logged into Facebook, but not your app ('not_authorized')
          // 3. Not logged into Facebook and can't tell if they are logged into
          //    your app or not.
          //
          // These three cases are handled in the callback function.
      
          FB.getLoginStatus(function(response) {
            statusChangeCallback(response);
          });

          FB.Event.subscribe("auth.login", function(response) {
            // Reload the same page
            window.location.reload();
            // Or uncomment the following line to redirect
            //window.location = "https://ykyuen.wordpress.com";
          });
      
        };
      
      </script>
  </head>

  <body>
      <div id="fb-root"></div>
      <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0&appId=400378810456321&autoLogAppEvents=1';
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));</script>

    <h3>Login Please</h3>

    <form method="post" action="login.php/login">
      <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        <input class="mdl-textfield__input" type="text" id="user_name" name="user_name" required>
        <label class="mdl-textfield__label" for="user_name">Username</label>
      </div>

      <br><br>

      <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        <input class="mdl-textfield__input" type="text" id="password" name="password" required>
        <label class="mdl-textfield__label" for="password">Password</label>
      </div>
    
      <br><br>

      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
        Login
      </button>
    </form>

    <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
      Register
    </button>

    <br><br>

    <!--
    <form method="post" action="loginFB">
      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored" id="fb_login_button">
        Login Using Facebook
      </button>
    </form>
    -->

    <div class="fb-login-button" data-max-rows="1" data-size="large" data-button-type="continue_with" data-show-faces="false" data-auto-logout-link="true" data-use-continue-as="true" action="statusChangeCallback(response)"></div>

  </body>
  <script>
      // Here we run a very simple test of the Graph API after login is
      // successful.  See statusChangeCallback() for when this call is made.
        function testAPI() {
          console.log('Welcome!  Fetching your information.... ');
          FB.api('/me', function(response) {
            console.log('Successful login for: ' + response.name);
            if (window.location.href.endsWith("/")) {
              window.location.replace('login');
            } else {
              window.location.replace('login.php/login');
            }
            //document.getElementById('fb_login_button').innerHTML = 'Continue as ' + response.name;
          });
        }
  </script>
</html>