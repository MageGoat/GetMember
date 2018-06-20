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

        $this->_getCheckout()->setCartCouponCode($couponCode);
        
         try {
            $codeLength = strlen($couponCode);  
            $isCodeLengthValid = $codeLength && $codeLength <= Mage_Checkout_Helper_Cart::COUPON_CODE_MAX_LENGTH;

            $this->_getCheckout()->getQuote()->getShippingAddress()->setCollectShippingRates(true);
            $this->_getCheckout()->getQuote()->setCouponCode($isCodeLengthValid ? $couponCode : '')
                ->collectTotals()
                ->save();

        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addError(Mage::helper('core')->__('Cannot apply the coupon code.'));
            Mage::logException($e);
        }
    }

    /**
     * record associate member order 
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function recordMemberOrder(Varien_Event_Observer $observer)
    {
        $order         = $observer->getOrder();

        if (empty($this->_getSession()->getMemberCode())) {
           return $this;
        }

        $pointModel  = Mage::getModel('getmember/point');
        $memberModel = Mage::getModel('getmember/member')->loadByMemberCode($this->_getSession()->getMemberCode());

        if (!$memberModel->getCustomerId()) {
           return $this;
        }

        if (intval($order->getGrandTotal()) <= 0) {
            return $this;
        }

        $pointModel->setOrderIncrementId($order->getIncrementId());
        $pointModel->setCustomerId($memberModel->getCustomerId());
        $pointModel->setMemberCode($this->_getSession()->getMemberCode());
        $pointModel->setState(Goat_GetMember_Model_Point::STATE_NEW);
        $pointModel->save();

        return $this;
    }
    /**
     * update status point to AVAILABLE
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function setMemberPointPaid(Varien_Event_Observer $observer)
    {
        $invoice = $observer->getInvoice();

        if ($invoice->getState() !== Mage_Sales_Model_Order_Invoice::STATE_PAID) {
            return $this;
        }

        if (!$orderIncrement = $invoice->getOrder()->getIncrementId()) {
           return $this;
        }

        $pointModel = Mage::getModel('getmember/point')->loadByOrderIncrementId($orderIncrement);

        if ($pointModel->getId() && $pointModel->getState() !== Goat_GetMember_Model_Point::STATE_USED) {
           $pointModel->setState(Goat_GetMember_Model_Point::STATE_AVAILABLE);
           $pointModel->setPoints(intval($pointModel->getPoints() + $invoice->getGrandTotal()));
           $pointModel->save();
        }

        return $this;
    }
}
