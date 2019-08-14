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
 * Menu Item edit form tab
 *
 * @category	JScriptz
 * @package	JScriptz_MegaMenu2

 */
class JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form{	
	/**
	 * prepare the form
	 * @access protected
	 * @return MegaMenu2_Menuitem_Block_Adminhtml_Menuitem_Edit_Tab_Form
	 */
	protected function _prepareForm(){
		$form = new Varien_Data_Form();
		$form->setHtmlIdPrefix('menuitem_');
		$form->setFieldNameSuffix('menuitem');
		$this->setForm($form);
		$fieldset = $form->addFieldset('menuitem_form', array('legend'=>Mage::helper('megamenu2')->__('Menu Item')));
		$fieldset->addType('editor', Mage::getConfig()->getBlockClassName('megamenu2/adminhtml_helper_wysiwyg'));
		if (!$this->getMenuitem()->getId()) {
			$parentId = $this->getRequest()->getParam('parent');
			if (!$parentId) {
				$parentId = Mage::helper('megamenu2/menuitem')->getRootMenuitemId();
			}
			$fieldset->addField('path', 'hidden', array(
				'name'  => 'path',
				'value' => $parentId
			));
		} 
		else {
			$fieldset->addField('id', 'hidden', array(
				'name'  => 'id',
				'value' => $this->getMenuitem()->getId()
			));
			$fieldset->addField('path', 'hidden', array(
				'name'  => 'path',
				'value' => $this->getMenuitem()->getPath()
			));
		}

		$fieldset->addField('linktitle', 'text', array(
			'label' => Mage::helper('megamenu2')->__('Link Title'),
			'name'  => 'linktitle',
			'note'	=> $this->__('This is the title for your menu link'),
			'required'  => true,
			'class' => 'required-entry',

		));

		$fieldset->addField('linkpath', 'text', array(
			'label' => Mage::helper('megamenu2')->__('Link Path'),
			'name'  => 'linkpath',
			'note'	=> $this->__('This is the url your link will follow'),

		));

		$fieldset->addField('linktarget', 'text', array(
			'label' => Mage::helper('megamenu2')->__('Link Target'),
			'name'  => 'linktarget',
			'note'	=> $this->__('Whether the link opens in a new tab -- examples (blank, self)'),

		));

		$fieldset->addField('menuhtml', 'editor', array(
			'label' => Mage::helper('megamenu2')->__('Menu HTML'),
			'name'  => 'menuhtml',
			'note'	=> $this->__('Place any HTML in this block to be displayed in the Mega Nav.  Tip:  Disable the WYSIWYG editor to place pure HTML code'),

		));

		/*$fieldset->addField('sortorder', 'text', array(
			'label' => Mage::helper('megamenu2')->__('Link Position'),
			'name'  => 'sortorder',
			'note'	=> $this->__('This controls the order in which your links are displayed'),

		));*/
		$fieldset->addField('status', 'select', array(
			'label' => Mage::helper('megamenu2')->__('Status'),
			'name'  => 'status',
			'values'=> array(
				array(
					'value' => 1,
					'label' => Mage::helper('megamenu2')->__('Enabled'),
				),
				array(
					'value' => 0,
					'label' => Mage::helper('megamenu2')->__('Disabled'),
				),
			),
		));
		if (Mage::app()->isSingleStoreMode()){
			$fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            Mage::registry('current_menuitem')->setStoreId(Mage::app()->getStore(true)->getId());
		}
		$form->addValues($this->getMenuitem()->getData());
		return parent::_prepareForm();
	}
	/**
	 * get the current menu item
	 * @access public
	 * @return JScriptz_MegaMenu2_Model_Menuitem
	 */
	public function getMenuitem(){
		return Mage::registry('menuitem');
	}
}