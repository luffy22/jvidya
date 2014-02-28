<?php
defined('_JEXEC') or die;

?>
<div id="login-link">
    <a href="#" onclick="javascrit:showLogin();">Login</a>
</div>
<div id="login-form2">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="javascript:hideLogin();">&times;</button>
    <div id="form-login-submit" class="control-group">
        <label>Enter Username or Email</label>
        <input id="modlgn-uname" type="text" placeholder="Enter your Username or Email…" 
               title="Enter your username or email..." tabindex="1"   />
        <p id="uid1">Please enter a valid Username or Email</p>
        <label>Password</label>
        <input id="modlgn-passwd" type="password" placeholder="Enter your Password..." 
               title="Enter your password" tabindex="2" />
        <p id="uid2">Password cannot be empty...</p>
        <div>
                <button type="submit" tabindex="3" name="Submit" class="btn btn-primary" onclick="javascript:validateLoginForm();"><?php echo JText::_('JLOGIN') ?></button>
                <button class="cancel" tabindex="4" name="Cancel" class="btn btn-danger" onclick="javascript:hideLogin();">Cancel</button>
        </div>
    </div>
</div>