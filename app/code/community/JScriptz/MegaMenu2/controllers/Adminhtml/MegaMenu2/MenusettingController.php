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
 * Menu Setting admin controller
 *
 * @category	JScriptz
 * @package		JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Adminhtml_MegaMenu2_MenusettingController extends JScriptz_MegaMenu2_Controller_Adminhtml_MegaMenu2{
	/**
	 * init the menusetting
	 * @access protected
	 * @return JScriptz_MegaMenu2_Model_Menusetting
	 */
	protected function _initMenusetting(){
		$menusettingId  = (int) $this->getRequest()->getParam('id');
		$menusetting	= Mage::getModel('megamenu2/menusetting');
		if ($menusettingId) {
			$menusetting->load($menusettingId);
		}
		Mage::register('current_menusetting', $menusetting);
		return $menusetting;
	}
 	/**
	 * default action
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function indexAction() {
		$this->loadLayout();
		$this->_title(Mage::helper('megamenu2')->__('MegaMenu2'))
			 ->_title(Mage::helper('megamenu2')->__('Menu Settings'));
		$this->renderLayout();
	}
	/**
	 * grid action
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function gridAction() {
		$this->loadLayout()->renderLayout();
	}
	/**
	 * edit menu setting - action
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function editAction() {
		$menusettingId	= $this->getRequest()->getParam('id');
		$menusetting  	= $this->_initMenusetting();
		if ($menusettingId && !$menusetting->getId()) {
			$this->_getSession()->addError(Mage::helper('megamenu2')->__('This menu setting no longer exists.'));
			$this->_redirect('*/*/');
			return;
		}
		$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
		if (!empty($data)) {
			$menusetting->setData($data);
		}
		Mage::register('menusetting_data', $menusetting);
		$this->loadLayout();
		$this->_title(Mage::helper('megamenu2')->__('MegaMenu2'))
			 ->_title(Mage::helper('megamenu2')->__('Menu Settings'));
		if ($menusetting->getId()){
			$this->_title($menusetting->getMenutitle());
		}
		else{
			$this->_title(Mage::helper('megamenu2')->__('Add menu setting'));
		}
		if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) { 
			$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true); 
		}
		$this->renderLayout();
	}
	/**
	 * new menu setting action
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function newAction() {
		$this->_forward('edit');
	}
	/**
	 * save menu setting - action
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function saveAction() {
		if ($data = $this->getRequest()->getPost('menusetting')) {
			try {
				$menusetting = $this->_initMenusetting();
				$menusetting->addData($data);
				$products = $this->getRequest()->getPost('products', -1);
				if ($products != -1) {
					$menusetting->setProductsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($products));
				}
				$menuitems = $this->getRequest()->getPost('menuitem_ids', -1);
				if ($menuitems != -1) {
					$menuitems = explode(',', $menuitems);
					$menuitems = array_unique($menuitems); 
					$menusetting->setMenuitemsData($menuitems);
				}
				$menusetting->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('megamenu2')->__('Menu Setting was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);
				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $menusetting->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
			} 
			catch (Mage_Core_Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
			catch (Exception $e) {
				Mage::logException($e);
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('megamenu2')->__('There was a problem saving the menu setting.'));
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		}
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('megamenu2')->__('Unable to find menu setting to save.'));
		$this->_redirect('*/*/');
	}
	/**
	 * delete menu setting - action
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0) {
			try {
				$menusetting = Mage::getModel('megamenu2/menusetting');
				$menusetting->setId($this->getRequest()->getParam('id'))->delete();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('megamenu2')->__('Menu Setting was successfully deleted.'));
				$this->_redirect('*/*/');
				return; 
			}
			catch (Mage_Core_Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('megamenu2')->__('There was an error deleteing menu setting.'));
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				Mage::logException($e);
				return;
			}
		}
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('megamenu2')->__('Could not find menu setting to delete.'));
		$this->_redirect('*/*/');
	}
	/**
	 * mass delete menu setting - action
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function massDeleteAction() {
		$menusettingIds = $this->getRequest()->getParam('menusetting');
		if(!is_array($menusettingIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('megamenu2')->__('Please select menu settings to delete.'));
		}
		else {
			try {
				foreach ($menusettingIds as $menusettingId) {
					$menusetting = Mage::getModel('megamenu2/menusetting');
					$menusetting->setId($menusettingId)->delete();
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('megamenu2')->__('Total of %d menu settings were successfully deleted.', count($menusettingIds)));
			}
			catch (Mage_Core_Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('megamenu2')->__('There was an error deleteing menu settings.'));
				Mage::logException($e);
			}
		}
		$this->_redirect('*/*/index');
	}
	/**
	 * mass Disable Menu change - action
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function massDisablemenuAction(){
		$menusettingIds = $this->getRequest()->getParam('menusetting');
		if(!is_array($menusettingIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('megamenu2')->__('Please select menu settings.'));
		} 
		else {
			try {
				foreach ($menusettingIds as $menusettingId) {
				$menusetting = Mage::getSingleton('megamenu2/menusetting')->load($menusettingId)
							->setDisablemenu($this->getRequest()->getParam('flag_disablemenu'))
							->setIsMassupdate(true)
							->save();
				}
				$this->_getSession()->addSuccess($this->__('Total of %d menu settings were successfully updated.', count($menusettingIds)));
			}
			catch (Mage_Core_Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('megamenu2')->__('There was an error updating menu settings.'));
				Mage::logException($e);
			}
		}
		$this->_redirect('*/*/index');
	}
	/**
	 * get grid of products action
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function productsAction(){
		$this->_initMenusetting();
		$this->loadLayout();
		$this->getLayout()->getBlock('menusetting.edit.tab.product')
			->setMenusettingProducts($this->getRequest()->getPost('menusetting_products', null));
		$this->renderLayout();
	}
	/**
	 * get grid of products action
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function productsgridAction(){
		$this->_initMenusetting();
		$this->loadLayout();
		$this->getLayout()->getBlock('menusetting.edit.tab.product')
			->setMenusettingProducts($this->getRequest()->getPost('menusetting_products', null));
		$this->renderLayout();
	}
	/**
	 * get menuitems action
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function menuitemsAction(){
		$this->_initMenusetting();
		$this->loadLayout();
		$this->renderLayout();
	}
	/**
	 * get child menuitems  action
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function menuitemsJsonAction(){
		$this->_initMenusetting();
		$this->getResponse()->setBody(
			$this->getLayout()->createBlock('megamenu2/adminhtml_menusetting_edit_tab_menuitem')
				->getMenuitemChildrenJson($this->getRequest()->getParam('menuitem'))
		);
	}
	/**
	 * export as csv - action
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function exportCsvAction(){
		$fileName   = 'menusetting.csv';
		$content	= $this->getLayout()->createBlock('megamenu2/adminhtml_menusetting_grid')->getCsv();
		$this->_prepareDownloadResponse($fileName, $content);
	}
	/**
	 * export as MsExcel - action
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function exportExcelAction(){
		$fileName   = 'menusetting.xls';
		$content	= $this->getLayout()->createBlock('megamenu2/adminhtml_menusetting_grid')->getExcelFile();
		$this->_prepareDownloadResponse($fileName, $content);
	}
	/**
	 * export as xml - action
	 * @access public
	 * @return void
	 * @author Jason Lotzer
	 */
	public function exportXmlAction(){
		$fileName   = 'menusetting.xml';
		$content	= $this->getLayout()->createBlock('megamenu2/adminhtml_menusetting_grid')->getXml();
		$this->_prepareDownloadResponse($fileName, $content);
	}
}
