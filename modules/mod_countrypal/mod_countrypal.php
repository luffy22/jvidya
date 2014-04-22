<?php

	defined('_JEXEC') or die;
	// Include function only once
	require_once(__DIR__).'/helper.php';
	
	$paypal_donate = ModCountryPalHelper::placeDonateButton();
	
	require JModuleHelper::getLayoutPath('mod_countrypal', $params->get('layout', 'default'));
?>
