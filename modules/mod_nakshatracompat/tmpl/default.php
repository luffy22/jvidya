<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
JHtml::_('behavior.keepalive');
JHtml::_('bootstrap.tooltip');

?>
<div class="message" id="match_message"></div>
<div class="control-group">
    <div class="control-label"><?php echo $girlsMenu; ?></div>
    <div class="controls"><?php echo $boysMenu;  ?></div>
</div>
<div class="control-group">
    <div class="control-label"><?php echo $girlsNakshatra; ?></div>
    <div class="controls"><?php echo $boysNakshatra; ?></div>           
</div>
<div class="control-group">
    <div class="control-label"><?php echo $girlsPada; ?></div>
    <div class="controls"><?php echo $boysPada; ?></div>
</div>
<div class="control-group">
    <div class="controls"><button class="btn btn-primary" id="compat-submit" onclick="checkCompatibility()">Check Compatibility</button></div>
</div>
