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
 * Menu Setting admin edit block
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Block_Adminhtml_Menusetting_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{
	/**
	 * constuctor
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function __construct(){
		parent::__construct();
		$this->_blockGroup = 'megamenu2';
		$this->_controller = 'adminhtml_menusetting';
		$this->_updateButton('save', 'label', Mage::helper('megamenu2')->__('Save Menu Setting'));
		$this->_updateButton('delete', 'label', Mage::helper('megamenu2')->__('Delete Menu Setting'));
		$this->_addButton('saveandcontinue', array(
			'label'		=> Mage::helper('megamenu2')->__('Save And Continue Edit'),
			'onclick'	=> 'saveAndContinueEdit()',
			'class'		=> 'save',
		), -100);
		$this->_formScripts[] = "
			function saveAndContinueEdit(){
				editForm.submit($('edit_form').action+'back/edit/');
			}
		";
	}
	/**
	 * get the edit form header
	 * @access public
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getHeaderText(){
		if( Mage::registry('menusetting_data') && Mage::registry('menusetting_data')->getId() ) {
			return Mage::helper('megamenu2')->__("Edit Menu Setting '%s'", $this->htmlEscape(Mage::registry('menusetting_data')->getMenutitle()));
		} 
		else {
			return Mage::helper('megamenu2')->__('Add Menu Setting');
		}
	}
}
