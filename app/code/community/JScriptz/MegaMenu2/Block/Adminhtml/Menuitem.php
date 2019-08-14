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
 * Menu Item admin block
 *
 * @category	JScriptz
 * @package	JScriptz_MegaMenu2
 */
class JScriptz_MegaMenu2_Block_Adminhtml_Menuitem extends Mage_Adminhtml_Block_Widget_Grid_Container{
	/**
	 * constructor
	 * @access public
	 * @return void
	 */
	public function __construct(){
		$this->_controller 		= 'adminhtml_menuitem';
		$this->_blockGroup 		= 'megamenu2';
		$this->_headerText 		= Mage::helper('megamenu2')->__('Menu Item');
		$this->_addButtonLabel 	= Mage::helper('megamenu2')->__('Add Menu Item');
		parent::__construct();
	}
}