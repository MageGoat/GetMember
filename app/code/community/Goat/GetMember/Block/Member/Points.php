<?php
/**
 * Magento
 *
 */

/**
 * Member Info block
 *
 */

class Goat_GetMember_Block_Member_Points extends Mage_Checkout_Block_Cart_Abstract
{
    /**
     * Product Collection
     *
     * @var Goat_GetMember_Model_Resource_Point_Collection
     */
    protected $_pointCollection;

    /**
     * Whether the block should be eventually rendered
     *
     * @var bool
     */
    protected $_shouldRender = true;

    protected function _construct()
    {
        parent::_construct();

        /** @var $customerSessionModel Mage_Customer_Model_Session */
        $customerSessionModel = Mage::getSingleton('customer/session');

        if (!$customerSessionModel->getCustomerId()) {
            $this->_shouldRender = false;
            return;
        }
    }

    /**
     * Retrieve loaded point collection
     *
     * @return Goat_GetMember_Model_Resource_Point_Collection
     */
    protected function _getPointCollection()
    {
        if (is_null($this->_pointCollection)) {

            /** @var $memberModel Goat_GetMember_Model_Member */
            $memberModel = Mage::getModel('getmember/member');

            /** @var $customerSessionModel Mage_Customer_Model_Session */
            $customerSessionModel = Mage::getSingleton('customer/session');

            $memberModel->loadByCustomerId($customerSessionModel->getCustomerId());

            $this->_pointCollection = $memberModel->getAllPoints();
        }
        return $this->_pointCollection;
    }

    /**
     * Retrieve loaded point collection
     *
     * @return Goat_GetMember_Model_Resource_Point_Collection
     */
    public function getLoadedPointCollection()
    {
        return $this->_getPointCollection();
    }

    /**
     * Return "cart" form action url
     *
     * @return string
     */
    public function getFormActionUrl()
    {
        return $this->getUrl('getmember/point/applyPost', array('_secure' => $this->_isSecure()));
    }

    /**
     * Retrieve total point
     *
     * @return $_total
     */
    public function getTotal()
    {
        return $this->_getPointCollection()->getTotalPoints();
    }

    public function isApplyMemberPoint()
    {
        return $this->getCheckout()->getApplyMemberPoint();
    }

    /**
     * Render the block if needed
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->_shouldRender) {
            return '';
        }
        
        return parent::_toHtml();
    }
}
