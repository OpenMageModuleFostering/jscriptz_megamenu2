<?php 
/**
 * JScriptz_MegaMenu2 extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category   	JScriptz
 * @package	JScriptz_MegaMenu2
 * @copyright  	Copyright (c) 2013
 * @license	http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Menu Item helper
 *
 * @category	JScriptz
 * @package	JScriptz_MegaMenu2
 */
class JScriptz_MegaMenu2_Helper_Menuitem extends Mage_Core_Helper_Abstract{
	const MENUITEM_ROOT_ID = 1;
	/**
	 * get the root id
	 * @access public
	 * @return int	
	 */
	public function getRootMenuitemId(){
		return self::MENUITEM_ROOT_ID;
	}
	/**
	 * check if breadcrumbs can be used
	 * @access public
	 * @return bool	
	 */
	public function getUseBreadcrumbs(){
		return Mage::getStoreConfigFlag('megamenu2/menuitem/breadcrumbs');
	}
}