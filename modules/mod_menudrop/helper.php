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
           $active      = self::getActive($params);
           $base        = JFactory::getApplication()->getMenu()->getItem($active);
           return $base;
        }

        public static function getActiveId(&$params)
        {
            $active     = JFactory::getApplication()->getMenu()->getActive();
            $activeid   = $active->id;
            return $activeid;
        }
	public static function getActive(&$params)
	{
            $active = JFactory::getApplication()->getMenu()->getActive();
            $title  = $active->title;
            return $title;
	}
      
        public static function getList($params)
        {
           $menu          = JFactory::getApplication()->getMenu();
           // base method
                    
           //$path        = $base->tree;
           $start       = (int) $params->get('startLevel');
           $end         = (int) $params->get('endLevel');
           $showAll     = $params->get('showAllChildren');
           $items       = $menu->getItems('menutype', $params->get('menutype'));
           $lastitem    = 0;
           
           
           return $items;
           
       
        }
}
	 

