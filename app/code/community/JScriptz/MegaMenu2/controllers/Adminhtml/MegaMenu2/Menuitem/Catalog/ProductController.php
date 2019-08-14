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
 * Menu Item - product controller
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */ 
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class JScriptz_MegaMenu2_Adminhtml_MegaMenu2_Menuitem_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController{
	/**
	 * menu items action
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function menuitemsAction(){
		$this->_initProduct();
		$this->loadLayout();
		$this->renderLayout();
	}
	/**
	 * menu items json action
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function menuitemsJsonAction(){
		$product = $this->_initProduct();
		$this->getResponse()->setBody(
		$this->getLayout()->createBlock('megamenu2/adminhtml_catalog_product_edit_tab_menuitem')
			->getMenuitemChildrenJson($this->getRequest()->getParam('menuitem'))
		);
	}
}
