<?php
	/* 
	 * Bootstrap Menudrop for bootstrap dropdown
	 * @Author Mugiwara Luffy
	 * @Date	2014/02/06 (yyyy/mm/dd)
	 * @version 1.0
	*/
	
	defined('_JEXEC') or die;
	// Include function only once
	require_once __DIR__ . '/helper.php';
	
        //$activemenu     = ModMenuHelper::getActive($params);
	$menuID         = modMenuDropHelper::getActiveId($params);
        //$menuBase       = modMenuDropHelper::getBase($params);
        $menuTitle      = modMenuDropHelper::getTitle($params);
        //$menuItems      = modMenuDropHelper::getDefault();
        $list           = modMenuDropHelper::getList($params);
        require( JModuleHelper::getLayoutPath( 'mod_menudrop' ));
