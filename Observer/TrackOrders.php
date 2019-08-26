<?php
/**
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\AdvancedSampleOrders\Observer;

use Magento\Framework\Event\Observer;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item as OrderItem;
use DCKAP\AdvancedSampleOrders\Model\AnalyticsFactory as AnalyticsModel;
use DCKAP\AdvancedSampleOrders\Model\ResourceModel\Analytics as AnalyticsResourceModel;
use DCKAP\AdvancedSampleOrders\Model\ResourceModel\Analytics\CollectionFactory as AnalyticsCollection;

/**
 * Class TrackOrders
 *
 * @package DCKAP\AdvancedSampleOrders\Observer
 */
class TrackOrders implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var AnalyticsModel
     */
    private $analyticsModel;

    /**
     * @var AnalyticsResourceModel
     */
    private $resourceModel;

    /**
     * @var AnalyticsCollection
     */
    private $analyticsCollection;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $dateTime;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $timeZone;

    /**
     * TrackOrders constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timeZone
     * @param AnalyticsModel $analyticsModel
     * @param AnalyticsResourceModel $resourceModel
     * @param AnalyticsCollection $analyticsCollection
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timeZone,
        AnalyticsModel $analyticsModel,
        AnalyticsResourceModel $resourceModel,
        AnalyticsCollection $analyticsCollection
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->analyticsModel = $analyticsModel;
        $this->resourceModel = $resourceModel;
        $this->analyticsCollection = $analyticsCollection;
        $this->timeZone = $timeZone;
        $this->dateTime = $dateTime;
    }

    /**
     * Update Sample Order Information
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var Order $order */
        $order = $observer->getEvent()->getOrder();

        if (!$order) {
            $allOrders = $observer->getOrders();
            foreach ($allOrders as $shipOrder) {
                $this->trackOrder($shipOrder);
            }
        } else {
            $this->trackOrder($order);
        }
    }

    /**
     * @param mixed $order
     */
    private function trackOrder($order)
    {
        /** @var OrderItem $item */
        foreach ($order->getAllVisibleItems() as $item) {
            if ($item->getSampleType()) {
                $this->addSampleItem($order, $item);
            } else {
                $this->updateSampleItem($order, $item);
            }
        }
    }

    /**
     * @param Order $order
     * @param OrderItem $item
     */
    private function addSampleItem(Order $order, OrderItem $item)
    {
        /** @var \DCKAP\AdvancedSampleOrders\Model\Analytics $_sample */
        $_sample = $this->analyticsModel->create();
        $_sample->setProductId($item->getProductId());
        $_sample->setCustomerId($this->formatCustomerId($order->getCustomerId()));
        $_sample->setEmail($order->getCustomerEmail());
        $_sample->setSampleOrderedDate($this->getCurrentDate());
        $_sample->setSamplePrice($item->getPrice());
        $_sample->setOrderStatus("Ordered");
        $_sample->setSampleOrderId($order->getId());
        $this->resourceModel->save($_sample);
    }

    /**
     * @param Order $order
     * @param OrderItem $item
     */
    private function updateSampleItem(Order $order, OrderItem $item)
    {
        $_statuses = explode(",", $this->scopeConfig->getValue('advancedsampleorders/analytics/status'));
        $_pending = ["Ordered", "Backordered"];
        foreach ($_statuses as $status) {
            if (in_array($status, $_pending)) {
                /** @var \DCKAP\AdvancedSampleOrders\Model\ResourceModel\Analytics\Collection $_samples */
                $_samples = $this->analyticsCollection->create();
                $_samples->addFieldtoFilter("product_id", $item->getProductId());
                $_samples->addFieldtoFilter("email", $order->getCustomerEmail());
                $_samples->addFieldtoFilter("order_status", $status);
                $_samples->addFieldtoFilter("sample_ordered_date", ['from' => $this->getFromDate()]);

                /** @var \DCKAP\AdvancedSampleOrders\Model\Analytics $oldSample */
                $oldSample = $_samples->getlastItem();
                if ($oldSample->getItemId()) {
                    $_totalCount = $oldSample->getTotalCount() ? (int)$oldSample->getTotalCount() + 1 : 1;
                    $_convertedIds = $oldSample->getConvertedOrderId()
                        ? $oldSample->getConvertedOrderId() . "," . $order->getId() : $order->getId();
                    $_itemPrice = $item->getPrice() * $item->getQtyOrdered();
                    $_convertedPrices = $oldSample->getOrderPrice()
                        ? (int)$oldSample->getOrderPrice() + $_itemPrice : $_itemPrice;

                    /** @var \DCKAP\AdvancedSampleOrders\Model\Analytics $_sample */
                    $_sample = $this->analyticsModel->create();
                    $this->resourceModel->load($_sample, $oldSample->getItemId());
                    $_sample->setOrderPlacedDate($this->getCurrentDate());
                    $_sample->setOrderPrice($_convertedPrices);
                    $_sample->setConvertedOrderId($_convertedIds);
                    $_sample->setTotalCount($_totalCount);
                    $_sample->setConverted(1);
                    $this->resourceModel->save($_sample);
                }
            }
        }
    }

    /**
     * @return string
     */
    private function getFromDate()
    {
        $date = new \Zend_Date($this->getCurrentDate());
        $date->subDay($this->getDays());
        return $date->toString('Y-M-d H:m:s');
    }

    /**
     * @return int|mixed
     */
    private function getDays()
    {
        $days = $this->scopeConfig->getValue("advancedsampleorders/analytics/days");
        return $days ? $days : 30;
    }

    /**
     * @return string
     */
    private function getCurrentDate()
    {
        return $this->dateTime->date('Y-m-d H:i:s', $this->timeZone->formatDate());
    }

    /**
     * @param $customerId
     * @return int
     */
    private function formatCustomerId($customerId)
    {
        return $customerId ? $customerId : 0;
    }
}
