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
 * MegaMenu2 textarea attribute WYSIWYG button
 * @category   JScriptz
 * @package	JScriptz_MegaMenu2
 * @author Jason Lotzer
 */
class JScriptz_MegaMenu2_Block_Adminhtml_Helper_Wysiwyg extends Varien_Data_Form_Element_Textarea
{
	/**
	 * Retrieve additional html and put it at the end of element html
	 * @access public
	 * @return string
	 * @author Jason Lotzer
	 */
	public function getAfterElementHtml(){
		$html = parent::getAfterElementHtml();
		$disabled = ($this->getDisabled() || $this->getReadonly());
		$html .= Mage::getSingleton('core/layout')
			->createBlock('adminhtml/widget_button', '', array(
				'label'   => Mage::helper('catalog')->__('WYSIWYG Editor'),
				'type'=> 'button',
				'disabled' => $disabled,
				'class' => ($disabled) ? 'disabled btn-wysiwyg' : 'btn-wysiwyg',
				'onclick' => 'catalogWysiwygEditor.open(\''.Mage::helper('adminhtml')->getUrl('*/*/wysiwyg').'\', \''.$this->getHtmlId().'\')'
			))->toHtml();
		return $html;
	}
}
