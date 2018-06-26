<?php
/**
 * Magento
 *
 *
 */


/**
 * Customers Member Get Member
 *
 * @package    Goat_GetMember
 */
class Goat_GetMember_PointController extends Mage_Core_Controller_Front_Action
{

	/**
     * Retrieve shopping cart model object
     *
     * @return Mage_Checkout_Model_Cart
     */
    protected function _getCart()
    {
        return Mage::getSingleton('checkout/cart');
    }

    /**
     * Get checkout session model instance
     *
     * @return Mage_Checkout_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Get current active quote instance
     *
     * @return Mage_Sales_Model_Quote
     */
    protected function _getQuote()
    {
        return $this->_getCart()->getQuote();
    }

	/**
     * Apply point shopping cart data action
     */
    public function applyPostAction()
    {
        if (!$this->_validateFormKey()) {
            $this->_redirect('*/*/');
            return;
        }
        $points = $this->getRequest()->getParam('points');
        $applyMemberPoint = (boolean)$this->getRequest()->getParam('apply_member_point');
        $customerSessionModel = Mage::getSingleton('customer/session');


        if (!$applyMemberPoint) {
            $this->_getSession()->setPoints('');
            $this->_getSession()->addSuccess( $this->__('Member Points was removed.'));
            $this->_redirectReferer();
            return;
        }

        if (!$points) {
            $this->_getSession()->addError($this->__('Please input same points!'));
            $this->_redirectReferer();
            return;
        }

        /** @var $_memberModel Goat_GetMember_Model_Member */
        $memberModel = Mage::getModel('getmember/member');
        $memberModel->loadByCustomerId($customerSessionModel->getCustomerId());

        $totalMemberPoints  = $memberModel->getAllPoints()->getTotalPoints();


        if ($totalMemberPoints < $points) {
            $this->_getSession()->addWarning($this->__('Maximum of points you can use is "%s".', $totalMemberPoints));
            $this->_redirectReferer();
            return;
        }

        $this->_getSession()->setPoints($points);
        $this->_getSession()->addSuccess($this->__('Was applied "%s" member points .', $points));
        $this->_redirectReferer();
    }

    /**
     * Prepare JSON formatted data for response to client
     *
     * @param $response
     * @return Zend_Controller_Response_Abstract
     */
    protected function _prepareDataJSON($response)
    {
        $this->getResponse()->setHeader('Content-type', 'application/json', true);
        return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }
}