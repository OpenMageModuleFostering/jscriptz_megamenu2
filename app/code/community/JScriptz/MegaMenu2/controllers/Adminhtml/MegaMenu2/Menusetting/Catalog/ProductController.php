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
 * Menusetting product admin controller
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class JScriptz_MegaMenu2_Adminhtml_MegaMenu2_Menusetting_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController{
	/**
	 * construct
	 * @access protected
	 * @return void
	 * @author Jason Lotzer
	 */
	protected function _construct(){
		// Define module dependent translate
		$this->setUsedModuleName('JScriptz_MegaMenu2');
	}
	/**
	 * menusettings in the catalog page
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function menusettingsAction(){
		$this->_initProduct();
		$this->loadLayout();
		$this->getLayout()->getBlock('product.edit.tab.menusetting')
			->setProductMenusettings($this->getRequest()->getPost('product_menusettings', null));
		$this->renderLayout();
	}
	/**
	 * menusettings grid in the catalog page
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function menusettingsGridAction(){
		$this->_initProduct();
		$this->loadLayout();
		$this->getLayout()->getBlock('product.edit.tab.menusetting')
			->setProductMenusettings($this->getRequest()->getPost('product_menusettings', null));
		$this->renderLayout();
	}
}
