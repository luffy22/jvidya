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
	/*public static function getBase(&$params)
	{
           // Get base menu item from parameters
		if ($params->get('base'))
		{
			$base = JFactory::getApplication()->getMenu()->getItem($params->get('base'));
		}
		else
		{
			$base = false;
		}

		// Use active menu item if no base found
		if (!$base)
		{
			$base = self::getActive($params);
		}

		return $base;
        }
        */
        public static function getActiveId($params)
        {
            $menu          = JFactory::getApplication()->getMenu();
            $items      = $menu->getItems('menutype', $params->get('menutype'));
            
            $current    = $items['0'];       // Gets the base item in array
            
            // Database Query
            /*$db         = JFactory::getDbo();
            $query      = $db->getQuery(true);
            $query      = "SELECT * FROM '#_menu' WHERE 'menutype'=".$current;
            $db         -> setQuery($query);
            $result     = $db->loadAssoc();
            
            $getActiveId    = $result['id'];
            return gettype($getActiveId);*/
            return $current->id;
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
        public static function getTitle($params)
        {
            $title      = (string) $params->get('title');
            
            return $title;
        }
   }     
	 

