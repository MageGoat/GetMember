<?php
/**
 * Magento
 *
 */

/**
 * Member Info block
 *
 */

class MageGoat_GetMember_Block_Member_Info extends Mage_Core_Block_Template
{
    /**
     * Member model
     *
     * @var MageGoat_GetMember_Model_Member
     */
    protected $_memberModel;

    /**
     * Whether the block should be eventually rendered
     *
     * @var bool
     */
    protected $_shouldRender = true;

    protected function _construct()
    {
        parent::_construct();

        /** @var $coreSessionModel Mage_Core_Model_Session */
        $coreSessionModel = Mage::getSingleton('core/session');

        if (!$memberCode = $coreSessionModel->getMemberCode()) {
            $this->_shouldRender = false;
            return;
        }

        $this->_memberModel = Mage::getModel('getmember/member')->loadByMemberCode($memberCode);

        if (!$this->_memberModel->getId()) {
            $this->_shouldRender = false;
            return;
        }
    }

    /**
     * Get MageGoat_GetMember_Model_Member object
     *
     * @return MageGoat_GetMember_Model_Member
     */
    public function getMember()
    {
        return $this->_memberModel;
    }

    public function getShareUrl($route = '')
    {
       return $this->getUrl($route, array('_query' => array('member_code' => $this->_memberModel->getMemberCode())));
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
