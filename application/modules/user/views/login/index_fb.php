<?php 
define('FACEBOOK_SDK_V4_SRC_DIR', APPLICATION_PATH .'/modules/user/static/fb/src/Facebook/');
require( APPLICATION_PATH . '/modules/user/static/fb/autoload.php');


// Make sure to load the Facebook SDK for PHP via composer or manually

use Facebook\FacebookSession;
// add other classes you plan to use, e.g.:
use Facebook\FacebookRequest;
use Facebook\GraphUser;
// use Facebook\FacebookRequestException;
use Facebook\FacebookJavaScriptLoginHelper;

FacebookSession::setDefaultApplication('1445074349146968', '9517fbb42e077486ff06e3276a2f49e7');

// Add `use Facebook\FacebookJavaScriptLoginHelper;` to top of file
$helper = new FacebookJavaScriptLoginHelper();
try {
  $session = $helper->getSession();
  //print_r($helper);
} catch(FacebookRequestException $ex) {
  // When Facebook returns an error
  
} catch(\Exception $ex) {
  // When validation fails or other local issues
  
}
if ($session) {
  // Logged in
}




// Add `use Facebook\FacebookRequest;` to top of file
//$request = new FacebookRequest($session, 'GET', '/me');
//$response = $request->execute();
//$graphObject = $response->getGraphObject();

?>
<html>
<head>
<title>Facebook Login JavaScript Example</title>
<meta charset="UTF-8">
</head>
<body>
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
	if (response.status === 'connected') {
		console.log(response.authResponse.accessToken);
	  }
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '1445074349146968',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.2' // use version 2.2
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
	  console.log(JSON.stringify(response));
      document.getElementById('status').innerHTML =
        'Thanks for logging in, ' + response.name + '!';
    });
  }
  
  FB.login(function(response) {
	  if (response.status === 'connected') {
		// Logged into your app and Facebook.
	  } else if (response.status === 'not_authorized') {
		// The person is logged into Facebook, but not your app.
	  } else {
		// The person is not logged into Facebook, so we're not sure if
		// they are logged into this app or not.
	  }
	  
	  
		console.log(response);
	  
	},
	  {
		scope: 'user_likes',
		auth_type: 'rerequest'
	  });
	
	FB.logout(function(response) {
        // Person is now logged out
    });
  
  
</script>

<!--
  Below we include the Login Button social plugin. This button uses
  the JavaScript SDK to present a graphical Login button that triggers
  the FB.login() function when clicked.
-->



<div id="status">
</div>

</body>
</html>
<?php




if($this->getUser() == null): ?>
    <legend><?=$this->getTrans('menuLogin') ?></legend>
    <form class="form-horizontal" action="" method="post">
        <?=$this->getTokenField() ?>
        <?php $errors = $this->get('errors'); ?>
        <div class="form-group <?php if (!empty($errors['loginContent_emailname'])) { echo 'has-error'; }; ?>">
            <label for="loginContent_emailname" class="col-lg-2 control-label">
                <?=$this->getTrans('nameEmail') ?>:
            </label>
            <div class="col-lg-8">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-user"></i></span>
                    <input class="form-control"
                           name="loginContent_emailname"
                           type="text" />
                    <?php if (!empty($errors['loginContent_emailname'])): ?>
                        <span class="help-inline"><?=$this->getTrans($errors['loginContent_emailname']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="form-group <?php if (!empty($errors['loginContent_password'])) { echo 'has-error'; }; ?>">
            <label class="col-lg-2 control-label">
                <?=$this->getTrans('password') ?>:
            </label>
            <div class="col-lg-8">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-lock"></i></span>
                    <input class="form-control"
                           name="loginContent_password"
                           type="password" />
                    <?php if (!empty($errors['loginContent_password'])): ?>
                        <span class="help-inline"><?=$this->getTrans($errors['loginContent_password']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        
        <div class="clearfix">   
            <!--            
            <div class="col-lg-offset-2 col-lg-8 pull-left">
                <label class="checkbox-inline">
                    <input type="checkbox" 
                           name="regist_confirm"
                           id="regist_confirm_yes" 
                           value="1" />
                           <label for="regist_confirm">Mich bei jedem Besuch automatisch anmelden</label>
                </label>
            </div>
            -->
            <div class="col-lg-10" align="right">
				<fb:login-button class="btn" scope="public_profile,email" onlogin="checkLoginState();">
</fb:login-button>
                <input type="submit" 
                       name="login" 
                       class="btn" 
                       value="<?=$this->getTrans('login') ?>" />
            </div>
        </div>  
    </form>
    <div class="col-lg-offset-2 col-lg-8">
            <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'login', 'action' => 'forgotpassword')) ?>"><?=$this->getTrans('forgotPassword') ?></a><br />
            <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'regist', 'action' => 'confirm')) ?>">Die Aktivierung Manuell freischalten</a>

    </div>
    <?php if ($this->get('regist_accept') == '1'): ?>
        <br /><br /><br />
        <legend><?=$this->getTrans('menuRegist') ?></legend>
        <p>
            Die Registrierung ist in wenigen Augenblicken erledigt und ermöglicht ihnen, auf weitere Funktionen zuzugreifen. Die Administration kann registrierten Benutzern auch zusätzliche Berechtigungen zuweisen.
        </p>
        <p>
            <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'regist', 'action' => 'index')) ?>" class="btn btn-default pull-left">
                <?=$this->getTrans('register') ?>
            </a>
        </p>
    <?php endif; ?>
<?php endif; ?>
