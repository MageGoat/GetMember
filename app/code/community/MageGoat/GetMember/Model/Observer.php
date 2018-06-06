<?php
/**
 * Magento
 *
 */
class MageGoat_GetMember_Model_Observer
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
        $this->_getSession()->setMemberCode($parmMemberCode);

        return $this;
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
        $pointModel->setState(MageGoat_GetMember_Model_Point::STATE_NEW);
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

        if ($pointModel->getId() && $pointModel->getState() !== MageGoat_GetMember_Model_Point::STATE_USED) {
           $pointModel->setState(MageGoat_GetMember_Model_Point::STATE_AVAILABLE);
           $pointModel->setPoints(intval($pointModel->getPoints() + $invoice->getGrandTotal()));
           $pointModel->save();
        }

        return $this;
    }
}
