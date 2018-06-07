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
class Goat_GetMember_MemberController extends Mage_Core_Controller_Front_Action
{
    /**
     * Action predispatch
     *
     * Check customer authentication for some actions
     */
    public function preDispatch()
    {
        parent::preDispatch();
        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
        }
    }

    public function dashboardAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');

        $customer      = Mage::getSingleton('customer/session')->getCustomer();
        $memberModel   = Mage::getModel('getmember/member');

        $memberModel->loadByCustomerId($customer->getId());

        if ($block = $this->getLayout()->getBlock('getmember_dashboard')) {
            $block->setMember($memberModel);
            $block->setRefererUrl($this->_getRefererUrl());
        }
        $this->getLayout()->getBlock('head')->setTitle($this->__('Nember Get Member'));
        $this->renderLayout();
    }

    public function saveAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('customer/account/');
        }

        try {

            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $memberModel   = Mage::getModel('getmember/member');
            $sessionCustomer = Mage::getSingleton('customer/session');

            if (!(boolean)$this->getRequest()->getParam('member_code', false)) {

                $sessionCustomer->addError($this->__('The Member Code was necessary.'));
                $this->_redirect('getmember/member/dashboard');
                return;
            }

            $memberModel->loadByMemberCode($this->getRequest()->getParam('member_code', false));

            if ($memberModel->getCustomerId() && $customer->getId() !== $memberModel->getCustomerId()) {
                
                $sessionCustomer->addError($this->__('The Member Code was already exist.'));
                $this->_redirect('getmember/member/dashboard');
                return;
            }

            $memberModel->setMemberCode($this->getRequest()->getParam('member_code', false));
            $memberModel->setComment($this->getRequest()->getParam('comment', false));
            $memberModel->setIsActive(true);
            $memberModel->setCustomerId($customer->getId());
            
            $memberModel->save();

            $sessionCustomer->addSuccess($this->__('The Member Code has been saved.'));

        } catch (Exception $e) {
            Mage::logException($e);
            $sessionCustomer->addError($this->__('An error occurred while saving your record.'));
        }

        $this->_redirect('getmember/member/dashboard');
    }

}
