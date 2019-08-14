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
 * @license		http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * MegaMenu2 module install script
 *
 * @category	JScriptz
 * @package	JScriptz_MegaMenu2
 */
$this->startSetup();
$table = $this->getConnection()
	->newTable($this->getTable('megamenu2/menuitem'))
	->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'identity'  => true,
		'nullable'  => false,
		'primary'   => true,
		), 'Menu Item ID')
	->addColumn('linktitle', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
		'nullable'  => false,
		), 'Link Title')

	->addColumn('linkpath', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
		), 'Link Path')

	->addColumn('linktarget', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
		), 'Link Target')

	->addColumn('menuhtml', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
		), 'Menu HTML')

	->addColumn('sortorder', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		), 'Link Position')

	->addColumn('status', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		), 'Status')

	->addColumn('parent_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		), 'Parent id')

	->addColumn('path', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
		), 'Path')

	->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		), 'Position')

	->addColumn('level', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		), 'Level')

	->addColumn('children_count', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		), 'Children count')

	->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
		), 'Menu Item Creation Time')
	->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
		), 'Menu Item Modification Time')
	->setComment('Menu Item Table');
$this->getConnection()->createTable($table);

$table = $this->getConnection()
	->newTable($this->getTable('megamenu2/menuitem_store'))
	->addColumn('menuitem_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
		'nullable'  => false,
		'primary'   => true,
		), 'Menu Item ID')
	->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		'primary'   => true,
		), 'Store ID')
	->addIndex($this->getIdxName('megamenu2/menuitem_store', array('store_id')), array('store_id'))
	->addForeignKey($this->getFkName('megamenu2/menuitem_store', 'menuitem_id', 'megamenu2/menuitem', 'entity_id'), 'menuitem_id', $this->getTable('megamenu2/menuitem'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
	->addForeignKey($this->getFkName('megamenu2/menuitem_store', 'store_id', 'core/store', 'store_id'), 'store_id', $this->getTable('core/store'), 'store_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
	->setComment('Menu Items To Store Linkage Table');
$this->getConnection()->createTable($table);
$this->endSetup();