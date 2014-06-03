<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

JHtml::_('behavior.caption');
?>
<div class="category-list<?php echo $this->pageclass_sfx;?>">

<?php
if($this->category->id	== '10')
{
	$this->subtemplatename = 'articles2';
}
else if($this->category->id == '11')
{
	$this->subtemplatename = 'articles3';
}
else if($this->category->id == '12')
{
	$this->subtemplatename = 'articles4';
}
else if($this->category->id == '13')
{
	$this->subtemplatename = 'articles5';
}
else
{
	$this->subtemplatename = 'articles';
}
echo JLayoutHelper::render('joomla.content.category_default', $this);
?>

</div>
