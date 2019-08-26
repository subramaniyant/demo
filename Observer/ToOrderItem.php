<?php
/**
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\AdvancedSampleOrders\Observer;

use Magento\Framework\Event\Observer;

/**
 * Class ToOrderItem
 *
 * @package DCKAP\AdvancedSampleOrders\Observer
 */
class ToOrderItem implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * Convert Sample Type from Quote to Order (Workaround)
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getData('quote');

        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getData('order');
       
        foreach ($order->getAllItems() as $item) {
            $quoteItem = $quote->getItemById($item->getQuoteItemId());
            $item->setData("sample_type", $quoteItem->getData("sample_type"));
        }
    }
}
