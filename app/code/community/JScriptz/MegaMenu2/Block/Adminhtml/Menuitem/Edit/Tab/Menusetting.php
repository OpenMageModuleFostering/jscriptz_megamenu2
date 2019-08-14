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
 * menuitem - menusetting relation edit block
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Edit_Tab_Menusetting extends Mage_Adminhtml_Block_Widget_Grid{
	/**
	 * Set grid params
	 * @access protected
	 * @return void
	 * @author Jason Lotzer
	 */
	public function __construct(){
		parent::__construct();
		$this->setId('menusetting_grid');
		$this->setDefaultSort('position');
		$this->setDefaultDir('ASC');
		$this->setUseAjax(true);
		if ($this->getMenuitem()->getId()) {
			$this->setDefaultFilter(array('in_menusettings'=>1));
		}
	}
	/**
	 * prepare the menusetting collection
	 * @access protected 
	 * @return JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Edit_Tab_Menusetting
	 * @author Jason Lotzer
	 */
	protected function _prepareCollection() {
		$collection = Mage::getResourceModel('megamenu2/menusetting_collection');
		if ($this->getMenuitem()->getId()){
			$constraint = 'related.menuitem_id='.$this->getMenuitem()->getId();
		}
		else{
			$constraint = 'related.menuitem_id=0';
		}
		$collection->getSelect()->joinLeft(
			array('related'=>$collection->getTable('megamenu2/menuitem_menusetting')),
			'related.menusetting_id=main_table.entity_id AND '.$constraint,
			array('position'));
		$this->setCollection($collection);
		parent::_prepareCollection();
		return $this;
	}
	/**
	 * prepare mass action grid
	 * @access protected
	 * @return JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Edit_Tab_Menusetting
	 * @author Jason Lotzer
	 */ 
	protected function _prepareMassaction(){
		return $this;
	}
	/**
	 * prepare the grid columns
	 * @access protected
	 * @return JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Edit_Tab_Menusetting
	 * @author Jason Lotzer
	 */
	protected function _prepareColumns(){
		$this->addColumn('in_menusettings', array(
			'header_css_class'  => 'a-center',
			'type'  => 'checkbox',
			'name'  => 'in_menusettings',
			'values'=> $this->_getSelectedMenusettings(),
			'align' => 'center',
			'index' => 'entity_id'
		));
		$this->addColumn('menutitle', array(
			'header'=> Mage::helper('megamenu2')->__('Menu Title'),
			'align' => 'left',
			'index' => 'menutitle',
		));
		$this->addColumn('position', array(
			'header'=> Mage::helper('megamenu2')->__('Position'),
			'name'  => 'position',
			'width' => 60,
			'type'  => 'number',
			'validate_class'=> 'validate-number',
			'index' => 'position',
			'editable'  => true,
		));
	}
	/**
	 * Retrieve selected menusettings
	 * @access protected
	 * @return array
	 * @author Jason Lotzer
	 */
	protected function _getSelectedMenusettings(){
		$menusettings = $this->getMenuitemMenusettings();
		if (!is_array($menusettings)) {
			$menusettings = array_keys($this->getSelectedMenusettings());
		}
		return $menusettings;
	}
 	/**
	 * Retrieve selected menusettings
	 * @access protected
	 * @return array
	 * @author Jason Lotzer
	 */
	public function getSelectedMenusettings() {
		$menusettings = array();
		$selected = Mage::registry('current_menuitem')->getSelectedMenusettings();
		if (!is_array($selected)){
			$selected = array();
		}
		foreach ($selected as $menusetting) {
			$menusettings[$menusetting->getId()] = array('position' => $menusetting->getPosition());
		}
		return $menusettings;
	}
	/**
	 * get row url
	 * @access public
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getRowUrl($item){
		return '#';
	}
	/**
	 * get grid url
	 * @access public
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getGridUrl(){
		return $this->getUrl('*/*/menusettingsGrid', array(
			'id'=>$this->getMenuitem()->getId()
		));
	}
	/**
	 * get the current menuitem
	 * @access public
	 * @return JScriptz_MegaMenu2_Model_Menuitem
	 * @author Jason Lotzer
	 */
	public function getMenuitem(){
		return Mage::registry('current_menuitem');
	}
	/**
	 * Add filter
	 * @access protected
	 * @param object $column
	 * @return JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Edit_Tab_Menusetting
	 * @author Jason Lotzer
	 */
	protected function _addColumnFilterToCollection($column){
		// Set custom filter for in product flag
		if ($column->getId() == 'in_menusettings') {
			$menusettingIds = $this->_getSelectedMenusettings();
			if (empty($menusettingIds)) {
				$menusettingIds = 0;
			}
			if ($column->getFilter()->getValue()) {
				$this->getCollection()->addFieldToFilter('entity_id', array('in'=>$menusettingIds));
			} 
			else {
				if($menusettingIds) {
					$this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$menusettingIds));
				}
			}
		} 
		else {
			parent::_addColumnFilterToCollection($column);
		}
		return $this;
	}
}
