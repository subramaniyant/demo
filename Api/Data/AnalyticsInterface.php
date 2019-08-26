<?php
/**
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\AdvancedSampleOrders\Api\Data;

/**
 * Interface for managing sample order analytics
 * @package DCKAP\AdvancedSampleOrders\Api\Data
 */
interface AnalyticsInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    /**
     * @return mixed
     */
    public function getItemId();

    /**
     * @param $id
     * @return mixed
     */
    public function setItemId($id);

    /**
     * @return mixed
     */
    public function getProductId();

    /**
     * @param $id
     * @return mixed
     */
    public function setProductId($id);

    /**
     * @return mixed
     */
    public function getCustomerId();

    /**
     * @param $id
     * @return mixed
     */
    public function setCustomerId($id);

    /**
     * @return mixed
     */
    public function getEmail();

    /**
     * @param $email
     * @return mixed
     */
    public function setEmail($email);

    /**
     * @return mixed
     */
    public function getSampleOrderedDate();

    /**
     * @param $orderDate
     * @return mixed
     */
    public function setSampleOrderedDate($orderDate);

    /**
     * @return mixed
     */
    public function getOrderPlacedDate();

    /**
     * @param $orderPlaced
     * @return mixed
     */
    public function setOrderPlacedDate($orderPlaced);

    /**
     * @return mixed
     */
    public function getSamplePrice();

    /**
     * @param $price
     * @return mixed
     */
    public function setSamplePrice($price);

    /**
     * @return mixed
     */
    public function getOrderPrice();

    /**
     * @param $price
     * @return mixed
     */
    public function setOrderPrice($price);

    /**
     * @return mixed
     */
    public function getSampleOrderId();

    /**
     * @param $orderId
     * @return mixed
     */
    public function setSampleOrderId($orderId);

    /**
     * @return mixed
     */
    public function getConvertedOrderId();

    /**
     * @param $orderId
     * @return mixed
     */
    public function setConvertedOrderId($orderId);

    /**
     * @return mixed
     */
    public function getTotalCount();

    /**
     * @param $count
     * @return mixed
     */
    public function setTotalCount($count);

    /**
     * @return mixed
     */
    public function getOrderStatus();

    /**
     * @param $status
     * @return mixed
     */
    public function setOrderStatus($status);

    /**
     * @return mixed
     */
    public function getConverted();

    /**
     * @param $converted
     * @return mixed
     */
    public function setConverted($converted);
}
