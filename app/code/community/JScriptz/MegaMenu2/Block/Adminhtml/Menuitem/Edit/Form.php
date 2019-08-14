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
 * Menu Item edit form
 *
 * @category	JScriptz
 * @package	JScriptz_MegaMenu2

 */
class JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Edit_Form extends JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Abstract
{
	/**
	 * Additional buttons on menu item page
	 * @var array
	 */
	protected $_additionalButtons = array();
	/**
	 * constroctor
	 * set template
	 * @access public
	 * @return void
	 */
	public function __construct(){
		parent::__construct();
		$this->setTemplate('jscriptz_megamenu2/menuitem/edit/form.phtml');
	}
	/**
	 * prepare the layout
	 * @access protected
	 * @return JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Edit_Form
	 */
	protected function _prepareLayout(){
		$menuitem = $this->getMenuitem();
		$menuitemId = (int)$menuitem->getId();
		$this->setChild('tabs',
			$this->getLayout()->createBlock('megamenu2/adminhtml_menuitem_edit_tabs', 'tabs')
		);		
		$this->setChild('save_button',
			$this->getLayout()->createBlock('adminhtml/widget_button')
				->setData(array(
					'label' => Mage::helper('megamenu2')->__('Save Menu Item'),
					'onclick'   => "menuitemSubmit('" . $this->getSaveUrl() . "', true)",
					'class' => 'save'
			))
		);		
		// Delete button
		if (!in_array($menuitemId, $this->getRootIds())) {
			$this->setChild('delete_button',
				$this->getLayout()->createBlock('adminhtml/widget_button')
					->setData(array(
						'label' => Mage::helper('megamenu2')->__('Delete Menu Item'),
						'onclick'   => "menuitemDelete('" . $this->getUrl('*/*/delete', array('_current' => true)) . "', true, {$menuitemId})",
						'class' => 'delete'
				))
			);
		}
		
		// Reset button
		$resetPath = $menuitem ? '*/*/edit' : '*/*/add';
		$this->setChild('reset_button',
			$this->getLayout()->createBlock('adminhtml/widget_button')
				->setData(array(
					'label' => Mage::helper('megamenu2')->__('Reset'),
					'onclick'   => "menuitemReset('".$this->getUrl($resetPath, array('_current'=>true))."',true)"
			))
		);
		return parent::_prepareLayout();
	}
	/**
	 * get html for delete button
	 * @access public
	 * @return string
	 */
	public function getDeleteButtonHtml(){
		return $this->getChildHtml('delete_button');
	}
	/**
	 * get html for save button
	 * @access public
	 * @return string
	 */
	public function getSaveButtonHtml(){
		return $this->getChildHtml('save_button');
	}
	/**
	 * get html for reset button
	 * @access public
	 * @return string
	 */
	public function getResetButtonHtml(){
		return $this->getChildHtml('reset_button');
	}
	/**
	 * Retrieve additional buttons html
	 * @access public
	 * @return string
	 */
	public function getAdditionalButtonsHtml(){
		$html = '';
		foreach ($this->_additionalButtons as $childName) {
			$html .= $this->getChildHtml($childName);
		}
		return $html;
	}

	/**
	 * Add additional button
	 *
	 * @param string $alias
	 * @param array $config
	 * @return JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Edit_Form
	 */
	public function addAdditionalButton($alias, $config){
		if (isset($config['name'])) {
			$config['element_name'] = $config['name'];
		}
		$this->setChild($alias . '_button',
		$this->getLayout()->createBlock('adminhtml/widget_button')->addData($config));
		$this->_additionalButtons[$alias] = $alias . '_button';
		return $this;
	}
	/**
	 * Remove additional button
	 * @access public
	 * @param string $alias
	 * @return JScriptz_MegaMenu2_Block_Adminhtml_Menuitem_Edit_Form
	 */
	public function removeAdditionalButton($alias){
		if (isset($this->_additionalButtons[$alias])) {
			$this->unsetChild($this->_additionalButtons[$alias]);
			unset($this->_additionalButtons[$alias]);
		}
		return $this;
	}
	/**
	 * get html for tabs
	 * @access public
	 * @return string
	 */
	public function getTabsHtml(){
		return $this->getChildHtml('tabs');
	}
	/**
	 * get the form header
	 * @access public
	 * @return string
	 */
	public function getHeader(){
		if ($this->getMenuitemId()) {
			return $this->getMenuitemLinktitle();
		} 
		else {
			return Mage::helper('megamenu2')->__('New Root Menu Item');
		}
	}
	/**
	 * get the delete url
	 * @access public
	 * @param array $args
	 * @return string
	 */
	public function getDeleteUrl(array $args = array()){
		$params = array('_current'=>true);
		$params = array_merge($params, $args);
		return $this->getUrl('*/*/delete', $params);
	}
	/**
	 * Return URL for refresh input element 'path' in form
	 * @access public
	 * @param array $args
	 * @return string
	 */
	public function getRefreshPathUrl(array $args = array()){
		$params = array('_current'=>true);
		$params = array_merge($params, $args);
		return $this->getUrl('*/*/refreshPath', $params);
	}
	/**
	 * check if request is ajax
	 * @access public
	 * @return bool
	 */
	public function isAjax(){
		return Mage::app()->getRequest()->isXmlHttpRequest() || Mage::app()->getRequest()->getParam('isAjax');
	}
}