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
 * Menu Item admin tree block
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Tree extends JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Abstract{
	/**
	 * constructor
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function __construct(){
		parent::__construct();
		$this->setTemplate('jscriptz_megamenu2/menuitem/tree.phtml');
		$this->setUseAjax(true);
		$this->_withProductCount = true;
	}
	/**
	 * prepare the layout
	 * @access protected
	 * @return JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Tree
	 * @author Jason Lotzer
	 */
	protected function _prepareLayout(){
		$addUrl = $this->getUrl("*/*/add", array(
			'_current'=>true,
			'id'=>null,
			'_query' => false
		));
		
		/*$this->setChild('add_sub_button',
			$this->getLayout()->createBlock('adminhtml/widget_button')
				->setData(array(
					'label' => Mage::helper('megamenu2')->__('Add Child Menu Item'),
					'onclick'   => "addNew('".$addUrl."', false)",
					'class' => 'add',
					'id'=> 'add_child_menuitem_button',
					'style' => $this->canAddChild() ? '' : 'display: none;'
				))
		);
		*/
		$this->setChild('add_root_button',
			$this->getLayout()->createBlock('adminhtml/widget_button')
				->setData(array(
					'label' => Mage::helper('megamenu2')->__('Add Menu Item'),
					'onclick'   => "addNew('".$addUrl."', true)",
					'class' => 'add',
					'id'=> 'add_root_menuitem_button'
				))
		);
		return parent::_prepareLayout();
	}
	/**
	 * get the menu item collection
	 * @access public
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem_Collection
	 * @author Jason Lotzer
	 */
	public function getMenuitemCollection(){
		$collection = $this->getData('menuitem_collection');
		if (is_null($collection)) {
			$collection = Mage::getModel('megamenu2/menuitem')->getCollection();
			$this->setData('menuitem_collection', $collection);
		}
		return $collection;
	}
	/**
	 * get html for add root button
	 * @access public
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getAddRootButtonHtml(){
		return $this->getChildHtml('add_root_button');
	}
	/**
	 * get html for add child button
	 * @access public
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getAddSubButtonHtml(){
		return $this->getChildHtml('add_sub_button');
	}
	/**
	 * get html for expand button
	 * @access public
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getExpandButtonHtml(){
		return $this->getChildHtml('expand_button');
	}
	/**
	 * get html for add collapse button
	 * @access public
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getCollapseButtonHtml(){
		return $this->getChildHtml('collapse_button');
	}
	/**
	 * get url for tree load
	 * @access public
	 * @param mxed $expanded
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getLoadTreeUrl($expanded=null){
		$params = array('_current'=>true, 'id'=>null,'store'=>null);
		if ((is_null($expanded) && Mage::getSingleton('admin/session')->getMenuitemIsTreeWasExpanded())|| $expanded == true) {
			$params['expand_all'] = true;
		}
		return $this->getUrl('*/*/menuitemsJson', $params);
	}
	/**
	 * get url for loading nodes
	 * @access public
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getNodesUrl(){
		return $this->getUrl('*/MegaMenu2_menuitems/jsonTree');
	}
	/**
	 * check if tree is expanded
	 * @access public
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getIsWasExpanded(){
		return Mage::getSingleton('admin/session')->getMenuitemIsTreeWasExpanded();
	}
	/**
	 * get url for moving menu item
	 * @access public
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getMoveUrl(){
		return $this->getUrl('*/MegaMenu2_menuitem/move');
	}
	/**
	 * get the tree as json
	 * @access public
	 * @param mixed $parentNodeMenuitem
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getTree($parentNodeMenuitem = null){
		$rootArray = $this->_getNodeJson($this->getRoot($parentNodeMenuitem));
		$tree = isset($rootArray['children']) ? $rootArray['children'] : array();
		return $tree;
	}
	/**
	 * get the tree as json
	 * @access public
	 * @param mixed $parentNodeMenuitem
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getTreeJson($parentNodeMenuitem = null){
		$rootArray = $this->_getNodeJson($this->getRoot($parentNodeMenuitem));
		$json = Mage::helper('core')->jsonEncode(isset($rootArray['children']) ? $rootArray['children'] : array());
		return $json;
	}

	/**
	 * Get JSON of array of menu items, that are breadcrumbs for specified menu item path
	 * @access public
	 * @param string $path
	 * @param string $javascriptVarName
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getBreadcrumbsJavascript($path, $javascriptVarName){
		if (empty($path)) {
			return '';
		}
	
		$menuitems = Mage::getResourceSingleton('megamenu2/menuitem_tree')->loadBreadcrumbsArray($path);
		if (empty($menuitems)) {
			return '';
		}
		foreach ($menuitems as $key => $menuitem) {
			$menuitems[$key] = $this->_getNodeJson($menuitem);
		}
		return
			'<script type="text/javascript">'
			. $javascriptVarName . ' = ' . Mage::helper('core')->jsonEncode($menuitems) . ';'
			. ($this->canAddChild() ? '$("add_child_menuitem_button").show();' : '$("add_child_menuitem_button").hide();')
			. '</script>';
	}

	/**
	 * Get JSON of a tree node or an associative array
	 * @access protected
	 * @param Varien_Data_Tree_Node|array $node
	 * @param int $level
	 * @return string
	 * @author Jason Lotzer
	 */
	protected function _getNodeJson($node, $level = 0){
		// create a node from data array
		if (is_array($node)) {
			$node = new Varien_Data_Tree_Node($node, 'entity_id', new Varien_Data_Tree);
		}
		$item = array();
		$item['text'] 	= $this->buildNodeName($node);
		$rootForStores 	= in_array($node->getEntityId(), $this->getRootIds());
		$item['id']  	= $node->getId();
		$item['path'] 	= $node->getData('path');
		$item['cls'] 	= 'folder';
		if ($node->getStatus()){
			$item['cls'] .= ' active-category';
		}
		else{
			$item['cls'] .= ' no-active-category';
		}
		$item['allowDrop'] = true;
		$item['allowDrag'] = true;
		if ((int)$node->getChildrenCount()>0) {
			$item['children'] = array();
		}
		$isParent = $this->_isParentSelectedMenuitem($node);
		if ($node->hasChildren()) {
			$item['children'] = array();
			if (!($this->getUseAjax() && $node->getLevel() > 1 && !$isParent)) {
				foreach ($node->getChildren() as $child) {
					$item['children'][] = $this->_getNodeJson($child, $level+1);
				}
			}
		}
		if ($isParent || $node->getLevel() < 1) {
			$item['expanded'] = true;
		}
		return $item;
	}
	/**
	 * Get node label
	 * @access public
	 * @param Varien_Object $node
	 * @return string
	 * @author Jason Lotzer
	 */
	public function buildNodeName($node){
		$result = $this->htmlEscape($node->getLinktitle());
		return $result;
	}
	/**
	 * check if entity is movable
	 * @access protected
	 * @param Varien_Object $node
	 * @return bool
	 * @author Jason Lotzer
	 */
	protected function _isMenuitemMoveable($node){
		return true;
	}
	/**
	 * check if parent is selected
	 * @access protected
	 * @param Varien_Object $node
	 * @return bool
	 * @author Jason Lotzer
	 */
	protected function _isParentSelectedMenuitem($node){
		if ($node && $this->getMenuitem()) {
			$pathIds = $this->getMenuitem()->getPathIds();
			if (in_array($node->getId(), $pathIds)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check if page loaded by outside link to menu item edit
	 * @access public
	 * @return boolean
	 * @author Jason Lotzer
	 */
	public function isClearEdit(){
		return (bool) $this->getRequest()->getParam('clear');
	}
	/**
	 * Check availability of adding root menu item
	 * @access public
	 * @return boolean
	 * @author Jason Lotzer
	 */
	public function canAddRootMenuitem(){
		return true;
	}
	/**
	 * Check availability of adding child menu item
	 * @access public
	 * @return boolean
	 */
	public function canAddChild(){
		return true;
	}
}
