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
 * Menu Setting collection resource model
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Model_Resource_Menusetting_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract{
	protected $_joinedFields = array();
	/**
	 * constructor
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function _construct(){
		parent::_construct();
		$this->_init('megamenu2/menusetting');
		$this->_map['fields']['store'] = 'store_table.store_id';
	}
	/**
	 * get menusettings as array
	 * @access protected
	 * @param string $valueField
	 * @param string $labelField
	 * @param array $additional
	 * @return array
	 * @author Jason Lotzer
	 */
	protected function _toOptionArray($valueField='entity_id', $labelField='menutitle', $additional=array()){
		return parent::_toOptionArray($valueField, $labelField, $additional);
	}
	/**
	 * get options hash
	 * @access protected
	 * @param string $valueField
	 * @param string $labelField
	 * @return array
	 * @author Jason Lotzer
	 */
	protected function _toOptionHash($valueField='entity_id', $labelField='menutitle'){
		return parent::_toOptionHash($valueField, $labelField);
	}
	/**
	 * Add filter by store
	 * @access public
	 * @param int|Mage_Core_Model_Store $store
	 * @param bool $withAdmin
	 * @return JScriptz_MegaMenu2_Model_Resource_Menusetting_Collection
	 * @author Jason Lotzer
	 */
	public function addStoreFilter($store, $withAdmin = true){
		if (!isset($this->_joinedFields['store'])){
			if ($store instanceof Mage_Core_Model_Store) {
				$store = array($store->getId());
			}
			if (!is_array($store)) {
				$store = array($store);
			}
			if ($withAdmin) {
				$store[] = Mage_Core_Model_App::ADMIN_STORE_ID;
			}
			$this->addFilter('store', array('in' => $store), 'public');
			$this->_joinedFields['store'] = true;
		}
		return $this;
	}
	/**
	 * Join store relation table if there is store filter
	 * @access protected
	 * @return JScriptz_MegaMenu2_Model_Resource_Menusetting_Collection
	 * @author Jason Lotzer
	 */
	protected function _renderFiltersBefore(){
		if ($this->getFilter('store')) {
			$this->getSelect()->join(
				array('store_table' => $this->getTable('megamenu2/menusetting_store')),
				'main_table.entity_id = store_table.menusetting_id',
				array()
			)->group('main_table.entity_id');
			/*
			 * Allow analytic functions usage because of one field grouping
			 */
			$this->_useAnalyticFunction = true;
		}
		return parent::_renderFiltersBefore();
	}
	/**
	 * Get SQL for get record count.
	 * Extra GROUP BY strip added.
	 * @access public
	 * @return Varien_Db_Select
	 * @author Jason Lotzer
	 */
	public function getSelectCountSql(){
		$countSelect = parent::getSelectCountSql();
		$countSelect->reset(Zend_Db_Select::GROUP);
		return $countSelect;
	}
	/**
	 * add the product filter to collection
	 * @access public
	 * @param mixed (Mage_Catalog_Model_Product|int) $product
	 * @return JScriptz_MegaMenu2_Model_Resource_Menusetting_Collection
	 * @author Jason Lotzer
	 */
	public function addProductFilter($product){
		if ($product instanceof Mage_Catalog_Model_Product){
			$product = $product->getId();
		}
		if (!isset($this->_joinedFields['product'])){
			$this->getSelect()->join(
				array('related_product' => $this->getTable('megamenu2/menusetting_product')),
				'related_product.menusetting_id = main_table.entity_id',
				array('position')
			);
			$this->getSelect()->where('related_product.product_id = ?', $product);
			$this->_joinedFields['product'] = true;
		}
		return $this;
	}
	/**
	 * add the menuitem filter to collection
	 * @access public
	 * @param mixed (JScriptz_MegaMenu2_Model_Menuitem|int) $menuitem
	 * @return JScriptz_MegaMenu2_Model_Resource_Menusetting_Collection
	 * @author Jason Lotzer
	 */
	public function addMenuitemFilter($menuitem){
		if ($menuitem instanceof JScriptz_MegaMenu2_Model_Menuitem){
			$menuitem = $menuitem->getId();
		}
		if (!isset($this->_joinedFields['menuitem'])){
			$this->getSelect()->join(
				array('related_menuitem' => $this->getTable('megamenu2/menusetting_menuitem')),
				'related_menuitem.menusetting_id = main_table.entity_id',
				array('position')
			);
			$this->getSelect()->where('related_menuitem.menuitem_id = ?', $menuitem);
			$this->_joinedFields['menuitem'] = true;
		}
		return $this;
	}
}
