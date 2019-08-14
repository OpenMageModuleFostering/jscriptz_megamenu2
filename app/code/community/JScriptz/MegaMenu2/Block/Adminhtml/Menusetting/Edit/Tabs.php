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
 * Menu Setting admin edit tabs
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Block_Adminhtml_Menusetting_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs{
	/**
	 * constructor
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function __construct(){
		parent::__construct();
		$this->setId('menusetting_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('megamenu2')->__('Menu Setting'));
	}
	/**
	 * before render html
	 * @access protected
	 * @return JScriptz_MegaMenu2_Block_Adminhtml_Menusetting_Edit_Tabs
	 * @author Jason Lotzer
	 */
	protected function _beforeToHtml(){
		$this->addTab('form_menusetting', array(
			'label'		=> Mage::helper('megamenu2')->__('Menu Setting'),
			'title'		=> Mage::helper('megamenu2')->__('Menu Setting'),
			'content' 	=> $this->getLayout()->createBlock('megamenu2/adminhtml_menusetting_edit_tab_form')->toHtml(),
		));
		if (!Mage::app()->isSingleStoreMode()){
			$this->addTab('form_store_menusetting', array(
				'label'		=> Mage::helper('megamenu2')->__('Store views'),
				'title'		=> Mage::helper('megamenu2')->__('Store views'),
				'content' 	=> $this->getLayout()->createBlock('megamenu2/adminhtml_menusetting_edit_tab_stores')->toHtml(),
			));
		}
		$this->addTab('menuitems', array(
			'label' => Mage::helper('megamenu2')->__('Menu Items'),
			'url'   => $this->getUrl('*/*/menuitems', array('_current' => true)),
   			'class'	=> 'ajax'
		));
		$this->addTab('products', array(
			'label' => Mage::helper('megamenu2')->__('Associated products'),
			'url'   => $this->getUrl('*/*/products', array('_current' => true)),
   			'class'	=> 'ajax'
		));
		return parent::_beforeToHtml();
	}
}
