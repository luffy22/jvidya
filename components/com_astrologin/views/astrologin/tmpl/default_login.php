<?php
defined('_JEXEC') or die;
?>
<html>
<head>
    <title>Astro Isha Login</title>
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
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
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
    appId      : '220390744824296',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.0' // use version 2.0
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

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      console.log('Successful login for: ' + response.name);
      document.getElementById('status').innerHTML =
        'Thanks for logging in, ' + response.name + '!';
    });
  }
</script>
<script type="text/javascript">
  (function() {
   var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
   po.src = 'https://apis.google.com/js/client:plusone.js';
   var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
 })();
 function signinCallback(authResult) {
  if (authResult['status']['signed_in']) {
    window.location.href = "http://localhost/jvidya/index.php";
    document.getElementById('signinButton').setAttribute('style', 'display: none');
  } else {
    alert("Unable to Validate Login");
    console.log('Sign-in state: ' + authResult['error']);
  }
}
</script>
</head>
<body>
<div class="social-group">
    <div class="fb-label">
        <fb:login-button scope="public_profile,email" data-size="large" onlogin="checkLoginState();">
        </fb:login-button>
        <div id="status">
        </div>
    </div>
    <div class="google-login">
        <span id="signinButton">
  <span
    class="g-signin"
    data-callback="signinCallback"
    data-clientid="330248034275-mb8mt4rfg5ctdkbhvna88kieo5958h1k.apps.googleusercontent.com"
    data-cookiepolicy="single_host_origin"
    data-requestvisibleactions="http://schemas.google.com/AddActivity"
    data-scope="https://www.googleapis.com/auth/plus.login" data-size="small">
  </span>
</span>
    </div>
</div>
<form enctype="application/x-www-form-urlencoded" method="post" class="form-horizontal">
    <fieldset class="fieldscontent">
        <div class="control-group">
            <div class="control-label">Enter Username</div>
            <div class="controls"><input type="text" name="username" id="al_uname" placeholder="Enter your username" /></div>
        </div>
        <div class="control-group">
            <div class="control-label">Enter Password</div>
            <div class="controls"><input type="password" name="password" id="al_passwd" placeholder="Enter your password" /></div>
        </div>
        <div  class="control-group">
                <div class="control-label"><label>Remember Me</label></div>
                <div class="controls"><input id="al_remember" type="checkbox" name="remember" class="inputbox" value="yes"/></div>
        </div>
        <div class="control-group">
            <div class="controls">
                <button type="submit" name="login" class="btn btn-primary" onclick="javascript:validateLogin();return false;">Login</button>
                <button type="reset" name="cancel" class="btn btn-navbar">Cancel</button>
            </div>
        </div>
    </fieldset>
</form>
</body>
</html>
