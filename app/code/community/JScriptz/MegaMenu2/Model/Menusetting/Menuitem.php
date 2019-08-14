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
 * Menu Setting menu item model
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Model_Menusetting_Menuitem extends Mage_Core_Model_Abstract{
	/**
	 * Initialize resource
	 * @access protected
	 * @return void
	 * @author Jason Lotzer
	 */
	protected function _construct(){
		$this->_init('megamenu2/menusetting_menuitem');
	}
	/**
	 * Save data for menusetting - menuitem relation
	 * @access public
	 * @param  JScriptz_MegaMenu2_Model_Menusetting $menusetting
	 * @return JScriptz_MegaMenu2_Model_Menusetting_Menuitem
	 * @author Jason Lotzer
	 */
	public function saveMenusettingRelation($menusetting){
		$data = $menusetting->getMenuitemsData();
		if (!is_null($data)) {
			$this->_getResource()->saveMenusettingRelation($menusetting, $data);
		}
		return $this;
	}
	/**
	 * get menuitems for menusetting
	 * @access public
	 * @param JScriptz_MegaMenu2_Model_Menusetting $menusetting
	 * @return JScriptz_MegaMenu2_Model_Resource_Menusetting_Menuitem_Collection
	 * @author Jason Lotzer
	 */
	public function getMenuitemsCollection($menusetting){
		$collection = Mage::getResourceModel('megamenu2/menusetting_menuitem_collection')
			->addMenusettingFilter($menusetting);
		return $collection;
	}
}
