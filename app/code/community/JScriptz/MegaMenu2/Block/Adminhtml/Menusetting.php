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
 * Menu Setting admin block
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Block_Adminhtml_Menusetting extends Mage_Adminhtml_Block_Widget_Grid_Container{
	/**
	 * constructor
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function __construct(){
		$this->_controller 		= 'adminhtml_menusetting';
		$this->_blockGroup 		= 'megamenu2';
		$this->_headerText 		= Mage::helper('megamenu2')->__('Menu Setting');
		$this->_addButtonLabel 	= Mage::helper('megamenu2')->__('Add Menu Setting');
		parent::__construct();
	}
}
