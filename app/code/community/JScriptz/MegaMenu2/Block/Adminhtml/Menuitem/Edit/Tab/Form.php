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
 * Menu Item edit form tab
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form{	
	/**
	 * prepare the form
	 * @access protected
	 * @return MegaMenu2_Menuitem_Block_Adminhtml_Menuitem_Edit_Tab_Form
	 * @author Jason Lotzer
	 */
	protected function _prepareForm(){
		$form = new Varien_Data_Form();
		$form->setHtmlIdPrefix('menuitem_');
		$form->setFieldNameSuffix('menuitem');
		$this->setForm($form);
		$fieldset = $form->addFieldset('menuitem_form', array('legend'=>Mage::helper('megamenu2')->__('Menu Item')));
		$fieldset->addType('file', Mage::getConfig()->getBlockClassName('megamenu2/adminhtml_menuitem_helper_file'));
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
	
  $fieldset->addField('linktype', 'select', array(
      'label'     => 'Link Type',
      'name'      => 'linktype',
      'note'      => 'Select from the list of link type options',
      'values'    => array(
          array(
              'value'     => 0,
              'label'     => 'Standard Link',
          ),

          array(
              'value'     => 1,
              'label'     => 'Dropdown with Categories',
          ),
          array(
              'value'     => 2,
              'label'     => 'Dropdown with HTML',
          ),
          array(
              'value'     => 3,
              'label'     => 'Contact Us',
          ),
          array(
              'value'     => 4,
              'label'     => 'Dropdown with Custom Featured Products',
          ),
      ),
  ));
		$fieldset->addField('linktitle', 'text', array(
			'label' => Mage::helper('megamenu2')->__('Link Title'),
			'name'  => 'linktitle',
			'required'  => true,
			'class' => 'required-entry',

		));
		$fieldset->addField('linkpath', 'text', array(
			'label' => Mage::helper('megamenu2')->__('Link Path'),
			'note'  => 'The URL you want the link to point to',
			'name'  => 'linkpath',

		));
		$fieldset->addField('linkclass', 'text', array(
			'label' => Mage::helper('megamenu2')->__('Link Class'),
			'note'  => 'CSS class to style your link',
			'name'  => 'linkclass',
		));
		$fieldset->addField('titletag', 'text', array(
			'label' => Mage::helper('megamenu2')->__('Title Tag'),
			'note'  => 'Title Tag that shows a tooltip on hover, also good for seo purposes',
			'name'  => 'titletag',
		));
		$fieldset->addField('linktarget', 'select', array(
		      'label'     => 'Target',
		      'name'      => 'linktarget',
		      'note'      => 'Sets whether the link opens in same window or different tab',
		      'values'    => array(
		          array(
		              'value'     => 0,
		              'label'     => 'Same Window',
		          ),
		
		          array(
		              'value'     => 1,
		              'label'     => 'New Tab',
		          ),
		      ),
		  ));
		  //if($this->getMenuItem()->getData('linktype') == 2){
		$fieldset->addField('menuhtml', 'editor', array(
			'label' => Mage::helper('megamenu2')->__('Menu HTML'),
			'name'  => 'menuhtml',

		));
		//}
		$fieldset->addField('linkimage', 'file', array(
			'label' => Mage::helper('megamenu2')->__('Link Image'),
			'name'  => 'linkimage',
			'note'	=> $this->__('Upload an image to include next to your menu link'),

		));
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
