<?php
/**
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\AdvancedSampleOrders\Block\Adminhtml;

use DCKAP\AdvancedSampleOrders\Model\Analytics as AnalyticsModel;

/**
 * Class History
 * @package DCKAP\AdvancedSampleOrders\Block\Adminhtml
 */
class History extends \Magento\Backend\Block\Template
{

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;
    /**
     * @var \Magento\Sales\Block\Adminhtml\Order\View\Info
     */
    private $orderInfo;
    /**
     * @var \DCKAP\AdvancedSampleOrders\Model\ResourceModel\Analytics\CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var AnalyticsModel
     */
    private $analyticsModel;

    /**
     * History constructor.
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Sales\Block\Adminhtml\Order\View\Info $orderInfo
     * @param AnalyticsModel $analyticsModel
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param \DCKAP\AdvancedSampleOrders\Model\ResourceModel\Analytics\CollectionFactory $collectionFactory
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Sales\Block\Adminhtml\Order\View\Info $orderInfo,
        AnalyticsModel $analyticsModel,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \DCKAP\AdvancedSampleOrders\Model\ResourceModel\Analytics\CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->registry = $registry;
        $this->analyticsModel = $analyticsModel;
        $this->orderInfo = $orderInfo;
        $this->collectionFactory = $collectionFactory;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Retrieve order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->registry->registry('current_order');
    }

    /**
     * @return mixed
     */
    public function isSample()
    {
        return $this->getOrder()->getSampleType();
    }

    /**
     * @return array
     */
    public function getCustomerDonut()
    {
        $_statuses = $this->analyticsModel->getStatuses();
        $is=0;
        $samplesdata = [];
        foreach ($_statuses as $status) {
            $isamples = $this->collectionFactory->create();
            $isamples->addFieldtoFilter("order_status", $status);
            $isamples->addFieldtoFilter("email", $this->getOrder()->getCustomerEmail());
            $samplesdata[$is]["value"] = $isamples->count() ? $isamples->count() : 0;
            $samplesdata[$is]["label"] = strtoupper($status);
            $is++;
        }
        return $samplesdata;
    }

    /**
     * @return array
     */
    public function getHistory()
    {
        $order = $this->getOrder();
        $orderItems = $order->getAllVisibleItems();
        $_products = [];
        $_history = [];
        $i = 1;

        /** @var \Magento\Sales\Model\Order\Item $item */
        foreach ($orderItems as $item) {
            $_products[] = $item->getProductId();
        }
        $isamples = $this->collectionFactory->create();
        $isamples->addFieldtoFilter("product_id", ['in' => $_products]);
        $isamples->addFieldtoFilter("email", $order->getCustomerEmail());
        $isamples->addFieldtoFilter("sample_order_id", ['to' => $order->getId()]);
        $isamples->addFieldtoFilter("sample_order_id", ['neq' => $order->getId()]);
        $isamples->getSelect()->limit(20);

        /** @var \DCKAP\AdvancedSampleOrders\Model\Analytics $_sample */
        foreach ($isamples as $sample) {
            $_history[$i]["classname"] = ($i % 2) ? "odd" : "even";
            $_history[$i]["orderurl"] = $this->orderInfo->getViewUrl($sample->getSampleOrderId());
            $_history[$i]["orderid"] = $this->orderRepository->get($sample->getSampleOrderId())->getIncrementId();
            $_history[$i]["productname"] = $this->productRepository->getById($sample->getProductId())->getName();
            $_history[$i]["ordered"] = $this->formatDate(
                $sample->getSampleOrderedDate(),
                \IntlDateFormatter::MEDIUM,
                false
            );
            $_history[$i]["status"] = $sample->getorderStatus();
            $i++;
        }
        return $_history;
    }
}
