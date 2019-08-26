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
 * Class ToOrderMultipleItem
 *
 * @package DCKAP\AdvancedSampleOrders\Observer
 */
class ToOrderMultipleItem implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * Convert Sample Type from Quote to Order (Workaround)
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getData('order');

        /** @var \Magento\Quote\Model\Quote\Address $address */
        $address = $observer->getData('address');

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getData('quote');

        foreach ($order->getAllItems() as $item) {
            $quoteAddressItem = $address->getItemById($item->getQuoteItemId());
            $quoteItem = $quote->getItemById($quoteAddressItem->getQuoteItemId());
            $item->setData("sample_type", $quoteItem->getData('sample_type'));
        }
    }
}
