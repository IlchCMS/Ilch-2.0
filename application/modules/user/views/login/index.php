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
<!--
  Below we include the Login Button social plugin. This button uses
  the JavaScript SDK to present a graphical Login button that triggers
  the FB.login() function when clicked.
-->

<?php if($this->getUser() == null): ?>
<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><?=$this->getTrans('menuLogin') ?></h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-5" >
                <a href="#"><img src="<?=$this->getModuleUrl('static/images/facebook/fb.png') ?>"/></a><br/>
                <a href="#"><img src="<?=$this->getModuleUrl('static/images/google/gplus.png') ?>" /></a><br/>
                <a href="#"><img src="<?=$this->getModuleUrl('static/images/twitter/tw.png') ?>" /></a>
            </div>
            <div class="col-md-7" style="border-left:1px solid #ccc;height:160px">
                <form class="form-horizontal" action="" method="post">
                    <?=$this->getTokenField() ?>
                    <?php $errors = $this->get('errors'); ?>
                    <fieldset>
                        <div class="form-group <?php if (!empty($errors['loginContent_emailname'])) { echo 'has-error'; }; ?>">
                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-user"></i></span>
                                <input class="form-control"
                                       name="loginContent_emailname"
                                       type="text"
                                       placeholder="<?=$this->getTrans('nameEmail') ?>" />
                                <?php if (!empty($errors['loginContent_emailname'])): ?>
                                    <span class="help-inline"><?=$this->getTrans($errors['loginContent_emailname']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>         
                        <!--div class="spacing"><input type="checkbox" name="checkboxes" id="checkboxes-0" value="1"><small> Remember me</small></div-->                    
                        <div class="form-group <?php if (!empty($errors['loginContent_password'])) { echo 'has-error'; }; ?>">
                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-lock"></i></span>
                                <input class="form-control"
                                       name="loginContent_password"
                                       type="password"
                                       placeholder="<?=$this->getTrans('password') ?>" />
                                <?php if (!empty($errors['loginContent_password'])): ?>
                                    <span class="help-inline"><?=$this->getTrans($errors['loginContent_password']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                  
                        <div class="spacing">
                            <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'login', 'action' => 'forgotpassword')) ?>"><?=$this->getTrans('forgotPassword') ?></a><br />
                            <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'regist', 'action' => 'confirm')) ?>">Die Aktivierung Manuell freischalten</a>
                        </div>
                        <!--fb:login-button class="btn" scope="public_profile,email" onlogin="checkLoginState();"></fb:login-button-->
                        <input type="submit" 
                               name="login" 
                               class="btn btn-info btn-sm pull-right" 
                               value="<?=$this->getTrans('login') ?>" />
                    </fieldset>
                </form>
            </div>  
        </div>  
    </div>
    <div class="panel-footer">
    <?php if ($this->get('regist_accept') == '1'): ?>
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
    </div>
</div>
<?php endif; ?>