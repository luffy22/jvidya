<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<!DOCTYPE html>
<html>
<head>

</head>
<body>
<form enctype="application/x-www-form-urlencoded" method="post" class="form-horizontal"
      action="<?php echo JRoute::_('index.php?option=com_astrologin&task=process.userregister'); ?>">
    <fieldset class="fieldscontent">
        <div class="control-group">
            <div class="control-label">Enter Username</div>
            <div class="controls"><input type="text" name="username" id="ar_uname" placeholder="Enter your username" /></div>
        </div>
        <div class="control-group">
            <div class="control-label">Enter Password</div>
            <div class="controls"><input type="password" name="password" id="ar_passwd" placeholder="Enter your password" /></div>
        </div>
        <div class="control-group">
            <div class="control-label">Confirm Password</div>
            <div class="controls"><input type="password" name="cpassword" id="ar_cpasswd" placeholder="Re-enter your password" /></div>
        </div>
        <div class="control-group">
            <div class="control-label">Enter Valid Email</div>
            <div class="controls"><input type="email" name="email" id="ar_email" placeholder="Enter Valid Email" /></div>
        </div>
        <div class="control-group">
            <div class="controls">
                <div class="control-label"><strong>Enter Image Value</strong></div>
                <?php
                    JPluginHelper::importPlugin('captcha');
                    $dispatcher = JDispatcher::getInstance();
                    $dispatcher->trigger('onInit','dynamic_recaptcha_1')
                ?>
                <div id="dynamic_recaptcha_1"></div>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <button type="submit" name="register" class="btn btn-primary" onclick="javascript:validateRegister();return false;">Register</button>
                <button type="reset" name="cancel" class="btn btn-navbar">Cancel</button>
            </div>
        </div>
    </fieldset>
</form>
</body>
</html>