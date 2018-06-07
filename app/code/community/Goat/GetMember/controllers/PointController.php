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


        $applyMemberPoint = (boolean)$this->getRequest()->getParam('apply_member_point');


        $this->_getSession()->setApplyMemberPoint($applyMemberPoint);

        $message = $this->__('Member Points was "%s".', ($applyMemberPoint)? 'applied': 'removed');


        if ($this->getRequest()->getParam('isAjax')) {
        	 $result['applied']  = $applyMemberPoint;
        	 $result['message']	 = $message;
        	 $this->_prepareDataJSON($result);
        	 return;
        }

        $this->_getSession()->addSuccess($message);
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