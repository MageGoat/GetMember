<?php
/**
 * Magento
 *
 */

/**
 * Order creditmemo point total calculation model
 */
class Goat_GetMember_Model_Sales_Order_Creditmemo_Total_Point extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $creditmemo->setPointAmount(0);
        $creditmemo->setBasePointAmount(0);

        $order = $creditmemo->getOrder();

        $totalPointAmount =  $order->getPointAmount();
        $baseTotalPointAmount = $order->getBasePointAmount();

        
        $creditmemo->setPointAmount($totalPointAmount);
        $creditmemo->setBasePointAmount($baseTotalPointAmount);

        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $totalPointAmount);
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseTotalPointAmount);
        return $this;
    }
}
