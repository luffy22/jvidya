
<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_related_items
 * @override    mod_related_items
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<h3>Related Articles</h3>
<table class="table-striped">
<?php
    $k = count($list);
    foreach ($list as $item) :
?>
    <tr class="table-striped">

        <td>
            <a href="<?php echo $item->route; ?>">
            <?php if ($showDate) echo JHTML::_('date', $item->created, JText::_('DATE_FORMAT_LC4')). " - "; ?>
            <?php echo $item->title; ?></a>
        </td>
    </tr>
<?php
        endforeach;
?>
</table>