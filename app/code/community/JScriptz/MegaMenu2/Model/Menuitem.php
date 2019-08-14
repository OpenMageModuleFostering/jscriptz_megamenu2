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
 * Menu Item model
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Model_Menuitem extends Mage_Core_Model_Abstract{
	/**
	 * Entity code.
	 * Can be used as part of method name for entity processing
	 */
	const ENTITY= 'megamenu2_menuitem';
	const CACHE_TAG = 'megamenu2_menuitem';
	/**
	 * Prefix of model events names
	 * @var string
	 */
	protected $_eventPrefix = 'megamenu2_menuitem';
	
	/**
	 * Parameter name in event
	 * @var string
	 */
	protected $_eventObject = 'menuitem';
	protected $_menusettingInstance = null;
	protected $_productInstance = null;
	/**
	 * constructor
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function _construct(){
		parent::_construct();
		$this->_init('megamenu2/menuitem');
	}
	/**
	 * before save menu item
	 * @access protected
	 * @return JScriptz_MegaMenu2_Model_Menuitem
	 * @author Jason Lotzer
	 */
	protected function _beforeSave(){
		parent::_beforeSave();
		$now = Mage::getSingleton('core/date')->gmtDate();
		if ($this->isObjectNew()){
			$this->setCreatedAt($now);
		}
		$this->setUpdatedAt($now);
		return $this;
	}
	/**
	 * get the menuitem Menu HTML
	 * @access public
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getMenuhtml(){
		$menuhtml = $this->getData('menuhtml');
		$helper = Mage::helper('cms');
		$processor = $helper->getBlockTemplateProcessor();
		$html = $processor->filter($menuhtml);
		return $html;
	}
	/**
	 * save menuitem relation
	 * @access public
	 * @return JScriptz_MegaMenu2_Model_Menuitem
	 * @author Jason Lotzer
	 */
	protected function _afterSave() {
		$this->getProductInstance()->saveMenuitemRelation($this);
		$this->getMenusettingInstance()->saveMenuitemRelation($this);
		return parent::_afterSave();
	}
	/**
	 * get product relation model
	 * @access public
	 * @return JScriptz_MegaMenu2_Model_Menuitem_Product
	 * @author Jason Lotzer
	 */
	public function getProductInstance(){
		if (!$this->_productInstance) {
			$this->_productInstance = Mage::getSingleton('megamenu2/menuitem_product');
		}
		return $this->_productInstance;
	}
	/**
	 * get selected products array
	 * @access public
	 * @return array
	 * @author Jason Lotzer
	 */
	public function getSelectedProducts(){
		if (!$this->hasSelectedProducts()) {
			$products = array();
			foreach ($this->getSelectedProductsCollection() as $product) {
				$products[] = $product;
			}
			$this->setSelectedProducts($products);
		}
		return $this->getData('selected_products');
	}
	/**
	 * Retrieve collection selected products
	 * @access public
	 * @return JScriptz_MegaMenu2_Resource_Menuitem_Product_Collection
	 * @author Jason Lotzer
	 */
	public function getSelectedProductsCollection(){
		$collection = $this->getProductInstance()->getProductCollection($this);
		return $collection;
	}
	/**
	 * get menusetting relation model
	 * @access public
	 * @return JScriptz_MegaMenu2_Model_Menuitem_Menusetting
	 * @author Jason Lotzer
	 */
	public function getMenusettingInstance(){
		if (!$this->_menusettingInstance) {
			$this->_menusettingInstance = Mage::getSingleton('megamenu2/menuitem_menusetting');
		}
		return $this->_menusettingInstance;
	}
	/**
	 * get selected menusettings array
	 * @access public
	 * @return array
	 * @author Jason Lotzer
	 */
	public function getSelectedMenusettings(){
		if (!$this->hasSelectedMenusettings()) {
			$menusettings = array();
			foreach ($this->getSelectedMenusettingsCollection() as $menusetting) {
				$menusettings[] = $menusetting;
			}
			$this->setSelectedMenusettings($menusettings);
		}
		return $this->getData('selected_menusettings');
	}
	/**
	 * Retrieve collection selected menusettings
	 * @access public
	 * @return JScriptz_MegaMenu2_Model_Menuitem_Menusetting_Collection
	 * @author Jason Lotzer
	 */
	public function getSelectedMenusettingsCollection(){
		$collection = $this->getMenusettingInstance()->getMenusettingsCollection($this);
		return $collection;
	}
	/**
	 * get the tree model
	 * @access public
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem_Tree
	 * @author Jason Lotzer
	 */
	public function getTreeModel(){
		return Mage::getResourceModel('megamenu2/menuitem_tree');
	}
	/**
	 * get tree model instance
	 * @access public
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem_Tree
	 * @author Jason Lotzer
	 */
	public function getTreeModelInstance(){
		if (is_null($this->_treeModel)) {
			$this->_treeModel = Mage::getResourceSingleton('megamenu2/menuitem_tree');
		}
		return $this->_treeModel;
	}
	/**
	 * Move menu item
	 * @access public
	 * @param   int $parentId new parent menu item id
	 * @param   int $afterMenuitemId menu item id after which we have put current menu item
	 * @return  JScriptz_MegaMenu2_Model_Menuitem
	 * @author Jason Lotzer
	 */
	public function move($parentId, $afterMenuitemId){
		$parent = Mage::getModel('megamenu2/menuitem')->load($parentId);
		if (!$parent->getId()) {
			Mage::throwException(
				Mage::helper('megamenu2')->__('Menu Item move operation is not possible: the new parent menu item was not found.')
			);
		}
		if (!$this->getId()) {
			Mage::throwException(
				Mage::helper('megamenu2')->__('Menu Item move operation is not possible: the current menu item was not found.')
			);
		} 
		elseif ($parent->getId() == $this->getId()) {
			Mage::throwException(
				Mage::helper('megamenu2')->__('Menu Item move operation is not possible: parent menu item is equal to child menu item.')
			);
		}
		$this->setMovedMenuitemId($this->getId());
		$eventParams = array(
			$this->_eventObject => $this,
			'parent'			=> $parent,
			'menuitem_id'   	=> $this->getId(),
			'prev_parent_id'	=> $this->getParentId(),
			'parent_id' 		=> $parentId
		);
		$moveComplete = false;
		$this->_getResource()->beginTransaction();
		try {
			$this->getResource()->changeParent($this, $parent, $afterMenuitemId);
			$this->_getResource()->commit();
			$this->setAffectedMenuitemIds(array($this->getId(), $this->getParentId(), $parentId));
			$moveComplete = true;
		} 
		catch (Exception $e) {
			$this->_getResource()->rollBack();
			throw $e;
		}
		if ($moveComplete) {
			Mage::app()->cleanCache(array(self::CACHE_TAG));
		}
		return $this;
	}
	/**
	 * Get the parent menu item
	 * @access public
	 * @return  JScriptz_MegaMenu2_Model_Menuitem
	 * @author Jason Lotzer
	 */
	public function getParentMenuitem(){
		if (!$this->hasData('parent_menuitem')) {
			$this->setData('parent_menuitem', Mage::getModel('megamenu2/menuitem')->load($this->getParentId()));
		}
		return $this->_getData('parent_menuitem');
	}
	/**
	 * Get the parent id
	 * @access public
	 * @return  int
	 * @author Jason Lotzer
	 */
	public function getParentId(){
		$parentIds = $this->getParentIds();
		return intval(array_pop($parentIds));
	}
	/**
	 * Get all parent menu items ids
	 * @access public
	 * @return array
	 * @author Jason Lotzer
	 */
	public function getParentIds(){
		return array_diff($this->getPathIds(), array($this->getId()));
	}
	/**
	 * Get all menu items children
	 * @access public
	 * @param bool $asArray
	 * @return mixed (array|string)
	 * @author Jason Lotzer
	 */
	public function getAllChildren($asArray = false){
		$children = $this->getResource()->getAllChildren($this);
		if ($asArray) {
			return $children;
		}
		else {
			return implode(',', $children);
		}
	}
	/**
	 * Get all menu items children
	 * @access public
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getChildren(){
		return implode(',', $this->getResource()->getChildren($this, false));
	}
	/**
	 * check the id
	 * @access public
	 * @return bool
	 * @author Jason Lotzer
	 */
	public function checkId($id){
		return $this->_getResource()->checkId($id);
	}
	/**
	 * Get array menu items ids which are part of menu item path
	 * @access public
	 * @return array
	 * @author Jason Lotzer
	 */
	public function getPathIds(){
		$ids = $this->getData('path_ids');
		if (is_null($ids)) {
			$ids = explode('/', $this->getPath());
			$this->setData('path_ids', $ids);
		}
		return $ids;
	}
	/**
	 * Retrieve level
	 * @access public
	 * @return int
	 * @author Jason Lotzer
	 */
	public function getLevel(){
		if (!$this->hasLevel()) {
			return count(explode('/', $this->getPath())) - 1;
		}
		return $this->getData('level');
	}
	/**
	 * Verify menu item ids
	 * @access public
	 * @param array $ids
	 * @return bool
	 * @author Jason Lotzer
	 */
	public function verifyIds(array $ids){
		return $this->getResource()->verifyIds($ids);
	}
	/**
	 * check if menu item has children
	 * @access public
	 * @return bool
	 * @author Jason Lotzer
	 */
	public function hasChildren(){
		return $this->_getResource()->getChildrenAmount($this) > 0;
	}
	/**
	 * check if menu item can be deleted
	 * @access protected
	 * @return JScriptz_MegaMenu2_Model_Menuitem
	 * @author Jason Lotzer
	 */
	protected function _beforeDelete(){
		if ($this->getResource()->isForbiddenToDelete($this->getId())) {
			Mage::throwException(Mage::helper('megamenu2')->__("Can't delete root menu item."));
		}
		return parent::_beforeDelete();
	}
	/**
	 * get the menu items
	 * @access public
	 * @param JScriptz_MegaMenu2_Model_Menuitem $parent
	 * @param int $recursionLevel
	 * @param bool $sorted
	 * @param bool $asCollection
	 * @param bool $toLoad
	 * @author Jason Lotzer
	 */
	public function getMenuitems($parent, $recursionLevel = 0, $sorted=false, $asCollection=false, $toLoad=true){
		return $this->getResource()->getMenuitems($parent, $recursionLevel, $sorted, $asCollection, $toLoad);
	}
	/**
	 * Return parent menu items of current menu item
	 * @access public
	 * @return array
	 * @author Jason Lotzer
	 */
	public function getParentMenuitems(){
		return $this->getResource()->getParentMenuitems($this);
	}
	/**
	 * Retuen children menu items of current menu item
	 * @access public
	 * @return array
	 * @author Jason Lotzer
	 */
	public function getChildrenMenuitems(){
		return $this->getResource()->getChildrenMenuitems($this);
	}
	/**
	 * check if parents are enabled
	 * @access public
	 * @return bool
	 * @author Jason Lotzer
	 */
	public function getStatusPath(){
		$parents = $this->getParentMenuitems();
		foreach ($parents as $parent){
			if (!$parent->getStatus()){
				return false;
			}
		}
		return $this->getStatus();
	}
}
