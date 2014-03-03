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
    <a href="#" onclick="javascrit:showLogin();">Login</a>
</div>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" class="form-inline">
	<div class="userdata">
		<div id="form-login-username" class="control-group">
			<div class="controls">
				<?php if (!$params->get('usetext')) : ?>
					<div class="input-prepend">
						<span class="add-on">
							<span class="icon-user hasTooltip" title="Enter your username..."></span>
							<label for="modlgn-username" class="element-invisible">Username</label>
						</span>
						<input id="modlgn-username" type="text" name="username" class="input-small" tabindex="0" size="18" placeholder="Enter your username..." />
                                                <p id="uid1">Please enter a valid Username or Email</p>
					</div>
				<?php else: ?>
					<label for="modlgn-username">Username</label>
					<input id="modlgn-username" type="text" name="username" class="input-small" tabindex="0" size="18" placeholder="Enter your username..." />
                                        <p id="uid1">Please enter a valid Username or Email</p>
				<?php endif; ?>
			</div>
		</div>
		<div id="form-login-password" class="control-group">
			<div class="controls">
				<?php if (!$params->get('usetext')) : ?>
					<div class="input-prepend">
						<span class="add-on">
                                                    <span class="icon-lock hasTooltip" title="<?php echo JText::_('JGLOBAL_PASSWORD') ?>"></span>
                                                    <label for="modlgn-passwd" class="element-invisible"><?php echo JText::_('JGLOBAL_PASSWORD'); ?></label>
						</span>
						<input id="modlgn-passwd" type="password" name="password" class="input-small" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />
                                                <p id="uid2">Password cannot be empty...</p>
					</div>
				<?php else: ?>
					<label for="modlgn-passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
					<input id="modlgn-passwd" type="password" name="password" class="input-small" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />
                                        <p id="uid2">Password cannot be empty...</p>
				<?php endif; ?>
			</div>
		</div>
		<?php if (count($twofactormethods) > 1): ?>
		<div id="form-login-secretkey" class="control-group">
			<div class="controls">
				<?php if (!$params->get('usetext')) : ?>
					<div class="input-prepend input-append">
						<span class="add-on">
							<span class="icon-star hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY'); ?>">
							</span>
								<label for="modlgn-secretkey" class="element-invisible"><?php echo JText::_('JGLOBAL_SECRETKEY'); ?>
							</label>
						</span>
						<input id="modlgn-secretkey" type="text" name="secretkey" class="input-small" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_SECRETKEY') ?>" />
						<span class="btn width-auto hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY_HELP'); ?>">
							<span class="icon-help"></span>
						</span>
				</div>
				<?php else: ?>
					<label for="modlgn-secretkey"><?php echo JText::_('JGLOBAL_SECRETKEY') ?></label>
					<input id="modlgn-secretkey" type="text" name="secretkey" class="input-small" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_SECRETKEY') ?>" />
					<span class="btn width-auto hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY_HELP'); ?>">
						<span class="icon-help"></span>
					</span>
				<?php endif; ?>

			</div>
		</div>
		<?php endif; ?>
		<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
		<div id="form-login-remember" class="control-group checkbox">
			<label for="modlgn-remember" class="control-label">Remember Me</label> <input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
		</div>
		<?php endif; ?>
		<div id="form-login-submit" class="control-group">
                    <div class="controls">
                        <button type="submit" tabindex="0" name="Submit" class="btn btn-primary">Login</button>
                        <button class="cancel" tabindex="4" name="Cancel" class="btn btn-danger" onclick="javascript:hideLogin();">Cancel</button>
                    </div>
		</div>
		<?php
                    $usersConfig = JComponentHelper::getParams('com_users'); ?>
                    <ul class="unstyled">
                    <?php if ($usersConfig->get('allowUserRegistration')) : ?>
                            <li>
                                    <a href="<?php echo JRoute::_('index.php?option=com_users&view=registration&Itemid='.UsersHelperRoute::getRegistrationRoute()); ?>">
                                    Create an Account<span class="icon-arrow-right"></span></a>
                            </li>
                    <?php endif; ?>
                            <li>
                                    <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset&Itemid='.UsersHelperRoute::getResetRoute()); ?>">
                                    Forgot your password</a>
                            </li>
                    </ul>
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.login" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	
</form>
