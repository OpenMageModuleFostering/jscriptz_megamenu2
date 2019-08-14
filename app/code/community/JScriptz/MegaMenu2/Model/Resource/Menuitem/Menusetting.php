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
 * Menu Item - Menu Setting relation model
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Model_Resource_Menuitem_Menusetting extends Mage_Core_Model_Resource_Db_Abstract{
	/**
	 * initialize resource model
	 * @access protected
	 * @return void
	 * @see Mage_Core_Model_Resource_Abstract::_construct()
	 * @author Jason Lotzer
	 */
	protected function  _construct(){
		$this->_init('megamenu2/menuitem_menusetting', 'rel_id');
	}
	/**
	 * Save menuitem - menusetting relations
	 * @access public
	 * @param JScriptz_MegaMenu2_Model_Menuitem $menuitem
	 * @param array $data
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem_Menusetting
	 * @author Jason Lotzer
	 */
	public function saveMenuitemRelation($menuitem, $data){
		if (!is_array($data)) {
			$data = array();
		}
		$deleteCondition = $this->_getWriteAdapter()->quoteInto('menuitem_id=?', $menuitem->getId());
		$this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

		foreach ($data as $menusettingId => $info) {
			$this->_getWriteAdapter()->insert($this->getMainTable(), array(
				'menuitem_id'  	=> $menuitem->getId(),
				'menusetting_id' 	=> $menusettingId,
				'position'  	=> @$info['position']
			));
		}
		return $this;
	}
}
