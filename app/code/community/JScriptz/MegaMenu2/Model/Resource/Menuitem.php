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
 * Menu Item resource model
 *
 * @category	JScriptz
 * @package	JScriptz_MegaMenu2
 */
class JScriptz_MegaMenu2_Model_Resource_Menuitem extends Mage_Core_Model_Resource_Db_Abstract{
	/**
	 * Menu Item tree object
	 * @var Varien_Data_Tree_Db	 */
	protected $_tree;
	/**
	 * constructor
	 * @access public
	 * @return void
	 */
	public function _construct(){
		$this->_init('megamenu2/menuitem', 'entity_id');
	}
	
	/**
	 * Get store ids to which specified item is assigned
	 * @access public
	 * @param int $menuitemId
	 * @return array
	 */
	public function lookupStoreIds($menuitemId){
		$adapter = $this->_getReadAdapter();
		$select  = $adapter->select()
			->from($this->getTable('megamenu2/menuitem_store'), 'store_id')
			->where('menuitem_id = ?',(int)$menuitemId);
		return $adapter->fetchCol($select);
	}
	/**
	 * Perform operations after object load
	 * @access public
	 * @param Mage_Core_Model_Abstract $object
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem
	 */
	protected function _afterLoad(Mage_Core_Model_Abstract $object){
		if ($object->getId()) {
			$stores = $this->lookupStoreIds($object->getId());
			$object->setData('store_id', $stores);
		}
		return parent::_afterLoad($object);
	}

	/**
	 * Retrieve select object for load object data
	 *
	 * @param string $field
	 * @param mixed $value
	 * @param JScriptz_MegaMenu2_Model_Menuitem $object
	 * @return Zend_Db_Select	 */
	protected function _getLoadSelect($field, $value, $object){
		$select = parent::_getLoadSelect($field, $value, $object);
		if ($object->getStoreId()) {
			$storeIds = array(Mage_Core_Model_App::ADMIN_STORE_ID, (int)$object->getStoreId());
			$select->join(
				array('megamenu2_menuitem_store' => $this->getTable('megamenu2/menuitem_store')),
				$this->getMainTable() . '.entity_id = megamenu2_menuitem_store.menuitem_id',
				array()
			)
			->where('megamenu2_menuitem_store.store_id IN (?)', $storeIds)
			->order('megamenu2_menuitem_store.store_id DESC')
			->limit(1);
		}
		return $select;
	}
	/**
	 * Retrieve menu item tree object
	 * @access protected
	 * @return Varien_Data_Tree_Db
	 */
	protected function _getTree(){
		if (!$this->_tree) {
			$this->_tree = Mage::getResourceModel('megamenu2/menuitem_tree')->load();
		}
		return $this->_tree;
	}
	/**
	 * Process menu item data before delete
	 * update children count for parent menu item
	 * delete child menu items
	 * @access protected
	 * @param Varien_Object $object
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem
	 */
	protected function _beforeDelete(Mage_Core_Model_Abstract $object){
		parent::_beforeDelete($object);
		/**
		 * Update children count for all parent menu items
		 */
		$parentIds = $object->getParentIds();
		if ($parentIds) {
			$childDecrease = $object->getChildrenCount() + 1; // +1 is itself
			$data = array('children_count' => new Zend_Db_Expr('children_count - ' . $childDecrease));
			$where = array('entity_id IN(?)' => $parentIds);
			$this->_getWriteAdapter()->update( $this->getMainTable(), $data, $where);
		}
		$this->deleteChildren($object);
		return $this;
	}
	/**
	 * Delete children menu items of specific menu item
	 * @access public
	 * @param Varien_Object $object
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem
	 */
	public function deleteChildren(Varien_Object $object){
		$adapter = $this->_getWriteAdapter();
		$pathField = $adapter->quoteIdentifier('path');
		$select = $adapter->select()
			->from($this->getMainTable(), array('entity_id'))
			->where($pathField . ' LIKE :c_path');
		$childrenIds = $adapter->fetchCol($select, array('c_path' => $object->getPath() . '/%'));
		if (!empty($childrenIds)) {
			$adapter->delete(
				$this->getMainTable(),
				array('entity_id IN (?)' => $childrenIds)
			);
		}
		/**
		 * Add deleted children ids to object
		 * This data can be used in after delete event
		 */
		$object->setDeletedChildrenIds($childrenIds);
		return $this;
	}
	/**
	 * Process menu item data after save menu item object
	 * @access protected
	 * @param Varien_Object $object
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem
	 */
	protected function _afterSave(Mage_Core_Model_Abstract $object){
		if (substr($object->getPath(), -1) == '/') {
			$object->setPath($object->getPath() . $object->getId());
			$this->_savePath($object);
		}
		$oldStores = $this->lookupStoreIds($object->getId());
		$newStores = (array)$object->getStores();
		if (empty($newStores)) {
			$newStores = (array)$object->getStoreId();
		}
		$table  = $this->getTable('megamenu2/menuitem_store');
		$insert = array_diff($newStores, $oldStores);
		$delete = array_diff($oldStores, $newStores);
		if ($delete) {
			$where = array(
				'menuitem_id = ?' => (int) $object->getId(),
				'store_id IN (?)' => $delete
			);
			$this->_getWriteAdapter()->delete($table, $where);
		}
		if ($insert) {
			$data = array();
			foreach ($insert as $storeId) {
				$data[] = array(
					'menuitem_id'  => (int) $object->getId(),
					'store_id' => (int) $storeId
				);
			}
			$this->_getWriteAdapter()->insertMultiple($table, $data);
		}
		return parent::_afterSave($object);
	}

	/**
	 * Update path field
	 * @access protected
	 * @param JScriptz_MegaMenu2_Model_Menuitem $object
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem
	 */
	protected function _savePath($object){
		if ($object->getId()) {
			$this->_getWriteAdapter()->update(
				$this->getMainTable(),
				array('path' => $object->getPath()),
				array('entity_id = ?' => $object->getId())
			);
		}
		return $this;
	}

	/**
	 * Get maximum position of child menu items by specific tree path
	 * @access protected
	 * @param string $path
	 * @return int
	 */
	protected function _getMaxPosition($path){
		$adapter = $this->getReadConnection();
		$positionField = $adapter->quoteIdentifier('position');
		$level   = count(explode('/', $path));
		$bind = array(
			'c_level' => $level,
			'c_path'  => $path . '/%'
		);
		$select  = $adapter->select()
			->from($this->getMainTable(), 'MAX(' . $positionField . ')')
			->where($adapter->quoteIdentifier('path') . ' LIKE :c_path')
			->where($adapter->quoteIdentifier('level') . ' = :c_level');
		
		$position = $adapter->fetchOne($select, $bind);
		if (!$position) {
			$position = 0;
		}
		return $position;
	}
	/**
	 * Get children menu items count
	 * @access public
	 * @param int $menuitemId
	 * @return int
	 */
	public function getChildrenCount($menuitemId){
		$select = $this->_getReadAdapter()->select()
			->from($this->getMainTable(), 'children_count')
			->where('entity_id = :entity_id');
		$bind = array('entity_id' => $menuitemId);
		return $this->_getReadAdapter()->fetchOne($select, $bind);
	}
	/**
	 * Check if menu item id exist
	 * @access public
	 * @param int $entityId
	 * @return bool
	 */
	public function checkId($entityId){
		$select = $this->_getReadAdapter()->select()
			->from($this->getMainTable(), 'entity_id')
			->where('entity_id = :entity_id');
		$bind =  array('entity_id' => $entityId);
		return $this->_getReadAdapter()->fetchOne($select, $bind);
	}

	/**
	 * Check array of menu items identifiers
	 * @access public
	 * @param array $ids
	 * @return array
	 */
	public function verifyIds(array $ids){
		if (empty($ids)) {
			return array();
		}
		$select = $this->_getReadAdapter()->select()
			->from($this->getMainTable(), 'entity_id')
			->where('entity_id IN(?)', $ids);
		
		return $this->_getReadAdapter()->fetchCol($select);
	}
	/**
	 * Process menu item data before saving
	 * prepare path and increment children count for parent menu items
	 * @access protected
	 * @param Varien_Object $object
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem
	 */
	protected function _beforeSave(Mage_Core_Model_Abstract $object){
		parent::_beforeSave($object);
		if (!$object->getChildrenCount()) {
			$object->setChildrenCount(0);
		}
		if ($object->getLevel() === null) {
			$object->setLevel(1);
		}
		if (!$object->getId() && !$object->getInitialSetupFlag()) {
			$object->setPosition($this->_getMaxPosition($object->getPath()) + 1);
			$path  = explode('/', $object->getPath());
			$level = count($path);
			$object->setLevel($level);
			if ($level) {
				$object->setParentId($path[$level - 1]);
			}
			$object->setPath($object->getPath() . '/');
			$toUpdateChild = explode('/',$object->getPath());
			$this->_getWriteAdapter()->update(
				$this->getMainTable(),
				array('children_count'  => new Zend_Db_Expr('children_count+1')),
				array('entity_id IN(?)' => $toUpdateChild)
			);
		}
		return $this;
	}
	/**
	 * Get count of active/not active children menu items
	 *
	 * @param JScriptz_MegaMenu2_Model_Menuitem $menuitem
	 * @param bool $isActiveFlag
	 * @return int
	 */
	public function getChildrenAmount($menuitem, $isActiveFlag = true){
		$bind = array(
			'active_flag'  => $isActiveFlag,
			'c_path'   => $menuitem->getPath() . '/%'
		);
		$select = $adapter->select()
			->from(array('m' => $this->getMainTable()), array('COUNT(m.entity_id)'))
			->where('m.path LIKE :c_path')
			->where('status' . ' = :active_flag');
		return $this->_getReadAdapter()->fetchOne($select, $bind);
	}
	/**
	 * Return parent menu items of menu item
	 * @access public
	 * @param JScriptz_MegaMenu2_Model_Menuitem $menuitem
	 * @return array
	 */
	public function getParentMenuitems($menuitem){
		$pathIds = array_reverse(explode('/', $menuitem->getPath()));
		$menuitems = Mage::getResourceModel('megamenu2/menuitem_collection')
			->addFieldToFilter('entity_id', array('in' => $pathIds))
			->addFieldToFilter('status', 1)
			->load()
			->getItems();
		return $menuitems;
	}
	/**
	 * Return child menu items
	 * @access public
	 * @param JScriptz_MegaMenu2_Model_Menuitem $menuitem
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem_Collection
	 */
	public function getChildrenMenuitems($menuitem){
		$collection = $menuitem->getCollection();
		$collection
			->addFilter('status', 1)
			->addIdFilter($menuitem->getChildren())
			->setOrder('position', Varien_Db_Select::SQL_ASC)
			->load();
		return $collection;
	}
	/**
	 * Return children ids of menu item
	 * @access public
	 * @param JScriptz_MegaMenu2_Model_Menuitem $menuitem
	 * @param boolean $recursive
	 * @return array
	 */
	public function getChildren($menuitem, $recursive = true){
		$bind = array(
			'c_path'   => $menuitem->getPath() . '/%'
		);
		$select = $this->_getReadAdapter()->select()
			->from(array('m' => $this->getMainTable()), 'entity_id')
			->where('status = ?', 1)
			->where($this->_getReadAdapter()->quoteIdentifier('path') . ' LIKE :c_path');
		if (!$recursive) {
			$select->where($this->_getReadAdapter()->quoteIdentifier('level') . ' <= :c_level');
			$bind['c_level'] = $menuitem->getLevel() + 1;
		}
		return $this->_getReadAdapter()->fetchCol($select, $bind);
	}
	/**
	 * Retrieve menu items
	 * @access public
	 * @param integer $parent
	 * @param integer $recursionLevel
	 * @param boolean|string $sorted
	 * @param boolean $asCollection
	 * @param boolean $toLoad
	 * @return Varien_Data_Tree_Node_Collection|JScriptz_MegaMenu2_Model_Resource_Menuitem_Collection
	 */
	public function getMenuitems($parent, $recursionLevel = 0, $sorted = false, $asCollection = false, $toLoad = true){
		$tree = Mage::getResourceModel('megamenu2/menuitem_tree');
		$nodes = $tree->loadNode($parent)
			->loadChildren($recursionLevel)
			->getChildren();
		$tree->addCollectionData(null, $sorted, $parent, $toLoad, true);
		if ($asCollection) {
			return $tree->getCollection();
		}
		return $nodes;
	}
	/**
	 * Return all children ids of menuitem (with menuitem id)
	 * @access public
	 * @param JScriptz_MegaMenu2_Model_Menuitem $menuitem
	 * @return array
	 */
	public function getAllChildren($menuitem){
		$children = $this->getChildren($menuitem);
		$myId = array($menuitem->getId());
		$children = array_merge($myId, $children);
		return $children;
	}
	/**
	 * Check menu item is forbidden to delete.
	 * @access public
	 * @param integer $menuitemId
	 * @return boolean
	 */
	public function isForbiddenToDelete($menuitemId){
		return ($menuitemId == Mage::helper('megamenu2/menuitem')->getRootMenuitemId());
	}
	/**
	 * Get menu item path value by its id
	 * @access public
	 * @param int $menuitemId
	 * @return string
	 */
	public function getMenuitemPathById($menuitemId){
		$select = $this->getReadConnection()->select()
			->from($this->getMainTable(), array('path'))
			->where('entity_id = :entity_id');
		$bind = array('entity_id' => (int)$menuitemId);
		return $this->getReadConnection()->fetchOne($select, $bind);
	}
	/**
	 * Move menu item to another parent node
	 * @access public
	 * @param JScriptz_MegaMenu2_Model_Menuitem $menuitem
	 * @param JScriptz_MegaMenu2_Model_Menuitem $newParent
	 * @param null|int $afterMenuitemId
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem
	 */
	public function changeParent(JScriptz_MegaMenu2_Model_Menuitem $menuitem, JScriptz_MegaMenu2_Model_Menuitem $newParent, $afterMenuitemId = null){
		$childrenCount  = $this->getChildrenCount($menuitem->getId()) + 1;
		$table  		= $this->getMainTable();
		$adapter		= $this->_getWriteAdapter();
		$levelFiled 	= $adapter->quoteIdentifier('level');
		$pathField  	= $adapter->quoteIdentifier('path');
		
		/**
		 * Decrease children count for all old menu item parent menu items
		 */
		$adapter->update(
			$table,
			array('children_count' => new Zend_Db_Expr('children_count - ' . $childrenCount)),
			array('entity_id IN(?)' => $menuitem->getParentIds())
		);
		/**
		 * Increase children count for new menu item parents
		 */
		$adapter->update(
			$table,
			array('children_count' => new Zend_Db_Expr('children_count + ' . $childrenCount)),
			array('entity_id IN(?)' => $newParent->getPathIds())
		);
		
		$position = $this->_processPositions($menuitem, $newParent, $afterMenuitemId);
		
		$newPath  = sprintf('%s/%s', $newParent->getPath(), $menuitem->getId());
		$newLevel = $newParent->getLevel() + 1;
		$levelDisposition = $newLevel - $menuitem->getLevel();
		
		/**
		 * Update children nodes path
		 */
		$adapter->update(
			$table,
			array(
				'path' => new Zend_Db_Expr('REPLACE(' . $pathField . ','.
					$adapter->quote($menuitem->getPath() . '/'). ', '.$adapter->quote($newPath . '/').')'
				),
				'level' => new Zend_Db_Expr( $levelFiled . ' + ' . $levelDisposition)
			),
			array($pathField . ' LIKE ?' => $menuitem->getPath() . '/%')
		);
		/**
		 * Update moved menu item data
		 */
		$data = array(
			'path'  => $newPath,
			'level' => $newLevel,
			'position'  =>$position,
			'parent_id' =>$newParent->getId()
		);
		$adapter->update($table, $data, array('entity_id = ?' => $menuitem->getId()));
		// Update menu item object to new data
		$menuitem->addData($data);
		return $this;
	}
	/**
	 * Process positions of old parent menu item children and new parent menu item children.
	 * Get position for moved menu item
	 * @access protected
	 * @param JScriptz_MegaMenu2_Model_Menuitem $menuitem
	 * @param JScriptz_MegaMenu2_Model_Menuitem $newParent
	 * @param null|int $afterMenuitemId
	 * @return int
	 */
	protected function _processPositions($menuitem, $newParent, $afterMenuitemId){
		$table  = $this->getMainTable();
		$adapter= $this->_getWriteAdapter();
		$positionField  = $adapter->quoteIdentifier('position');
		
		$bind = array(
			'position' => new Zend_Db_Expr($positionField . ' - 1')
		);
		$where = array(
			'parent_id = ?' => $menuitem->getParentId(),
			$positionField . ' > ?' => $menuitem->getPosition()
		);
		$adapter->update($table, $bind, $where);
		
		/**
		 * Prepare position value
		 */
		if ($afterMenuitemId) {
			$select = $adapter->select()
				->from($table,'position')
				->where('entity_id = :entity_id');
			$position = $adapter->fetchOne($select, array('entity_id' => $afterMenuitemId));
			$bind = array(
				'position' => new Zend_Db_Expr($positionField . ' + 1')
			);
			$where = array(
				'parent_id = ?' => $newParent->getId(),
				$positionField . ' > ?' => $position
			);
			$adapter->update($table, $bind, $where);
		} 
		elseif ($afterMenuitemId !== null) {
			$position = 0;
			$bind = array(
				'position' => new Zend_Db_Expr($positionField . ' + 1')
			);
			$where = array(
				'parent_id = ?' => $newParent->getId(),
				$positionField . ' > ?' => $position
			);
			$adapter->update($table, $bind, $where);
		} 
		else {
			$select = $adapter->select()
				->from($table,array('position' => new Zend_Db_Expr('MIN(' . $positionField. ')')))
				->where('parent_id = :parent_id');
			$position = $adapter->fetchOne($select, array('parent_id' => $newParent->getId()));
		}
		$position += 1;
		return $position;
	}
}