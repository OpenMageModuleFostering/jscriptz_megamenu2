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
 * Menu Setting - product relation model
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Model_Resource_Menusetting_Product extends Mage_Core_Model_Resource_Db_Abstract{
/**
	 * initialize resource model
	 * @access protected
	 * @return void
	 * @see Mage_Core_Model_Resource_Abstract::_construct()
	 * @author Jason Lotzer
	 */
	protected function  _construct(){
		$this->_init('megamenu2/menusetting_product', 'rel_id');
	}
	/**
	 * Save menusetting - product relations
	 * @access public
	 * @param JScriptz_MegaMenu2_Model_Menusetting $menusetting
	 * @param array $data
	 * @return JScriptz_MegaMenu2_Model_Resource_Menusetting_Product
	 * @author Jason Lotzer
	 */
	public function saveMenusettingRelation($menusetting, $data){
		if (!is_array($data)) {
			$data = array();
		}
		$deleteCondition = $this->_getWriteAdapter()->quoteInto('menusetting_id=?', $menusetting->getId());
		$this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

		foreach ($data as $productId => $info) {
			$this->_getWriteAdapter()->insert($this->getMainTable(), array(
				'menusetting_id'  	=> $menusetting->getId(),
				'product_id' 	=> $productId,
				'position'  	=> @$info['position']
			));
		}
		return $this;
	}
	/**
	 * Save  product - menusetting relations
	 * @access public
	 * @param Mage_Catalog_Model_Product $prooduct
	 * @param array $data
	 * @return JScriptz_MegaMenu2_Model_Resource_Menusetting_Product
	 * @@author Jason Lotzer
	 */
	public function saveProductRelation($product, $data){
		if (!is_array($data)) {
			$data = array();
		}
		$deleteCondition = $this->_getWriteAdapter()->quoteInto('product_id=?', $product->getId());
		$this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);
		
		foreach ($data as $menusettingId => $info) {
			$this->_getWriteAdapter()->insert($this->getMainTable(), array(
				'menusetting_id' => $menusettingId,
				'product_id' => $product->getId(),
				'position'   => @$info['position']
			));
		}
		return $this;
	}
}
