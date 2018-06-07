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

        if($this->_getCheckout()->getApplyMemberPoint()) { //your business logic
            
            /** @var $_memberModel Goat_GetMember_Model_Member */
            $_memberModel = Mage::getModel('getmember/member');
            $_memberModel->loadByCustomerId($customerSessionModel->getCustomerId());

            $_totalPointBalance  = $_memberModel->getAllPoints()->getTotalPoints();

            $address->setPointAmount(-$_totalPointBalance);
            $address->setBasePointAmount(-$_totalPointBalance);
                 
            #$quote->setPointAmount(-$_totalPointBalance);
 
            $address->setGrandTotal($address->getGrandTotal() + $address->getPointAmount());
            $address->setBaseGrandTotal($address->getBaseGrandTotal() + $address->getBasePointAmount());
        }
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