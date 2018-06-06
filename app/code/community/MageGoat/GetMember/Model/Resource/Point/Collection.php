<?php
/**
 * Magento
 *
 */
class MageGoat_GetMember_Model_Resource_Point_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Initialize collection
     *
     */
    public function _construct()
    {
        $this->_init('getmember/point');
    }

    /**
     * Add filter by specified recurring profile id(s)
     *
     * @param array|int $ids
     * @return Mage_Sales_Model_Resource_Order_Collection
     */
    public function addCustomerFilter($customer_id)
    {
        $this->addFieldToFilter('customer_id', $customer_id);         
        return $this;
    }

    /**
     * Retrieve total point
     *
     * @return $_total
     */
    public function getTotalPoints()
    {
        $_total = 0;
         foreach ($this->getData() as $item) {
             $_total += $item['points'];
        }
        return $_total;
    }
}