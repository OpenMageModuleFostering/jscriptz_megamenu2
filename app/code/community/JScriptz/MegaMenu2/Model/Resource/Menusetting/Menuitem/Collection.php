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
 * Menu Setting - Menu Item relation resource model collection
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Model_Resource_Menusetting_Menuitem_Collection extends JScriptz_MegaMenu2_Model_Resource_Menuitem_Collection{
	/**
	 * remember if fields have been joined
	 * @var bool
	 */
	protected $_joinedFields = false;
	/**
	 * join the link table
	 * @access public
	 * @return JScriptz_MegaMenu2_Model_Resource_Menusetting_Menuitem_Collection
	 * @author Jason Lotzer
	 */
	public function joinFields(){
		if (!$this->_joinedFields){
			$this->getSelect()->join(
				array('related' => $this->getTable('megamenu2/menusetting_menuitem')),
				'related.menuitem_id = main_table.entity_id',
				array('position')
			);
			$this->_joinedFields = true;
		}
		return $this;
	}
	/**
	 * add menusetting filter
	 * @access public
	 * @param JScriptz_MegaMenu2_Model_Menusetting | int $menusetting
	 * @return JScriptz_MegaMenu2_Model_Resource_Menusetting_Menuitem_Collection
	 * @author Jason Lotzer
	 */
	public function addMenusettingFilter($menusetting){
		if ($menusetting instanceof JScriptz_MegaMenu2_Model_Menusetting){
			$menusetting = $menusetting->getId();
		}
		if (!$this->_joinedFields){
			$this->joinFields();
		}
		$this->getSelect()->where('related.menusetting_id = ?', $menusetting);
		return $this;
	}
}
