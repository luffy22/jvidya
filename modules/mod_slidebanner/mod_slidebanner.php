<?php
defined('_JEXEC') or die;

//Include the syndicate function only once
require_once __DIR__.'/helper.php';

require_once JPATH_ADMINISTRATOR . '/components/com_banners/helpers/banners.php';
BannersHelper::updateReset();
$list = &ModSlideBannerHelper::getList($params);

require JModuleHelper::getLayoutPath('mod_slidebanner', $params->get('layout', 'default'));
?>
