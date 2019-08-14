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
 * @package		JScriptz_MegaMenu2
 * @copyright  	Copyright (c) 2013
 * @license		http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Menu Item menu setting model
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Model_Menuitem_Menusetting extends Mage_Core_Model_Abstract{
	/**
	 * Initialize resource
	 * @access protected
	 * @return void
	 * @author Jason Lotzer
	 */
	protected function _construct(){
		$this->_init('megamenu2/menuitem_menusetting');
	}
	/**
	 * Save data for menuitem - menusetting relation
	 * @access public
	 * @param  JScriptz_MegaMenu2_Model_Menuitem $menuitem
	 * @return JScriptz_MegaMenu2_Model_Menuitem_Menusetting
	 * @author Jason Lotzer
	 */
	public function saveMenuitemRelation($menuitem){
		$data = $menuitem->getMenusettingsData();
		if (!is_null($data)) {
			$this->_getResource()->saveMenuitemRelation($menuitem, $data);
		}
		return $this;
	}
	/**
	 * get menusettings for menuitem
	 * @access public
	 * @param JScriptz_MegaMenu2_Model_Menuitem $menuitem
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem_Menusetting_Collection
	 * @author Jason Lotzer
	 */
	public function getMenusettingsCollection($menuitem){
		$collection = Mage::getResourceModel('megamenu2/menuitem_menusetting_collection')
			->addMenuitemFilter($menuitem);
		return $collection;
	}
}
