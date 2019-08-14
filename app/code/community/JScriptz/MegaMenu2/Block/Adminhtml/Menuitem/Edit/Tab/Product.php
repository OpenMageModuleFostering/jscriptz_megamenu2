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
 * Menu Item - product relation edit block
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Edit_Tab_Product extends Mage_Adminhtml_Block_Widget_Grid{
	/**
	 * Set grid params
	 * @access protected
	 * @return void
	 * @author Jason Lotzer
	 */
	public function __construct(){
		parent::__construct();
		$this->setId('product_grid');
		$this->setDefaultSort('position');
		$this->setDefaultDir('ASC');
		$this->setUseAjax(true);
		if ($this->getMenuitem()->getId()) {
			$this->setDefaultFilter(array('in_products'=>1));
		}
	}
	/**
	 * prepare the product collection
	 * @access protected 
	 * @return JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Edit_Tab_Product
	 * @author Jason Lotzer
	 */
	protected function _prepareCollection() {
		$collection = Mage::getResourceModel('catalog/product_collection');
		$collection->addAttributeToSelect('price');
		$adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
		$collection->joinAttribute('product_name', 'catalog_product/name', 'entity_id', null, 'left', $adminStore);
		if ($this->getMenuitem()->getId()){
			$constraint = '{{table}}.menuitem_id='.$this->getMenuitem()->getId();
		}
		else{
			$constraint = '{{table}}.menuitem_id=0';
		}
		$collection->joinField('position',
			'megamenu2/menuitem_product',
			'position',
			'product_id=entity_id',
			$constraint,
			'left');
		$this->setCollection($collection);
		parent::_prepareCollection();
		return $this;
	}
	/**
	 * prepare mass action grid
	 * @access protected
	 * @return JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Edit_Tab_Product
	 * @author Jason Lotzer
	 */ 
	protected function _prepareMassaction(){
		return $this;
	}
	/**
	 * prepare the grid columns
	 * @access protected
	 * @return JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Edit_Tab_Product
	 * @author Jason Lotzer
	 */
	protected function _prepareColumns(){
		$this->addColumn('in_products', array(
			'header_css_class'  => 'a-center',
			'type'  => 'checkbox',
			'name'  => 'in_products',
			'values'=> $this->_getSelectedProducts(),
			'align' => 'center',
			'index' => 'entity_id'
		));
		$this->addColumn('product_name', array(
			'header'=> Mage::helper('catalog')->__('Name'),
			'align' => 'left',
			'index' => 'product_name',
		));
		$this->addColumn('sku', array(
			'header'=> Mage::helper('catalog')->__('SKU'),
			'align' => 'left',
			'index' => 'sku',
		));
		$this->addColumn('price', array(
			'header'=> Mage::helper('catalog')->__('Price'),
			'type'  => 'currency',
			'width' => '1',
			'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
			'index' => 'price'
		));
		/*$this->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
		$this->addColumn('status', array(
			'header'=> Mage::helper('catalog')->__('Status'),
			'name'  => 'status',
			'index' => 'status',
			'type' => 'select',
         		//'value' => $this->getCollection()->addFieldToFilter('status', array('eq' => '1')),

		));*/
		
		$this->addColumn('position', array(
			'header'=> Mage::helper('catalog')->__('Position'),
			'name'  => 'position',
			'width' => 60,
			'type'  => 'number',
			'validate_class'=> 'validate-number',
			'index' => 'position',
			'editable'  => true,
		));
	}
	/**
	 * Retrieve selected products
	 * @access protected
	 * @return array
	 * @author Jason Lotzer
	 */
	protected function _getSelectedProducts(){
		$products = $this->getMenuitemProducts();
		if (!is_array($products)) {
			$products = array_keys($this->getSelectedProducts());
		}
		return $products;
	}
 	/**
	 * Retrieve selected products
	 * @access protected
	 * @return array
	 * @author Jason Lotzer
	 */
	public function getSelectedProducts() {
		$products = array();
		$selected = Mage::registry('current_menuitem')->getSelectedProducts();
		if (!is_array($selected)){
			$selected = array();
		}
		foreach ($selected as $product) {
			$products[$product->getId()] = array('position' => $product->getPosition());
		}
		return $products;
	}
	/**
	 * get row url
	 * @access public
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getRowUrl($item){
		return '#';
	}
	/**
	 * get grid url
	 * @access public
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getGridUrl(){
		return $this->getUrl('*/*/productsGrid', array(
			'id'=>$this->getMenuitem()->getId()
		));
	}
	/**
	 * get the current menuitem
	 * @access public
	 * @return JScriptz_MegaMenu2_Model_Menuitem
	 * @author Jason Lotzer
	 */
	public function getMenuitem(){
		return Mage::registry('current_menuitem');
	}
	/**
	 * Add filter
	 * @access protected
	 * @param object $column
	 * @return JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Edit_Tab_Product
	 * @author Jason Lotzer
	 */
	protected function _addColumnFilterToCollection($column){
		// Set custom filter for in product flag
		if ($column->getId() == 'in_products') {
			$productIds = $this->_getSelectedProducts();
			if (empty($productIds)) {
				$productIds = 0;
			}
			if ($column->getFilter()->getValue()) {
				$this->getCollection()->addFieldToFilter('entity_id', array('in'=>$productIds));
				//$this->getCollection()->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
				$this->getCollection()->addFieldToFilter('status', array('eq' => '1'));
				
			} 
			else {
				if($productIds) {
					$this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$productIds));
					//$this->getCollection()->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
					$this->getCollection()->addFieldToFilter('status', array('eq' => '1'));
				}
			}
		} 
		else {
			parent::_addColumnFilterToCollection($column);
		}
		return $this;
	}
}
