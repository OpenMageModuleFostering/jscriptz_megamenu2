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
 * Adminhtml observer
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Model_Adminhtml_Observer{
	/**
	 * check if tab can be added
	 * @access protected
	 * @param Mage_Catalog_Model_Product $product
	 * @return bool
	 * @author Jason Lotzer
	 */
	protected function _canAddTab($product){
		if ($product->getId()){
			return true;
		}
		if (!$product->getAttributeSetId()){
			return false;
		}
		$request = Mage::app()->getRequest();
		if ($request->getParam('type') == 'configurable'){
			if ($request->getParam('attribtues')){
				return true;
			}
		}
		return false;
	}
	/**
	 * add the menuitem tab to products
	 * @access public
	 * @param Varien_Event_Observer $observer
	 * @return JScriptz_MegaMenu2_Model_Adminhtml_Observer
	 * @author Jason Lotzer
	 */
	public function addMenuitemBlock($observer){
		$block = $observer->getEvent()->getBlock();
		$product = Mage::registry('product');
		if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)){
			$block->addTab('menuitems', array(
				'label' => Mage::helper('megamenu2')->__('Menuitems'),
				'url'   => Mage::helper('adminhtml')->getUrl('adminhtml/megamenu2_menuitem_catalog_product/menuitems', array('_current' => true)),
				'class' => 'ajax',
			));
		}
		return $this;
	}
	/**
	 * add the menusetting tab to products
	 * @access public
	 * @param Varien_Event_Observer $observer
	 * @return JScriptz_MegaMenu2_Model_Adminhtml_Observer
	 * @author Jason Lotzer
	 */
	public function addMenusettingBlock($observer){
		$block = $observer->getEvent()->getBlock();
		$product = Mage::registry('product');
		if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)){
			$block->addTab('menusettings', array(
				'label' => Mage::helper('megamenu2')->__('Menusettings'),
				'url'   => Mage::helper('adminhtml')->getUrl('adminhtml/megamenu2_menusetting_catalog_product/menusettings', array('_current' => true)),
				'class' => 'ajax',
			));
		}
		return $this;
	}
	/**
	 * save menusetting - product relation
	 * @access public
	 * @param Varien_Event_Observer $observer
	 * @return JScriptz_MegaMenu2_Model_Adminhtml_Observer
	 * @author Jason Lotzer
	 */
	public function saveMenusettingData($observer){
		$post = Mage::app()->getRequest()->getPost('menusettings', -1);
		if ($post != '-1') {
			$post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
			$product = Mage::registry('product');
			$menusettingProduct = Mage::getResourceSingleton('megamenu2/menusetting_product')->saveProductRelation($product, $post);
		}
		return $this;
	}	/**
	 * save menuitem - product relation
	 * @access public
	 * @param Varien_Event_Observer $observer
	 * @return JScriptz_MegaMenu2_Model_Adminhtml_Observer
	 * @author Jason Lotzer
	 */
	public function saveMenuitemData($observer){
		$post = Mage::app()->getRequest()->getPost('menuitem_ids', -1);
		if ($post != '-1') {
			$post = explode(',', $post);
			$post = array_unique($post); 
			$product = $observer->getEvent()->getProduct();
			Mage::getResourceSingleton('megamenu2/menuitem_product')->saveProductRelation($product, $post);
		}
		return $this;
	}
}
