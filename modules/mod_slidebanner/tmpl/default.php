<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_banners
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_ROOT . '/components/com_banners/helpers/banner.php';
$baseurl = JUri::base();
?>
<div class="bannergroup" id="slideshow">
<?php 
    foreach($list as $item) :
    $link = JRoute::_('index.php?option=com_banners&task=click&id='. $item->id);
    $imageurl   = $item->params->get('imageurl');
    $width      = $item->params->get('width');
    $height     = $item->params->get('height');
    $alt        = $item->params->get('alt');
    $alt        = $alt ? $alt : $item->name;

    $imagedir   = dirname($imageurl);
    $images     = scandir($imagedir);
?>
<?php
    foreach($images as $img)
    {
        if($img == '.'|| $img == '..')
        {
            continue;
        }
        if((preg_match('/.jpg/', $img))||(preg_match('/.gif/', $img)) || (preg_match('/.png/', $img)))
        {
        ?>
        <div>
        <img src= "<?php echo $baseurl.$imagedir.'/'.$img ?>" alt = "<?php echo $alt; ?>" title="<?php echo $alt ?>"
                />
        </div>
       <?php
        }
        else
        {
            continue;
        }
    }
?>
    </div>
<?php endforeach; ?>
<?php return; ?>
</div>

