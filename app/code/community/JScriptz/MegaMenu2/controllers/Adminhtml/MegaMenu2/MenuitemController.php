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
 * Menu Item admin controller
 *
 * @category	JScriptz
 * @package	JScriptz_MegaMenu2

 */
class JScriptz_MegaMenu2_Adminhtml_MegaMenu2_MenuitemController extends JScriptz_MegaMenu2_Controller_Adminhtml_MegaMenu2{
	/**
	 * init menu item
	 * @access protected
	 * @return JScriptz_MegaMenu2_Model_Menuitem	
	 */
	protected function _initMenuitem(){
		$menuitemId = (int) $this->getRequest()->getParam('id',false);
		$menuitem = Mage::getModel('megamenu2/menuitem');
		if ($menuitemId) {
			$menuitem->load($menuitemId);
		}
		if ($activeTabId = (string) $this->getRequest()->getParam('active_tab_id')) {
			Mage::getSingleton('admin/session')->setActiveTabId($activeTabId);
		}
		Mage::register('menuitem', $menuitem);
		Mage::register('current_menuitem', $menuitem);
		return $menuitem;
	}
	/**
	 * default action
	 * @access public
	 * @return void
	
	 */
	public function indexAction(){
		$this->_forward('edit');
	}

	/**
	 * Add new menu item form
	 * @access public
	 * @return void
	
	 */
	public function addAction(){
		Mage::getSingleton('admin/session')->unsActiveTabId();
		$this->_forward('edit');
	}
	/**
	 * Edit menu item page
	 * @access public
	 * @return void
	
	 */
	public function editAction(){
		$params['_current'] = true;
		$redirect = false;
		$parentId = (int) $this->getRequest()->getParam('parent');
		$menuitemId = (int) $this->getRequest()->getParam('id');
		$_prevMenuitemId = Mage::getSingleton('admin/session')->getLastEditedMenuitem(true);
		if ($_prevMenuitemId && !$this->getRequest()->getQuery('isAjax') && !$this->getRequest()->getParam('clear')) {
			$this->getRequest()->setParam('id',$_prevMenuitemId);
		}
		if ($redirect) {
			$this->_redirect('*/*/edit', $params);
			return;
		}
		if (!($menuitem = $this->_initMenuitem())) {
			return;
		}
		$this->_title($menuitemId ? $menuitem->getLinktitle() : $this->__('New Menu Item'));
		$data = Mage::getSingleton('adminhtml/session')->getMenuitemData(true);
		if (isset($data['menuitem'])) {
			$menuitem->addData($data['menuitem']);
		}
		if ($this->getRequest()->getQuery('isAjax')) {
			$breadcrumbsPath = $menuitem->getPath();
			if (empty($breadcrumbsPath)) {
				$breadcrumbsPath = Mage::getSingleton('admin/session')->getDeletedPath(true);
				if (!empty($breadcrumbsPath)) {
					$breadcrumbsPath = explode('/', $breadcrumbsPath);
					if (count($breadcrumbsPath) <= 1) {
						$breadcrumbsPath = '';
					}
					else {
						array_pop($breadcrumbsPath);
						$breadcrumbsPath = implode('/', $breadcrumbsPath);
					}
				}
			}
			Mage::getSingleton('admin/session')->setLastEditedMenuitem($menuitem->getId());
			$this->loadLayout();			
			$eventResponse = new Varien_Object(array(
				'content' => $this->getLayout()->getBlock('menuitem.edit')->getFormHtml(). $this->getLayout()->getBlock('menuitem.tree')->getBreadcrumbsJavascript($breadcrumbsPath, 'editingMenuitemBreadcrumbs'),
				'messages' => $this->getLayout()->getMessagesBlock()->getGroupedHtml(),
			));
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($eventResponse->getData()));			
			return;
		}
		$this->loadLayout();
		$this->_title(Mage::helper('megamenu2')->__('MegaMenu2'))
			 ->_title(Mage::helper('megamenu2')->__('Menu Items'));
		$this->_setActiveMenu('megamenu2/menuitem');
		$this->getLayout()->getBlock('head')->setCanLoadExtJs(true)
			->setContainerCssClass('menuitem');
		
		$this->_addBreadcrumb(
			Mage::helper('megamenu2')->__('Manage Menu Items'),
			Mage::helper('megamenu2')->__('Manage Menu Items')
		);
		if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
			$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
		}
		$this->renderLayout();
	}
	/**
	 * Get tree node (Ajax version)
	 * @access public
	 * @return void
	
	 */
	public function menuitemsJsonAction(){
		if ($this->getRequest()->getParam('expand_all')) {
			Mage::getSingleton('admin/session')->setMenuitemIsTreeWasExpanded(true);
		} 
		else {
			Mage::getSingleton('admin/session')->setMenuitemIsTreeWasExpanded(false);
		}
		if ($menuitemId = (int) $this->getRequest()->getPost('id')) {
			$this->getRequest()->setParam('id', $menuitemId);
			if (!$menuitem = $this->_initMenuitem()) {
				return;
			}
			$this->getResponse()->setBody(
				$this->getLayout()->createBlock('megamenu2/adminhtml_menuitem_tree')->getTreeJson($menuitem)
			);
		}
	}
	/**
	 * Move menu item action
	 */
	public function moveAction(){
		$menuitem = $this->_initMenuitem();
		if (!$menuitem) {
			$this->getResponse()->setBody(Mage::helper('megamenu2')->__('Menu Item move error'));
			return;
		}
		$parentNodeId   = $this->getRequest()->getPost('pid', false);
		$prevNodeId = $this->getRequest()->getPost('aid', false);
		try {
			$menuitem->move($parentNodeId, $prevNodeId);
			$this->getResponse()->setBody("SUCCESS");
		}
		catch (Mage_Core_Exception $e) {
			$this->getResponse()->setBody($e->getMessage());
		}
		catch (Exception $e){
			$this->getResponse()->setBody(Mage::helper('megamenu2')->__('Menu Item move error'));
			Mage::logException($e);
		}
	}
	/**
	 * Tree Action
	 * Retrieve menu item tree
	 * @access public
	 * @return void
	
	 */
	public function treeAction(){
		$menuitemId = (int) $this->getRequest()->getParam('id');
		$menuitem = $this->_initMenuitem();
		$block = $this->getLayout()->createBlock('megamenu2/adminhtml_menuitem_tree');
		$root  = $block->getRoot();
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode(
			array(
				'data' => $block->getTree(),
				'parameters' => array(
					'text'=> $block->buildNodeName($root),
					'draggable'   => false,
					'allowDrop'   => ($root->getIsVisible()) ? true : false,
					'id'  => (int) $root->getId(),
					'expanded'=> (int) $block->getIsWasExpanded(),
					'menuitem_id' => (int) $menuitem->getId(),
					'root_visible'=> (int) $root->getIsVisible()
				)
			)
		));
	}
	/**
	 * Build response for refresh input element 'path' in form
	 * @access public
	 * @return void
	
	 */
	public function refreshPathAction(){
		if ($id = (int) $this->getRequest()->getParam('id')) {
			$menuitem = Mage::getModel('megamenu2/menuitem')->load($id);
			$this->getResponse()->setBody(
				Mage::helper('core')->jsonEncode(array(
				   'id' => $id,
				   'path' => $menuitem->getPath(),
				))
			);
		}
	}
	/**
	 * Delete menu item action
	 * @access public
	 * @return void
	
	 */
	public function deleteAction(){
		if ($id = (int) $this->getRequest()->getParam('id')) {
			try {
				$menuitem = Mage::getModel('megamenu2/menuitem')->load($id);
				Mage::getSingleton('admin/session')->setDeletedPath($menuitem->getPath());
				
				$menuitem->delete();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('megamenu2')->__('The menu item has been deleted.'));
			}
			catch (Mage_Core_Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('_current'=>true)));
				return;
			}
			catch (Exception $e){
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('megamenu2')->__('An error occurred while trying to delete the menu item.'));
				$this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('_current'=>true)));
				Mage::logException($e);
				return;
			}
		}
		$this->getResponse()->setRedirect($this->getUrl('*/*/', array('_current'=>true, 'id'=>null)));
	}
	/**
	 * Check if admin has permissions to visit related pages
	 * @access protected
	 * @return boolean
	
	 */
	protected function _isAllowed(){
		return Mage::getSingleton('admin/session')->isAllowed('megamenu2/menuitem');
	}	/**
	 * wyisiwyg action
	 * @access public
	 * @return void
	
	 */
	public function wysiwygAction(){
		$elementId = $this->getRequest()->getParam('element_id', md5(microtime()));
		$storeMediaUrl = Mage::app()->getStore(0)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
		
		$content = $this->getLayout()->createBlock('adminhtml/catalog_helper_form_wysiwyg_content', '', array(
			'editor_element_id' => $elementId,
			'store_id'  		=> 0,
			'store_media_url'   => $storeMediaUrl,
		));
		$this->getResponse()->setBody($content->toHtml());
	}
	/**
	 * Menu Item save action
	 * @access public
	 * @return void
	
	 */
	public function saveAction(){
		if (!$menuitem = $this->_initMenuitem()) {
			return;
		}
		$refreshTree = 'false';
		if ($data = $this->getRequest()->getPost('menuitem')) {
			$menuitem->addData($data);
			if (!$menuitem->getId()) {
				$parentId = $this->getRequest()->getParam('parent');
				if (!$parentId) {
					$parentId = Mage::helper('megamenu2/menuitem')->getRootMenuitemId();
				}
				$parentMenuitem = Mage::getModel('megamenu2/menuitem')->load($parentId);
				$menuitem->setPath($parentMenuitem->getPath());
			}
			try {
				$menuitem->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('megamenu2')->__('The menu item has been saved.'));
				$refreshTree = 'true';
			}
			catch (Exception $e){
				$this->_getSession()->addError($e->getMessage())->setMenuitemData($data);
				Mage::logException($e);
				$refreshTree = 'false';
			}
		}
		$url = $this->getUrl('*/*/edit', array('_current' => true, 'id' => $menuitem->getId()));
		$this->getResponse()->setBody(
			'<script type="text/javascript">parent.updateContent("' . $url . '", {}, '.$refreshTree.');</script>'
		);
	}
}