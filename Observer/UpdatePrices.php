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
 * Class UpdatePrices
 *
 * @package DCKAP\AdvancedSampleOrders\Observer
 */
class UpdatePrices implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;
    /**
     * @var \DCKAP\AdvancedSampleOrders\Helper\Config
     */
    private $moduleConfig;

    /**
     * UpdatePrices constructor.
     * @param \Magento\Framework\App\Request\Http $request
     * @param \DCKAP\AdvancedSampleOrders\Helper\Config $moduleConfig
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \DCKAP\AdvancedSampleOrders\Helper\Config $moduleConfig
    ) {
        $this->request = $request;
        $this->moduleConfig = $moduleConfig;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $item = $observer->getEvent()->getData('quote_item');
        if ($this->request->getParam("stype") == "sample") {
            $item->setSampleType("1");
            $options = $item->getOptionsByCode();

            $productType = $item->getProductType();
            if (isset($options["bundle_option_ids"])) {
                $productType = "bundle";
            }
            $finalPrice = $item->getProduct()->getFinalPrice()
                ? $item->getProduct()->getFinalPrice() : $item->getProduct()->getPrice();
            $customPrice = $this->moduleConfig->getSamplePrice(
                $this->request->getParam("product"),
                $productType,
                $finalPrice
            );
               $item->setCustomPrice($customPrice);
               $item->setOriginalCustomPrice($customPrice);
               $item->getProduct()->setIsSuperMode(true);
        }
    }
}
