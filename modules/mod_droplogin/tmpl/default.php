<?php
defined('_JEXEC') or die;

?>
<div id="login-link">
    <a href="#" onclick="javascrit:showLogin();">Login</a>
</div>
<div id="login-form">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="javascript:hideLogin();">&times;</button>
    <div id="form-login-submit" class="control-group">
        <label>Username</label>
        <input type="text" placeholder="Enter your Username…" />
        <div class="form-actions">
                <button type="submit" tabindex="0" name="Submit" class="btn btn-primary"><?php echo JText::_('JLOGIN') ?></button>
                <button class="cancel" tabindex="3" name="Cancel" class="btn btn-danger" onclick="javascript:hideLogin();">Cancel</button>
        </div>
    </div>
</div>