<?php
/**
 * Magento
 *
 */

 /**
  * Totals point block
  */
class MageGoat_GetMember_Block_Adminhtml_Sales_Order_Totals_Point extends Mage_Sales_Block_Order_Totals
{
     /**
     * Determine display parameters before rendering HTML
     *
     * @return Mage_Adminhtml_Block_Sales_Order_Totals_Item
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();

        $this->setCanDisplayTotalPaid($this->getParentBlock()->getCanDisplayTotalPaid());
        $this->setCanDisplayTotalRefunded($this->getParentBlock()->getCanDisplayTotalRefunded());
        $this->setCanDisplayTotalDue($this->getParentBlock()->getCanDisplayTotalDue());

        return $this;
    }

    /**
     * Initialize totals object
     *
     * @return Mage_Adminhtml_Block_Sales_Order_Totals_Item
     */
    public function initTotals()
    {
        $total = new Varien_Object(array(
            'code'      => 'point_amount',
            'area'      => $this->getDisplayArea(),
            'strong'    => false,
            'value' => $this->getSource()->getPointAmount(),
            'base_value'=> $this->getSource()->getBasePointAmount(),
            'label' => $this->__('Point Amount')
        ));

        if ($this->getBeforeCondition()) {
            $this->getParentBlock()->addTotalBefore($total, $this->getBeforeCondition());
        } else {
            $this->getParentBlock()->addTotal($total, $this->getAfterCondition());
        }
        return $this;
    }

    /**
     * Price HTML getter
     *
     * @param float $baseAmount
     * @param float $amount
     * @return string
     */
    public function displayPrices($baseAmount, $amount)
    {
        return $this->helper('adminhtml/sales')->displayPrices($this->getOrder(), $baseAmount, $amount);
    }

    /**
     * Price attribute HTML getter
     *
     * @param string $code
     * @param bool $strong
     * @param string $separator
     * @return string
     */
    public function displayPriceAttribute($code, $strong = false, $separator = '<br/>')
    {
        return $this->helper('adminhtml/sales')->displayPriceAttribute($this->getSource(), $code, $strong, $separator);
    }

    /**
     * Source order getter
     *
     * @return Mage_Sales_Model_Order
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }
}
