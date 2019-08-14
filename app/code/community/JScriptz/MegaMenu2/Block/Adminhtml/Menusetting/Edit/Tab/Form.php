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
 * Menu Setting edit form tab
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Block_Adminhtml_Menusetting_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form{	
	/**
	 * prepare the form
	 * @access protected
	 * @return MegaMenu2_Menusetting_Block_Adminhtml_Menusetting_Edit_Tab_Form
	 * @author Jason Lotzer
	 */
	protected function _prepareForm(){
		$form = new Varien_Data_Form();
		$form->setHtmlIdPrefix('menusetting_');
		$form->setFieldNameSuffix('menusetting');
		$this->setForm($form);
		$fieldset = $form->addFieldset('menusetting_form', array('legend'=>Mage::helper('megamenu2')->__('Menu Setting')));

		$fieldset->addField('menutitle', 'text', array(
			'label' => Mage::helper('megamenu2')->__('Menu Title'),
			'name'  => 'menutitle',
			'note'	=> $this->__('Title for your menu'),
			'required'  => true,
			'class' => 'required-entry',

		));

		$fieldset->addField('disablemenu', 'select', array(
			'label' => Mage::helper('megamenu2')->__('Disable Menu'),
			'name'  => 'disablemenu',
			'note'	=> $this->__('Disables Menu for Debugging'),

			'values'=> array(
				array(
					'value' => 1,
					'label' => Mage::helper('megamenu2')->__('Yes'),
				),
				array(
					'value' => 0,
					'label' => Mage::helper('megamenu2')->__('No'),
				),
			),
		));
		if (Mage::app()->isSingleStoreMode()){
			$fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            Mage::registry('current_menusetting')->setStoreId(Mage::app()->getStore(true)->getId());
		}
		if (Mage::getSingleton('adminhtml/session')->getMenusettingData()){
			$form->setValues(Mage::getSingleton('adminhtml/session')->getMenusettingData());
			Mage::getSingleton('adminhtml/session')->setMenusettingData(null);
		}
		elseif (Mage::registry('current_menusetting')){
			$form->setValues(Mage::registry('current_menusetting')->getData());
		}
		return parent::_prepareForm();
	}
}
