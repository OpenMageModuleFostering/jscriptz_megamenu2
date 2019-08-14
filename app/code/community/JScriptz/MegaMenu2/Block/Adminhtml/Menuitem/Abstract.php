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
 * Menu Item admin block abstract
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Abstract extends Mage_Adminhtml_Block_Template{
	/**
	 * get current menu item
	 * @access public
	 * @return JScriptz_MegaMenu2_Model_Entity
	 * @author Jason Lotzer
	 */
	public function getMenuitem(){
		return Mage::registry('menuitem');
	}
	/**
	 * get current menu item id
	 * @access public
	 * @return int
	 * @author Jason Lotzer
	 */
	public function getMenuitemId(){
		if ($this->getMenuitem()) {
			return $this->getMenuitem()->getId();
		}
		return null;
	}
	/**
	 * get current menu item Link Title
	 * @access public
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getMenuitemLinktitle(){
		return $this->getMenuitem()->getLinktitle();
	}
	/**
	 * get current menu item path
	 * @access public
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getMenuitemPath(){
		if ($this->getMenuitem()) {
			return $this->getMenuitem()->getPath();
		}
		return Mage::helper('megamenu2/menuitem')->getRootMenuitemId();
	}
	/**
	 * check if there is a root menu item
	 * @access public
	 * @return bool
	 * @author Jason Lotzer
	 */
	public function hasRootMenuitem(){
		$root = $this->getRoot();
		if ($root && $root->getId()) {
			return true;
		}
		return false;
	}
	/**
	 * get the root
	 * @access publoc
	 * @param JScriptz_MegaMenu2_Model_Menuitem|null $parentNodeMenuitem
	 * @param int $recursionLevel
	 * @return Varien_Data_Tree_Node
	 * @author Jason Lotzer
	 */
	public function getRoot($parentNodeMenuitem = null, $recursionLevel = 3){
		if (!is_null($parentNodeMenuitem) && $parentNodeMenuitem->getId()) {
			return $this->getNode($parentNodeMenuitem, $recursionLevel);
		}
		$root = Mage::registry('root');
		if (is_null($root)) {
			$rootId = Mage::helper('megamenu2/menuitem')->getRootMenuitemId();
			$tree = Mage::getResourceSingleton('megamenu2/menuitem_tree')
				->load(null, $recursionLevel);
			if ($this->getMenuitem()) {
				$tree->loadEnsuredNodes($this->getMenuitem(), $tree->getNodeById($rootId));
			}
			$tree->addCollectionData($this->getMenuitemCollection());
			$root = $tree->getNodeById($rootId);
			if ($root && $rootId != Mage::helper('megamenu2/menuitem')->getRootMenuitemId()) {
				$root->setIsVisible(true);
			}
			elseif($root && $root->getId() == Mage::helper('megamenu2/menuitem')->getRootMenuitemId()) {
				$root->setLinktitle(Mage::helper('megamenu2')->__('Root'));
			}
			Mage::register('root', $root);
		}
		return $root;
	}

	/**
	 * Get and register menu items root by specified menu items IDs
	 * @accsess public
	 * @param array $ids
	 * @return Varien_Data_Tree_Node
	 * @author Jason Lotzer
	 */
	public function getRootByIds($ids){
		$root = Mage::registry('root');
		if (null === $root) {
			$menuitemTreeResource = Mage::getResourceSingleton('megamenu2/menuitem_tree');
			$ids 	= $menuitemTreeResource->getExistingMenuitemIdsBySpecifiedIds($ids);
			$tree   = $menuitemTreeResource->loadByIds($ids);
			$rootId = Mage::helper('megamenu2/menuitem')->getRootMenuitemId();
			$root   = $tree->getNodeById($rootId);
			if ($root && $rootId != Mage::helper('megamenu2/menuitem')->getRootMenuitemId()) {
				$root->setIsVisible(true);
			} 
			else if($root && $root->getId() == Mage::helper('megamenu2/menuitem')->getRootMenuitemId()) {
				$root->setName(Mage::helper('megamenu2')->__('Root'));
			}
			$tree->addCollectionData($this->getMenuitemCollection());
			Mage::register('root', $root);
		}
		return $root;
	}
	/**
	 * get specific node
	 * @access public
	 * @param JScriptz_MegaMenu2_Model_Menuitem $parentNodeMenuitem
	 * @param $int $recursionLevel
	 * @return Varien_Data_Tree_Node
	 * @author Jason Lotzer
	 */
	public function getNode($parentNodeMenuitem, $recursionLevel = 2){
		$tree = Mage::getResourceModel('megamenu2/menuitem_tree');
		$nodeId 	= $parentNodeMenuitem->getId();
		$parentId   = $parentNodeMenuitem->getParentId();
		$node = $tree->loadNode($nodeId);
		$node->loadChildren($recursionLevel);
		if ($node && $nodeId != Mage::helper('megamenu2/menuitem')->getRootMenuitemId()) {
			$node->setIsVisible(true);
		} 
		elseif($node && $node->getId() == Mage::helper('megamenu2/menuitem')->getRootMenuitemId()) {
			$node->setLinktitle(Mage::helper('megamenu2')->__('Root'));
		}
		$tree->addCollectionData($this->getMenuitemCollection());
		return $node;
	}
	/**
	 * get url for saving data
	 * @access public
	 * @param array $args
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getSaveUrl(array $args = array()){
		$params = array('_current'=>true);
		$params = array_merge($params, $args);
		return $this->getUrl('*/*/save', $params);
	}
	/**
	 * get url for edit
	 * @access public
	 * @param array $args
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getEditUrl(){
		return $this->getUrl("*/MegaMenu2_menuitem/edit", array('_current'=>true, '_query'=>false, 'id'=>null, 'parent'=>null));
	}
	/**
	 * Return root ids
	 * @access public
	 * @return array
	 * @author Jason Lotzer
	 */
	public function getRootIds(){
		return array(Mage::helper('megamenu2/menuitem')->getRootMenuitemId());
	}
}
