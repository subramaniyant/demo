<?php

namespace DCKAP\AdvancedSampleOrders\Model;

use DCKAP\AdvancedSampleOrders\Api\Data\AnalyticsInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Analytics
 * @package DCKAP\AdvancedSampleOrders\Model
 */
class Analytics extends AbstractModel implements AnalyticsInterface
{

    /**
     * @var \Magento\Sales\Model\Order\Item
     */
    private $orderItem;

    /**
     * Initialize with resource model
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Analytics::class);
    }

    /**
     * Analytics constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Sales\Model\Order\Item $orderItem
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Model\Order\Item $orderItem
    ) {
        $this->orderItem = $orderItem;
        parent::__construct($context, $registry);
    }

    /**
     * @return array
     */
    public function getStatuses()
    {
        $_availableStatuses = [];
        $_statuses = $this->orderItem->getStatuses();
        foreach ($_statuses as $_status) {
            if (!in_array($_status, ["Partial", "Mixed"])) {
                $_availableStatuses[] = $_status;
            }
        }
        return $_availableStatuses;
    }

    /**
     * @return mixed
     */
    public function getItemId()
    {
        return $this->_getData('item_id');
    }

    /**
     * @param $id
     */
    public function setItemId($id)
    {
        $this->setData('item_id', $id);
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->_getData('product_id');
    }

    /**
     * @param $id
     */
    public function setProductId($id)
    {
        $this->setData('product_id', $id);
    }

    /**
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->_getData('customer_id');
    }

    /**
     * @param $id
     */
    public function setCustomerId($id)
    {
        $this->setData('customer_id', $id);
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->_getData('email');
    }

    /**
     * @param $email
     */
    public function setEmail($email)
    {
        $this->setData('email', $email);
    }

    /**
     * @return mixed
     */
    public function getSampleOrderedDate()
    {
        return $this->_getData('sample_ordered_date');
    }

    /**
     * @param $orderDate
     */
    public function setSampleOrderedDate($orderDate)
    {
        $this->setData('sample_ordered_date', $orderDate);
    }

    /**
     * @return mixed
     */
    public function getOrderPlacedDate()
    {
        return $this->_getData('order_placed_date');
    }

    /**
     * @param $orderPlaced
     */
    public function setOrderPlacedDate($orderPlaced)
    {
        $this->setData('order_placed_date', $orderPlaced);
    }

    /**
     * @return mixed
     */
    public function getSamplePrice()
    {
        return $this->_getData('sample_price');
    }

    /**
     * @param $price
     */
    public function setSamplePrice($price)
    {
        $this->setData('sample_price', $price);
    }

    /**
     * @return mixed
     */
    public function getOrderPrice()
    {
        return $this->_getData('order_price');
    }

    /**
     * @param $price
     */
    public function setOrderPrice($price)
    {
        $this->setData('order_price', $price);
    }

    /**
     * @return mixed
     */
    public function getSampleOrderId()
    {
        return $this->_getData('sample_order_id');
    }

    /**
     * @param $orderId
     */
    public function setSampleOrderId($orderId)
    {
        $this->setData('sample_order_id', $orderId);
    }

    /**
     * @return mixed
     */
    public function getConvertedOrderId()
    {
        return $this->_getData('converted_order_id');
    }

    /**
     * @param $orderId
     */
    public function setConvertedOrderId($orderId)
    {
        $this->setData('converted_order_id', $orderId);
    }

    /**
     * @return mixed
     */
    public function getTotalCount()
    {
        return $this->_getData('total_count');
    }

    /**
     * @param $count
     */
    public function setTotalCount($count)
    {
        $this->setData('total_count', $count);
    }

    /**
     * @return mixed
     */
    public function getOrderStatus()
    {
        return $this->_getData('order_status');
    }

    /**
     * @param $status
     */
    public function setOrderStatus($status)
    {
        $this->setData('order_status', $status);
    }

    /**
     * @return mixed
     */
    public function getConverted()
    {
        return $this->_getData('converted');
    }

    /**
     * @param $converted
     */
    public function setConverted($converted)
    {
        $this->setData('converted', $converted);
    }
}
