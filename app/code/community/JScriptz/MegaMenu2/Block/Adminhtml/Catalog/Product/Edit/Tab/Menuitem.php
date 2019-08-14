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
 * Menu Item tab on product edit form
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Block_Adminhtml_Catalog_Product_Edit_Tab_Menuitem extends JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Tree{
	protected $_menuitemIds = null;
	protected $_selectedNodes = null;
	/**
	 * constructor
	 * Specify template to use
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function __construct(){
		parent::__construct();
		$this->setTemplate('jscriptz_megamenu2/catalog/product/edit/tab/menuitem.phtml');
	}
	/**
	 * Retrieve currently edited product
	 * @access public
	 * @return Mage_Catalog_Model_Product
	 * @author Jason Lotzer
	 */
	public function getProduct(){
		return Mage::registry('current_product');
	}
	/**
	 * Return array with menu item IDs which the product is assigned to
	 * @access public
	 * @return array
	 * @author Jason Lotzer
	 */
	public function getMenuitemIds(){
		if (is_null($this->_menuitemIds)){
			$selectedMenuitems = Mage::helper('megamenu2/product')->getSelectedMenuitems($this->getProduct());
			$ids = array();
			foreach ($selectedMenuitems as $menuitem){
				$ids[] = $menuitem->getId();
			}
			$this->_menuitemIds = $ids; 
		}
		return $this->_menuitemIds;
	}
	/**
	 * Forms string out of getMenuitemIds()
	 * @access public
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getIdsString(){
		return implode(',', $this->getMenuitemIds());
	}
	/**
	 * Returns root node and sets 'checked' flag (if necessary)
	 * @access public
	 * @return Varien_Data_Tree_Node
	 * @author Jason Lotzer
	 */
	public function getRootNode(){
		$root = $this->getRoot();
		if ($root && in_array($root->getId(), $this->getMenuitemIds())) {
			$root->setChecked(true);
		}
		return $root;
	}
	/**
	 * Returns root node
	 *
	 * @param JScriptz_MegaMenu2_Model_Menuitem|null $parentNodeMenuitem
	 * @param int  $recursionLevel
	 * @return Varien_Data_Tree_Node
	 * @author Jason Lotzer
	 */
	public function getRoot($parentNodeMenuitem = null, $recursionLevel = 3){
		if (!is_null($parentNodeMenuitem) && $parentNodeMenuitem->getId()) {
			return $this->getNode($parentNodeMenuitem, $recursionLevel);
		}
		$root = Mage::registry('menuitem_root');
		if (is_null($root)) {
			$rootId = Mage::helper('megamenu2/menuitem')->getRootMenuitemId();
		
			$ids = $this->getSelectedFMenuitemPathIds($rootId);
			$tree = Mage::getResourceSingleton('megamenu2/menuitem_tree')
				->loadByIds($ids, false, false);
			if ($this->getMenuitem()) {
				$tree->loadEnsuredNodes($this->getMenuitem(), $tree->getNodeById($rootId));
			}
			$tree->addCollectionData($this->getMenuitemCollection());
			$root = $tree->getNodeById($rootId);
			Mage::register('menuitem_root', $root);
		}
		return $root;
	}
	/**
	 * Returns array with configuration of current node
	 * @access protected
	 * @param Varien_Data_Tree_Node $node
	 * @param int $level How deep is the node in the tree
	 * @return array
	 * @author Jason Lotzer
	 */
	protected function _getNodeJson($node, $level = 1){
		$item = parent::_getNodeJson($node, $level);
		if ($this->_isParentSelectedMenuitem($node)) {
			$item['expanded'] = true;
		}
		if (in_array($node->getId(), $this->getMenuitemIds())) {
			$item['checked'] = true;
		}
		return $item;
	}

	/**
	 * Returns whether $node is a parent (not exactly direct) of a selected node
	 * @access protected
	 * @param Varien_Data_Tree_Node $node
	 * @return bool
	 * @author Jason Lotzer
	 */
	protected function _isParentSelectedMenuitem($node){
		$result = false;
		// Contains string with all menu item IDs of children (not exactly direct) of the node
		$allChildren = $node->getAllChildren();
		if ($allChildren) {
			$selectedMenuitemIds = $this->getMenuitemIds();
			$allChildrenArr = explode(',', $allChildren);
			for ($i = 0, $cnt = count($selectedMenuitemIds); $i < $cnt; $i++) {
				$isSelf = $node->getId() == $selectedMenuitemIds[$i];
				if (!$isSelf && in_array($selectedMenuitemIds[$i], $allChildrenArr)) {
					$result = true;
					break;
				}
			}
		}
		return $result;
	}

	/**
	 * Returns array with nodes those are selected (contain current product)
	 * @access protected
	 * @return array
	 * @author Jason Lotzer
	 */
	protected function _getSelectedNodes(){
		if ($this->_selectedNodes === null) {
			$this->_selectedNodes = array();
			$root = $this->getRoot();
			foreach ($this->getMenuitemIds() as $menuitemId) {
				if ($root) {
					$this->_selectedNodes[] = $root->getTree()->getNodeById($menuitemId);
				}
			}
		}
		return $this->_selectedNodes;
	}

	/**
	 * Returns JSON-encoded array of menu item children
	 * @access public
	 * @param int $menuitemId
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getMenuitemChildrenJson($menuitemId){
		$menuitem = Mage::getModel('megamenu2/menuitem')->load($menuitemId);
		$node = $this->getRoot($menuitem, 1)->getTree()->getNodeById($menuitemId);
		if (!$node || !$node->hasChildren()) {
			return '[]';
		}
		
		$children = array();
		foreach ($node->getChildren() as $child) {
			$children[] = $this->_getNodeJson($child);
		}
		return Mage::helper('core')->jsonEncode($children);
	}

	/**
	 * Returns URL for loading tree
	 * @access public
	 * @param null $expanded
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getLoadTreeUrl($expanded = null){
		return $this->getUrl('*/*/menuitemsJson', array('_current' => true));
	}

	/**
	 * Return distinct path ids of selected menu items
	 * @access public
	 * @param mixed $rootId Root menu item Id for context
	 * @return array
	 * @author Jason Lotzer
	 */
	public function getSelectedMenuitemPathIds($rootId = false){
		$ids = array();
		$menuitemIds = $this->getMenuitemIds();
		if (empty($menuitemIds)) {
			return array();
		}
		$collection = Mage::getResourceModel('megamenu2/menuitem_collection');
		
		if ($rootId) {
			$collection->addFieldToFilter('parent_id', $rootId);
		} 
		else {
			$collection->addFieldToFilter('entity_id', array('in'=>$menuitemIds));
		}
		
		foreach ($collection as $item) {
			if ($rootId && !in_array($rootId, $item->getPathIds())) {
				continue;
			}
			foreach ($item->getPathIds() as $id) {
				if (!in_array($id, $ids)) {
					$ids[] = $id;
				}
			}
		}
		return $ids;
	}
}
