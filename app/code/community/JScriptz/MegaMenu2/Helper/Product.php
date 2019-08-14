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
 * Product helper
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Helper_Product extends JScriptz_MegaMenu2_Helper_Data{
	/**
	 * get the selected menuitems for a product
	 * @access public
	 * @param Mage_Catalog_Model_Product $product
	 * @return array()
	 * @author Jason Lotzer
	 */
	public function getSelectedMenuitems(Mage_Catalog_Model_Product $product){
		if (!$product->hasSelectedMenuitems()) {
			$menuitems = array();
			foreach ($this->getSelectedMenuitemsCollection($product) as $menuitem) {
				$menuitems[] = $menuitem;
			}
			$product->setSelectedMenuitems($menuitems);
		}
		return $product->getData('selected_menuitems');
	}
	/**
	 * get menuitem collection for a product
	 * @access public
	 * @param Mage_Catalog_Model_Product $product
	 * @return JScriptz_MegaMenu2_Model_Resource_Menuitem_Collection
	 */
	public function getSelectedMenuitemsCollection(Mage_Catalog_Model_Product $product){
		$collection = Mage::getResourceSingleton('megamenu2/menuitem_collection')
			->addProductFilter($product);
		return $collection;
	}
	/**
	 * get the selected menusettings for a product
	 * @access public
	 * @param Mage_Catalog_Model_Product $product
	 * @return array()
	 * @author Jason Lotzer
	 */
	public function getSelectedMenusettings(Mage_Catalog_Model_Product $product){
		if (!$product->hasSelectedMenusettings()) {
			$menusettings = array();
			foreach ($this->getSelectedMenusettingsCollection($product) as $menusetting) {
				$menusettings[] = $menusetting;
			}
			$product->setSelectedMenusettings($menusettings);
		}
		return $product->getData('selected_menusettings');
	}
	/**
	 * get menusetting collection for a product
	 * @access public
	 * @param Mage_Catalog_Model_Product $product
	 * @return JScriptz_MegaMenu2_Model_Resource_Menusetting_Collection
	 */
	public function getSelectedMenusettingsCollection(Mage_Catalog_Model_Product $product){
		$collection = Mage::getResourceSingleton('megamenu2/menusetting_collection')
			->addProductFilter($product);
		return $collection;
	}
}
