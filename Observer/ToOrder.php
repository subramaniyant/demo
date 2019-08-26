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
 * Class ToOrder
 *
 * @package DCKAP\AdvancedSampleOrders\Observer
 */
class ToOrder implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * Mark Order as sampled if it contains sample item
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();

        if ($this->hasSampleItem($order)) {
            $order->setSampleType(1);
        }
    }

    /**
     * @param $order
     * @return int
     */
    protected function hasSampleItem($order)
    {
        /** @int $isSample */
        $isSample = 0;

        foreach ($order->getAllItems() as $item) {
            $isSample = $item->getSampleType() ? 1 : $isSample;
            $productName = $item->getName();
            if (!$item->getParentItem() && strpos($productName, '( SAMPLE )') === false
                && $item->getSampleType()) {
                $item->setName($productName." ( SAMPLE )");
            }
        }
        return $isSample;
    }
}
