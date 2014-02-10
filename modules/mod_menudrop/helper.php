<?php

/*
 * Helper Class for mod_menudrop
 * @author: Mugiwara Luffy
 * @version: 1.0
 * @date: 2014/02/06 (yyyy/mm/dd)
*/
defined('_JEXEC') or die;
class modMenuDropHelper
{
	/*
	 * Get te list of menu
	 * @return params The list of the menu
	 */
	public static function getBase(&$params)
	{

		// Get default menu - JMenu object, look at JMenu api docs
                $active = JFactory::getApplication()->getMenu()->getActive();
                $link   = $active->link; 
                return $link;
        }


	public static function getActive(&$params)
	{
              $active = JFactory::getApplication()->getMenu()->getActive();
              $title  = $active->title;
              return $title;
	}
}
	 

