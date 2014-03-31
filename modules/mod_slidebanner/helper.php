<?php
defined('_JEXEC') or die;

class ModSlideBannerHelper
{
	public static function &getList(&$params)
	{
		JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_banners/models', 'BannersModel');
		$document	= JFactory::getDocument();
		$app		= JFactory::getApplication();
		
		$model		= JModelLegacy::getInstance('Banners', 'BannersModel', array('ignore_request' => true));
		$model		-> setState('filter.client_id', (int)$params->get('cid'));
		$model		-> setState('filter.category_id', $params->get('catid', array()));
		$model		-> setState('list.limit', (int)$params->get('count', 1));
		$model		-> setState('list.start', 0);
		
		$banners	= $model->getItems();
		$model		-> impress();
		
		return $banners;
	}
}
?>
