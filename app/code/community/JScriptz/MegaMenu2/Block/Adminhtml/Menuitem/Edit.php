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
 * Menu Item admin edit form
 *
 * @category	JScriptz
 * @package	JScriptz_MegaMenu2

 */
class JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{
	/**
	 * constructor
	 * @access public
	 * @return void
	 */
	public function __construct(){
		$this->_objectId	= 'entity_id';
		$this->_blockGroup	= 'megamenu2';
		$this->_controller  = 'adminhtml_menuitem';
		$this->_mode		= 'edit';
		parent::__construct();
		$this->setTemplate('jscriptz_megamenu2/menuitem/edit.phtml');
	}
}
