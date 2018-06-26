<?php
/**
 * Magento
 *
 */

/** @var $installer Mage_Core_Model_Resource_Setup */
$installer =  new Mage_Sales_Model_Resource_Setup('core_setup');

$installer->addAttribute('quote_address', 'point_amount', array('type'=>'decimal'));
$installer->addAttribute('quote_address', 'base_point_amount', array('type'=>'decimal'));

$installer->addAttribute('order', 'point_amount', array('type'=>'decimal'));
$installer->addAttribute('order', 'base_point_amount', array('type'=>'decimal'));

$installer->addAttribute('invoice', 'point_amount', array('type'=>'decimal'));
$installer->addAttribute('invoice', 'base_point_amount', array('type'=>'decimal'));

$installer->addAttribute('creditmemo', 'point_amount', array('type'=>'decimal'));
$installer->addAttribute('creditmemo', 'base_point_amount', array('type'=>'decimal'));