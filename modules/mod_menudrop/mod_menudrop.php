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
	
	$menuActive     = modMenuDropHelper::getActive($params );
        $menuActiveId   = modMenuDropHelper::getActiveId($params);
        $menuBase       = modMenuDropHelper::getBase($params);
        //$menutitle      = modMenuDropHelper::getDefault();
        //$menuItems      = modMenuDropHelper::getDefault();
        $list           = modMenuDropHelper::getList($params);
        require( JModuleHelper::getLayoutPath( 'mod_menudrop' ));
