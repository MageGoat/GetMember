<?php


class Goat_GetMember_Model_Sales_Quote_Address_Total_Point extends Mage_Sales_Model_Quote_Address_Total_Abstract
{

    protected $_code = 'point';
 
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);
 
        $this->_setAmount(0);
        $this->_setBaseAmount(0);
 
        $items = $this->_getAddressItems($address);
        if (!count($items)) {
            return $this; //this makes only address type shipping to come through
        }
        

        $quote = $address->getQuote();
        $customerSessionModel = Mage::getSingleton('customer/session');
        $memberSessionPoints = $this->_getCheckout()->getPoints();

        if(!$memberSessionPoints) { //your business logic
            return;
        }

        $cartAmount = $address->getSubtotal() + $address->getDiscountAmount();

        if ($cartAmount <= 0 ) {
            $this->_getCheckout()->setPoints('');
            $this->_getCheckout()->addError(Mage::helper('getmember')->__('Discount of "%s". You can not use points! ', $address->getDiscountAmount()));
            return;
        }

        /** @var $_memberModel Goat_GetMember_Model_Member */
        $memberModel = Mage::getModel('getmember/member');
        $memberModel->loadByCustomerId($customerSessionModel->getCustomerId());

        $_totalMemberPoint  = $memberModel->getAllPoints()->getTotalPoints();

        if ($memberSessionPoints > $_totalMemberPoint) {
            $memberSessionPoints = $_totalMemberPoint;
        }

        if ($memberSessionPoints > $cartAmount) {
            $memberSessionPoints = ($cartAmount - 1);

            $this->_getCheckout()->addError(Mage::helper('getmember')->__('Sorry, but you can use a maximum of "%s" points!', $memberSessionPoints));
        }

        $this->_getCheckout()->setPoints($memberSessionPoints);

        $address->setPointAmount(-$memberSessionPoints);
        $address->setBasePointAmount(-$memberSessionPoints);
        
    
        $address->setGrandTotal($address->getGrandTotal() + $address->getPointAmount());
        $address->setBaseGrandTotal($address->getBaseGrandTotal() + $address->getBasePointAmount());
    }
 
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $amount = $address->getPointAmount();
        if ($amount!=0) {
            $title = Mage::helper('getmember')->__('Points');

            $address->addTotal(array(
                    'code' => $this->getCode(),
                    'title'=> $title,
                    'value'=> $address->getPointAmount()
            ));

        }
        return $this;
    }

    /**
     * Get checkout session
     *
     * @return Mage_Checkout_Model_Session
     */
    protected function _getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }
}