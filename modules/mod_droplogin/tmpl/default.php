<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_SITE.'/components/com_users/helpers/route.php';

JHtml::_('behavior.keepalive');
JHtml::_('bootstrap.tooltip');

?>
<div id="login-link">
    <div class="btn"><a href="#" onclick="javascrit:showLogin();">Login</a></div>
</div>
<form id="login-form" class="form-inline">
    <div class="control-group">
        <div class="control-label">Enter Username or Email</div>
        <div class="controls"><input type="text" name="moduname" id="mod-uname" placeholder="Enter Username or Email" /></div>
    </div>
    <div class="control-group">
        <div class="control-label">Enter Password</div>
        <div class="controls"><input type="password" name="modpwd" id="mod-pwd" placeholder="Enter your password" /></div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button type="button" class="btn btn-primary" onclick="javascript:LoginUser()">Login</button>
            <button type="button" class="btn" onclick="hideLogin();">Cancel</button>
        </div>
    </div>
</form>
