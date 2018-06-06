<?php
/**
 * Magento
 *
 */
class MageGoat_GetMember_Model_Point extends MageGoat_GetMember_Model_Abstract
{

	const STATE_NEW  	   = 'new';
    const STATE_USED       = 'used';
	const STATE_AVAILABLE  = 'available';

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
     * @return MageGoat_GetMember_Model_Point
     */
    public function loadByOrderIncrementId($order_increment_id)
    {
        return $this->loadByAttribute('order_increment_id', $order_increment_id);
    }
}