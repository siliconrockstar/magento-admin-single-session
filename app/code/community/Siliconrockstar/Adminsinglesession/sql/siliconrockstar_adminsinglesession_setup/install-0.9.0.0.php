<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();
$conn = $installer->getConnection();

$test = $installer->getTable('keys');

$table = $installer->getConnection()->newTable($installer->getTable('siliconrockstar_adminsinglesession_key'))
        ->addColumn('key_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
                ), 'key_id')
        ->addColumn('username', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => false,
            'default' => '',
                ), 'username')
        ->addColumn('key', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
    'nullable' => false,
    'default' => '',
        ), 'key');

$installer->getConnection()->createTable($table);
$installer->endSetup();
