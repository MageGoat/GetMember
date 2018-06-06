<?php
/**
 * Magento
 *
 */
class MageGoat_GetMember_Model_Member extends MageGoat_GetMember_Model_Abstract
{

    protected $_eventPrefix = 'member';

    protected $_pointCollection   = null;

	/**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('getmember/member');
    }

    /**
     * Load member by system code identifier
     *
     * @param string $memberCode
     * @return MageGoat_GetMember_Model_Member
     */
    public function loadByMemberCode($memberCode)
    {
        return $this->loadByAttribute('member_code', $memberCode);
    }

    /**
     * Load member by customer id identifier
     *
     * @param string $customer_id
     * @return MageGoat_GetMember_Model_Member
     */
    public function loadByCustomerId($customer_id)
    {
        return $this->loadByAttribute('customer_id', $customer_id);
    }

    /**
     * Get Mage_Customer_Model_Customer object
     *
     * @return Mage_Customer_Model_Customer
     */
    public function getCustomer()
    {   
        $customerModel = Mage::getModel('customer/customer');
        $customerModel->load($this->getCustomerId());
        
        return $customerModel;
    }

    /**
     * get All Point of Member
     *
     * @return MageGoat_GetMember_Model_Resource_Point_Collection
     */
    public function getAllPoints()
    {
        if (is_null($this->_pointCollection)) {
            $this->_pointCollection = Mage::getResourceModel('getmember/point_collection')
                ->addCustomerFilter($this->getCustomerId())
                ->setOrder('created_at', 'desc');
        }
        return $this->_pointCollection;
    }
}