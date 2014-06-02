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
      action="/jvidya/components/com_astrologin/processRegistration.php">
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
                <img id="captcha" src="/jvidya/securimage/securimage_show.php" alt="CAPTCHA Image" />
                <input type="text" name="captcha_code" size="10" maxlength="6" />
<a href="#" onclick="document.getElementById('captcha').src = '/jvidya/securimage/securimage_show.php?' + Math.random(); return false"><br/>[ Different Image ]</a>
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