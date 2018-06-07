<?php
/**
 * Magento
 *
 */

/**
 * Order invoice point total calculation model
 */
class Goat_GetMember_Model_Sales_Order_Invoice_Total_Point extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $invoice->setPointAmount(0);
        $invoice->setBasePointAmount(0);
        $qtyTotal = 0;
        $qtyToInvoice = 0;

        $previusInvoicePointAmount = 0;
        $previusInvoiceBasePointAmount = 0;
        foreach ($invoice->getOrder()->getInvoiceCollection() as $previusInvoice) {
            $previusInvoicePointAmount += $previusInvoice->getPointAmount();
            $previusInvoiceBasePointAmount += $previusInvoice->getBasePointAmount();
        }

        /** @var $item Mage_Sales_Model_Order_Invoice_Item */
        foreach ($invoice->getAllItems() as $item) {
            $orderItem = $item->getOrderItem();
            if ($orderItem->isDummy()) {
                 continue;
            }
            $qtyTotal  += $orderItem->getQtyOrdered();
            $qtyToInvoice += $item->getQty();
        }

        $invoicePointAmount = $invoice->roundPrice((($invoice->getOrder()->getPointAmount() - $previusInvoicePointAmount) / $qtyTotal) * $qtyToInvoice, 'regular', true);
        $baseInvoicePointAmount = $invoice->roundPrice((($invoice->getOrder()->getPointAmount() - $previusInvoiceBasePointAmount) / $qtyTotal) * $qtyToInvoice, 'base', true);  

        $invoice->setPointAmount($invoicePointAmount);
        $invoice->setBasePointAmount($baseInvoicePointAmount);

        $invoice->setGrandTotal($invoice->getGrandTotal()+$invoicePointAmount);
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal()+$baseInvoicePointAmount);
        
        return $this;
    }
}
