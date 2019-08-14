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
 * Menu Setting admin grid block
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Block_Adminhtml_Menusetting_Grid extends Mage_Adminhtml_Block_Widget_Grid{
	/**
	 * constructor
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function __construct(){
		parent::__construct();
		$this->setId('menusettingGrid');
		$this->setDefaultSort('entity_id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
		$this->setUseAjax(true);
	}
	/**
	 * prepare collection
	 * @access protected
	 * @return JScriptz_MegaMenu2_Block_Adminhtml_Menusetting_Grid
	 * @author Jason Lotzer
	 */
	protected function _prepareCollection(){
		$collection = Mage::getModel('megamenu2/menusetting')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	/**
	 * prepare grid collection
	 * @access protected
	 * @return JScriptz_MegaMenu2_Block_Adminhtml_Menusetting_Grid
	 * @author Jason Lotzer
	 */
	protected function _prepareColumns(){
		$this->addColumn('entity_id', array(
			'header'	=> Mage::helper('megamenu2')->__('Id'),
			'index'		=> 'entity_id',
			'type'		=> 'number'
		));
		$this->addColumn('menutitle', array(
			'header'=> Mage::helper('megamenu2')->__('Menu Title'),
			'index' => 'menutitle',
			'type'	 	=> 'text',

		));
		$this->addColumn('disablemenu', array(
			'header'=> Mage::helper('megamenu2')->__('Disable Menu'),
			'index' => 'disablemenu',
			'type'		=> 'options',
			'options'	=> array(
				'1' => Mage::helper('megamenu2')->__('Yes'),
				'0' => Mage::helper('megamenu2')->__('No'),
			)

		));
		if (!Mage::app()->isSingleStoreMode()) {
			$this->addColumn('store_id', array(
				'header'=> Mage::helper('megamenu2')->__('Store Views'),
				'index' => 'store_id',
				'type'  => 'store',
				'store_all' => true,
				'store_view'=> true,
				'sortable'  => false,
				'filter_condition_callback'=> array($this, '_filterStoreCondition'),
			));
		}
		$this->addColumn('action',
			array(
				'header'=>  Mage::helper('megamenu2')->__('Action'),
				'width' => '100',
				'type'  => 'action',
				'getter'=> 'getId',
				'actions'   => array(
					array(
						'caption'   => Mage::helper('megamenu2')->__('Edit'),
						'url'   => array('base'=> '*/*/edit'),
						'field' => 'id'
					)
				),
				'filter'=> false,
				'is_system'	=> true,
				'sortable'  => false,
		));
		$this->addExportType('*/*/exportCsv', Mage::helper('megamenu2')->__('CSV'));
		$this->addExportType('*/*/exportExcel', Mage::helper('megamenu2')->__('Excel'));
		$this->addExportType('*/*/exportXml', Mage::helper('megamenu2')->__('XML'));
		return parent::_prepareColumns();
	}
	/**
	 * prepare mass action
	 * @access protected
	 * @return JScriptz_MegaMenu2_Block_Adminhtml_Menusetting_Grid
	 * @author Jason Lotzer
	 */
	protected function _prepareMassaction(){
		$this->setMassactionIdField('entity_id');
		$this->getMassactionBlock()->setFormFieldName('menusetting');
		$this->getMassactionBlock()->addItem('delete', array(
			'label'=> Mage::helper('megamenu2')->__('Delete'),
			'url'  => $this->getUrl('*/*/massDelete'),
			'confirm'  => Mage::helper('megamenu2')->__('Are you sure?')
		));
		$this->getMassactionBlock()->addItem('disablemenu', array(
			'label'=> Mage::helper('megamenu2')->__('Change Disable Menu'),
			'url'  => $this->getUrl('*/*/massDisablemenu', array('_current'=>true)),
			'additional' => array(
				'flag_disablemenu' => array(
						'name' => 'flag_disablemenu',
						'type' => 'select',
						'class' => 'required-entry',
						'label' => Mage::helper('megamenu2')->__('Disable Menu'),
						'values' => array(
								'1' => Mage::helper('megamenu2')->__('Yes'),
								'0' => Mage::helper('megamenu2')->__('No'),
						)
				)
			)
		));
		return $this;
	}
	/**
	 * get the row url
	 * @access public
	 * @param JScriptz_MegaMenu2_Model_Menusetting
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getRowUrl($row){
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
	/**
	 * get the grid url
	 * @access public
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getGridUrl(){
		return $this->getUrl('*/*/grid', array('_current'=>true));
	}
	/**
	 * after collection load
	 * @access protected
	 * @return JScriptz_MegaMenu2_Block_Adminhtml_Menusetting_Grid
	 * @author Jason Lotzer
	 */
	protected function _afterLoadCollection(){
		$this->getCollection()->walk('afterLoad');
		parent::_afterLoadCollection();
	}
	/**
	 * filter store column
	 * @access protected
	 * @param JScriptz_MegaMenu2_Model_Resource_Menusetting_Collection $collection
	 * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
	 * @return JScriptz_MegaMenu2_Block_Adminhtml_Menusetting_Grid
	 * @author Jason Lotzer
	 */
	protected function _filterStoreCondition($collection, $column){
		if (!$value = $column->getFilter()->getValue()) {
        	return;
		}
		$collection->addStoreFilter($value);
		return $this;
    }
}
