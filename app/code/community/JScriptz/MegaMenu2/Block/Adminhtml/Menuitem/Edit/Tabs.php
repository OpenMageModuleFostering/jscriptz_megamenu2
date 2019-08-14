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
 * @package	JScriptz_MegaMenu2
 * @copyright  	Copyright (c) 2013
 * @license	http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Menu Item admin edit tabs
 *
 * @category	JScriptz
 * @package	JScriptz_MegaMenu2

 */
class JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs{
	/**
	 * Initialize Tabs
	 * @access public
	 * @return void
 	*/
	public function __construct(){
		parent::__construct();
		$this->setId('menuitem_info_tabs');
		$this->setDestElementId('menuitem_tab_content');
		$this->setTitle(Mage::helper('megamenu2')->__('Menu Item'));
		$this->setTemplate('widget/tabshoriz.phtml');
	}
	/**
	 * Retrieve menu item entity
	 * @access public
	 * @return JScriptz_MegaMenu2_Model_Menuitem
	
	 */
	public function getMenuitem(){
		return Mage::registry('current_menuitem');
	}
	/**
	 * Prepare Layout Content
	 * @access public 
	 * @return JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Edit_Tabs
	 */
	protected function _prepareLayout(){
		$this->addTab('form_menuitem', array(
			'label'		=> Mage::helper('megamenu2')->__('Menu Item'),
			'title'		=> Mage::helper('megamenu2')->__('Menu Item'),
			'content' 	=> $this->getLayout()->createBlock('megamenu2/adminhtml_menuitem_edit_tab_form')->toHtml(),
		));
		if (!Mage::app()->isSingleStoreMode()){
			$this->addTab('form_store_menuitem', array(
				'label'		=> Mage::helper('megamenu2')->__('Store views'),
				'title'		=> Mage::helper('megamenu2')->__('Store views'),
				'content' 	=> $this->getLayout()->createBlock('megamenu2/adminhtml_menuitem_edit_tab_stores')->toHtml(),
			));
		}
		return parent::_prepareLayout();
	}
}