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
 * Menu Item collection resource model
 *
 * @category	JScriptz
 * @package	JScriptz_MegaMenu2
 */
class JScriptz_MegaMenu2_Model_Resource_Menuitem_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract{
	protected $_joinedFields = array();
	/**
	 * constructor
	 * @access public
	 * @return void
	 */
	public function _construct(){
		parent::_construct();
		$this->_init('megamenu2/menuitem');
		$this->_map['fields']['store'] = 'store_table.store_id';
	}
	/**
	 * get menuitems as array
	 * @access protected
	 * @param string $valueField
	 * @param string $labelField
	 * @param array $additional
	 * @return array
	 */
	protected function _toOptionArray($valueField='entity_id', $labelField='linktitle', $additional=array()){
		return parent::_toOptionArray($valueField, $labelField, $additional);
	}
	/**
	 * get options hash
	 * @access protected
	 * @param string $valueField
	 * @param string $labelField
	 * @return array
	 */
	protected function _toOptionHash($valueField='entity_id', $labelField='linktitle'){
		return parent::_toOptionHash($valueField, $labelField);
	}
	/**
	 * Add filter by store
	 * @access public
	 * @param int|Mage_Core_Model_Store $store
	 * @param bool $withAdmin
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem_Collection
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
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem_Collection
	 */
	protected function _renderFiltersBefore(){
		if ($this->getFilter('store')) {
			$this->getSelect()->join(
				array('store_table' => $this->getTable('megamenu2/menuitem_store')),
				'main_table.entity_id = store_table.menuitem_id',
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
	 */
	public function getSelectCountSql(){
		$countSelect = parent::getSelectCountSql();
		$countSelect->reset(Zend_Db_Select::GROUP);
		return $countSelect;
	}
	/**
	 * Add Id filter
	 * @access public
	 * @param array $menuitemIds
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem_Collection
	 */
	public function addIdFilter($menuitemIds){
		if (is_array($menuitemIds)) {
			if (empty($menuitemIds)) {
				$condition = '';
			} 
			else {
				$condition = array('in' => $menuitemIds);
			}
		} 
		elseif (is_numeric($menuitemIds)) {
			$condition = $menuitemIds;
		} 
		elseif (is_string($menuitemIds)) {
			$ids = explode(',', $menuitemIds);
			if (empty($ids)) {
				$condition = $menuitemIds;
			} 
			else {
				$condition = array('in' => $ids);
			}
		}
		$this->addFieldToFilter('entity_id', $condition);
		return $this;
	}
	/**
	 * Add menu item path filter
	 * @access public
	 * @param string $regexp
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem_Collection
	 */
	public function addPathFilter($regexp){
		$this->addFieldToFilter('path', array('regexp' => $regexp));
		return $this;
	}

	/**
	 * Add menu item path filter
	 * @access public
	 * @param array|string $paths
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem_Collection
	 */
	public function addPathsFilter($paths){
		if (!is_array($paths)) {
			$paths = array($paths);
		}
		$write  = $this->getResource()->getWriteConnection();
		$cond   = array();
		foreach ($paths as $path) {
			$cond[] = $write->quoteInto('e.path LIKE ?', "$path%");
		}
		if ($cond) {
			$this->getSelect()->where(join(' OR ', $cond));
		}
		return $this;
	}
	/**
	 * Add menu item level filter
	 * @access public
	 * @param int|string $level
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem_Collection
	 */
	public function addLevelFilter($level){
		$this->addFieldToFilter('level', array('lteq' => $level));
		return $this;
	}
	/**
	 * Add root menu item filter
	 * @access public
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem_Collection	 */
	public function addRootLevelFilter(){
		$this->addFieldToFilter('path', array('neq' => '1'));
		$this->addLevelFilter(1);
		return $this;
	}
	/**
	 * Add order field
	 * @access public
	 * @param string $field
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem_Collection	 */
	public function addOrderField($field){
		$this->setOrder($field, self::SORT_ORDER_ASC);
		return $this;
	}
	/**
	 * Add active menu item filter
	 * @access public
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem_Collection	 */
	public function addStatusFilter(){
		$this->addFieldToFilter('status', 1);
		return $this;
	}
}