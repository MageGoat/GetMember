<?php
/**
 * Magento
 *
 */
class Goat_GetMember_Model_Observer
{

    /**
     * Get checkout session model instance
     *
     * @return Mage_Core_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('core/session');
    }

    /**
     * Get checkout session model instance
     *
     * @return Mage_Checkout_Model_Session
     */
    protected function _getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     *
     * check a valid memberCode
     */
    protected function _isValidMemberCode($code)
    {
        $memberModel = Mage::getModel('getmember/member')->loadByMemberCode($code);

        if (!$memberModel->getId()) {
           return false;
        }

        return true;
    }

    /**
     * set member code on session for use after
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function setMemberCodeInSession(Varien_Event_Observer $observer)
    {
        $action         = $observer->getControllerAction();
        $parmMemberCode = (string) $action->getRequest()->getParam('mCode');

        if (empty($parmMemberCode)) {
           return $this;
        }

        if (!$this->_isValidMemberCode($parmMemberCode)) {
            return $this;
        }

        $this->_getSession()->setMemberCode($parmMemberCode);

        return $this;
    }

    /**
     *
     * apply Coupon on cart associte to members
     */
    public function applyCouponMember(Varien_Event_Observer $observer)
    {
        $mCode = $this->_getSession()->getMemberCode();

        if (empty($mCode) || !$this->_isValidMemberCode($mCode)) {
           return $this;
        }

        $couponCode = Mage::getStoreConfig('getmember/configuration/coupon_member');

        #$this->_getCheckout()->setCartCouponCode($couponCode); //hard coupon
        $this->_getCheckout()->getQuote()->setCouponCode($couponCode);
        return;
    }

    /**
     * record points associate member 
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function recordPointsToMember(Varien_Event_Observer $observer)
    {
        $order      = $observer->getOrder();
        $memberCode = $this->_getSession()->getMemberCode();

        if (empty($memberCode)) {
           return $this;
        }

        $pointModel  = Mage::getModel('getmember/point');
        $memberModel = Mage::getModel('getmember/member');

        $memberModel->loadByMemberCode($memberCode);

        if (!$memberModel->getCustomerId()) {
           return $this;
        }

        if (intval($order->getGrandTotal()) <= 0) {
            return $this;
        }

        $pointModel->setOrderIncrementId($order->getIncrementId());
        $pointModel->setCustomerId($memberModel->getCustomerId()); //Member Customer Id
        $pointModel->setState(Goat_GetMember_Model_Point::STATE_WAITING);
        $pointModel->save();

        return $this;
    }

    /**
     * record used point associate member customer 
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function recordUsedPointsToMember(Varien_Event_Observer $observer)
    {
        $order      = $observer->getOrder();

        if (!$orderIncrement = $order->getIncrementId()) {
           return $this;
        }

        if (intval($order->getPointAmount()) >= 0) {
            return $this;
        }

        $pointUsedModel  = Mage::getModel('getmember/point');

        $pointUsedModel->loadByOrderIncrementId($orderIncrement);

        //already record
        if ($pointUsedModel->getId()) {
            return $this;
        }

        $pointUsedModel->setOrderIncrementId($order->getIncrementId());
        $pointUsedModel->setCustomerId($order->getCustomerId()); //Member Customer Id
        $pointUsedModel->setState(Goat_GetMember_Model_Point::STATE_USED);
        $pointUsedModel->setPoints(intval($order->getPointAmount()));
        $pointUsedModel->save();

        $this->_getCheckout()->setPoints('');
        return $this;
    }

    /**
     * update point STATE_WAITING to STATE_EARNED
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function earnPointPaid(Varien_Event_Observer $observer)
    {
        $invoice    = $observer->getInvoice();
        $pointModel = Mage::getModel('getmember/point');


        if ($invoice->getState() !== Mage_Sales_Model_Order_Invoice::STATE_PAID) {
            return $this;
        }

        if (!$orderIncrement = $invoice->getOrder()->getIncrementId()) {
           return $this;
        }

        $pointModel->loadByOrderIncrementId($orderIncrement);

        if (!$pointModel->getId()){
            return $this;
        }

        if ($pointModel->getState() == Goat_GetMember_Model_Point::STATE_WAITING) {
            $pointModel->setState(Goat_GetMember_Model_Point::STATE_EARNED);
            $pointModel->setPoints(intval($pointModel->getPoints() + $invoice->getGrandTotal()));
            $pointModel->save();
        }

        return $this;
    }
}
