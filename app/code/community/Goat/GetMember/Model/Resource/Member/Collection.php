<?php
/**
 * Magento
 *
 */
class Goat_GetMember_Model_Resource_Member_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Initialize collection
     *
     */
    public function _construct()
    {
        $this->_init('getmember/member');
    }
}