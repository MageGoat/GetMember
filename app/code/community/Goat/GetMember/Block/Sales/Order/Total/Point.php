<?php
/**
 * Magento
 *
 */

 /**
  * Totals point block
  */
class Goat_GetMember_Block_Sales_Order_Total_Point extends Mage_Sales_Block_Order_Totals
{
    /**
     * Initialize totals object
     *
     * @return Mage_Sales_Block_Order_Totals
     */
    public function initTotals()
    {
        if (((float)$this->getSource()->getPointAmount()) == 0) {
            return $this;
        }
        
        $total = new Varien_Object(array(
            'code'      => 'point_amount',
            #'block_name'=> 'point',
            'area'      => $this->getDisplayArea(),
            'strong'    => false,
            'value' => $this->getSource()->getPointAmount(),
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
