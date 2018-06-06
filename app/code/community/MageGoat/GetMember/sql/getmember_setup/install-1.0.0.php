<?php
/**
 * Magento
 *
 *
 */


/* @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;

$installer->startSetup();

/**
 * Create table 'getmember_member'
 */
$memberUserTable = $installer->getConnection()
    ->newTable($installer->getTable('getmember/member'))

    ->addColumn('member_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Member User Id')

    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => true,
        ), 'customer id associente to member')

    ->addColumn('member_code', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Member unique Code')

    ->addColumn('comment', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'default' => null
        ), 'comment')

    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        ), 'Created At')

    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        ), 'Updated At')

    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
        'default'   => 0,
        ), 'IS Active')

    ->addColumn('custom_points', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => true,
        ), 'Custom Points')

    ->addIndex($installer->getIdxName('getmember/member', array('is_active')),
        array('is_active'))

    ->addIndex($installer->getIdxName('getmember/member', array('member_code')),
        array('member_code'))

    ->addIndex($installer->getIdxName('getmember/member', array('member_code'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
        array('member_code'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->setComment('Member unique Code')
    
    ->addIndex($installer->getIdxName('getmember/member', array('customer_id')),
        array('customer_id'));


$installer->getConnection()->createTable($memberUserTable);


/**
 * Create table 'getmember_point'
 */
$memberPointsTable = $installer->getConnection()
    ->newTable($installer->getTable('getmember/point'))


    ->addColumn('point_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Point Member Id')

    ->addColumn('order_increment_id', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
        'nullable'  => false,
        ), 'Order Increment Id')

    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        ), 'customer id associente to points')

    ->addColumn('member_code', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'code associente to member')

    ->addColumn('points', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => true,
        ), 'Points')

    ->addColumn('state', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        ), 'State')

    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        ), 'Created At')

    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        ), 'Updated At')

    ->addIndex($installer->getIdxName('getmember/point', array('order_increment_id')),
        array('order_increment_id'))

    ->addIndex($installer->getIdxName('getmember/point', array('customer_id')),
        array('customer_id'))

    ->addIndex($installer->getIdxName('getmember/point', array('state')),
        array('state'));


$installer->getConnection()->createTable($memberPointsTable);

$installer->endSetup();
