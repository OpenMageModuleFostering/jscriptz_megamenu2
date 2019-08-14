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
 * Menu Item - product relation model
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */

class JScriptz_MegaMenu2_Model_Resource_Menuitem_Product extends Mage_Core_Model_Resource_Db_Abstract{
	
	/**
	 * initialize resource model
	 * @access protected
	 * @return void
	 * @see Mage_Core_Model_Resource_Abstract::_construct()
	 * @author Jason Lotzer
	 */
	protected function  _construct(){
		$this->_init('megamenu2/menuitem_product', 'rel_id');
	}
	/**
	 * Save menu item - product relations
	 * @access public
	 * @param JScriptz_MegaMenu2_Model_Menuitem $menuitem
	 * @param array $data
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem_Product
	 * @author Jason Lotzer
	 */
	public function saveMenuitemRelation($menuitem, $data){
		if (!is_array($data)) {
			$data = array();
		}
		$deleteCondition = $this->_getWriteAdapter()->quoteInto('menuitem_id=?', $menuitem->getId());
		$this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

		foreach ($data as $productId => $info) {
			$this->_getWriteAdapter()->insert($this->getMainTable(), array(
				'menuitem_id'  	=> $menuitem->getId(),
				'product_id' 	=> $productId,
				'position'  	=> @$info['position']
			));
		}
		return $this;
	}
	/**
	 * Save  product - menu item relations
	 * @access public
	 * @param Mage_Catalog_Model_Product $prooduct
	 * @param array $data
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem_Product
	 * @author Jason Lotzer
	 */
	public function saveProductRelation($product, $menuitemIds){
		
		$oldMenuitems = Mage::helper('megamenu2/product')->getSelectedMenuitems($product);
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
					'product_id'  => (int)$product->getId(),
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
					'product_id = ?'  => (int)$product->getId(),
					'menuitem_id = ?' => (int)$menuitemId,
				);
				$write->delete($this->getMainTable(), $where);
			}
		}
		return $this;
	}
}
