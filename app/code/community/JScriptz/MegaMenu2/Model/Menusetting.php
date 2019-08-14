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
 * Menu Setting model
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Model_Menusetting extends Mage_Core_Model_Abstract{
	/**
	 * Entity code.
	 * Can be used as part of method name for entity processing
	 */
	const ENTITY= 'megamenu2_menusetting';
	const CACHE_TAG = 'megamenu2_menusetting';
	/**
	 * Prefix of model events names
	 * @var string
	 */
	protected $_eventPrefix = 'megamenu2_menusetting';
	
	/**
	 * Parameter name in event
	 * @var string
	 */
	protected $_eventObject = 'menusetting';
	protected $_menuitemInstance = null;
	protected $_productInstance = null;
	/**
	 * constructor
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function _construct(){
		parent::_construct();
		$this->_init('megamenu2/menusetting');
	}
	/**
	 * before save menu setting
	 * @access protected
	 * @return JScriptz_MegaMenu2_Model_Menusetting
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
	 * save menusetting relation
	 * @access public
	 * @return JScriptz_MegaMenu2_Model_Menusetting
	 * @author Jason Lotzer
	 */
	protected function _afterSave() {
		$this->getProductInstance()->saveMenusettingRelation($this);
		$this->getMenuitemInstance()->saveMenusettingRelation($this);
		return parent::_afterSave();
	}
	/**
	 * get product relation model
	 * @access public
	 * @return JScriptz_MegaMenu2_Model_Menusetting_Product
	 * @author Jason Lotzer
	 */
	public function getProductInstance(){
		if (!$this->_productInstance) {
			$this->_productInstance = Mage::getSingleton('megamenu2/menusetting_product');
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
	 * @return JScriptz_MegaMenu2_Resource_Menusetting_Product_Collection
	 * @author Jason Lotzer
	 */
	public function getSelectedProductsCollection(){
		$collection = $this->getProductInstance()->getProductCollection($this);
		return $collection;
	}
	/**
	 * get menuitem relation model
	 * @access public
	 * @return JScriptz_MegaMenu2_Model_Menusetting_Menuitem
	 * @author Jason Lotzer
	 */
	public function getMenuitemInstance(){
		if (!$this->_menuitemInstance) {
			$this->_menuitemInstance = Mage::getSingleton('megamenu2/menusetting_menuitem');
		}
		return $this->_menuitemInstance;
	}
	/**
	 * get selected menuitems array
	 * @access public
	 * @return array
	 * @author Jason Lotzer
	 */
	public function getSelectedMenuitems(){
		if (!$this->hasSelectedMenuitems()) {
			$menuitems = array();
			foreach ($this->getSelectedMenuitemsCollection() as $menuitem) {
				$menuitems[] = $menuitem;
			}
			$this->setSelectedMenuitems($menuitems);
		}
		return $this->getData('selected_menuitems');
	}
	/**
	 * Retrieve collection selected menuitems
	 * @access public
	 * @return JScriptz_MegaMenu2_Model_Menusetting_Menuitem_Collection
	 * @author Jason Lotzer
	 */
	public function getSelectedMenuitemsCollection(){
		$collection = $this->getMenuitemInstance()->getMenuitemsCollection($this);
		return $collection;
	}
}
