<?php
/**
 * Magento
 *
 */
class Goat_GetMember_Model_Point extends Goat_GetMember_Model_Abstract
{

	const STATE_EARNED     = 'earned';
    const STATE_USED       = 'used';
    const STATE_WAITING    = 'waiting';

    protected $_eventPrefix = 'point';

    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('getmember/point');
    }


    /**
     * Load point by order_increment_id identifier
     *
     * @param string $order_increment_id
     * @return Goat_GetMember_Model_Point
     */
    public function loadByOrderIncrementId($order_increment_id)
    {
        return $this->loadByAttribute('order_increment_id', $order_increment_id);
    }
}