<?php
	/* 
	 * Bootstrap Menudrop for bootstrap dropdown
	 * @Author Mugiwara Luffy
	 * @Date	2014/02/06 (yyyy/mm/dd)
	 * @version 1.0
	*/
	
	defined('_JEXEC') or die;
	// Include function only once
	require_once(dir(__FILE__).'/helper.php');
	
	$list		= modMenuDropHelper::getList($params);
	$base 		= modMenuDropHelper::getBase($params);
	$active		= modMenuDropHelper::getActive($params);
	$active_id	= $active->id;
	$path		= $base->tree;
	
	$showAll	= $params->get('showAllChildren');
	$class_sfx	= htmlspecialchars($params->get('class_sfx'));
	
	if(count($list))
	{
		require JModuleHelper::getLayoutPath('mod_menudrop',$params->get('layout', 'default'));
	}
