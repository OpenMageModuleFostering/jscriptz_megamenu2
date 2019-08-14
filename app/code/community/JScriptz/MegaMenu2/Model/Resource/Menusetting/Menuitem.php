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
 * Menu Setting - Menu Item relation model
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Model_Resource_Menusetting_Menuitem extends Mage_Core_Model_Resource_Db_Abstract{
	/**
	 * initialize resource model
	 * @access protected
	 * @return void
	 * @see Mage_Core_Model_Resource_Abstract::_construct()
	 * @author Jason Lotzer
	 */
	protected function  _construct(){
		$this->_init('megamenu2/menusetting_menuitem', 'rel_id');
	}
	/**
	 * Save menusetting - menuitem relations
	 * @access public
	 * @param JScriptz_MegaMenu2_Model_Menusetting $menusetting
	 * @param array $data
	 * @return JScriptz_MegaMenu2_Model_Resource_Menusetting_Menuitem
	 * @author Jason Lotzer
	 */
	public function saveMenusettingRelation($menusetting, $menuitemIds){
		if (is_null($menuitemIds)){
			return $this;
		}
		$oldMenuitems = $menusetting->getSelectedMenuitems();
		$oldMenuitemIds = array();
		foreach ($oldMenuitems as $menuitem){
			$oldMenuitemIds[] = $menuitem->getId();
		}
		$insert = array_diff($menuitemIds, $oldMenuitemIds);
		$delete = array_diff($oldMenuitemIds, $menuitemIds);
		$write = $this->_getWriteAdapter();
		if (!empty($insert)) {
			$data = array();
			foreach ($insert as $menuitemId) {
				if (empty($menuitemId)) {
					continue;
				}
				$data[] = array(
					'menuitem_id' => (int)$menuitemId,
					'menusetting_id'  => (int)$menusetting->getId(),
					'position'=> 1
				);
			}
			if ($data) {
				$write->insertMultiple($this->getMainTable(), $data);
			}
		}
		if (!empty($delete)) {
			foreach ($delete as $menuitemId) {
				$where = array(
					'menusetting_id = ?'  => (int)$menusetting->getId(),
					'menuitem_id = ?' => (int)$menuitemId,
				);
				$write->delete($this->getMainTable(), $where);
			}
		}
		return $this;
	}
}
