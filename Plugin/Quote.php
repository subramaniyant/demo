<?php
/**
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\AdvancedSampleOrders\Plugin;

/**
 * Class Quote
 * @package DCKAP\AdvancedSampleOrders\Plugin
 */
class Quote
{

    /**
     * @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable
     */
    private $_configurableType;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $_checkoutSession;
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $_productRepository;
    /**
     * @var \DCKAP\AdvancedSampleOrders\Helper\Config
     */
    private $_helper;
    private $_isSample;
    private $orderedQty;

    /**
     * Quote constructor.
     * @param \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableType
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableType,
        \Magento\Checkout\Model\Session $checkoutSession,
        \DCKAP\AdvancedSampleOrders\Helper\Config $helper,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    ) {
        $this->_configurableType = $configurableType;
        $this->_checkoutSession = $checkoutSession;
        $this->_productRepository = $productRepository;
        $this->_helper = $helper;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param $product
     * @param null $mst_request
     * @param string $processMode
     */
    public function beforeAddProduct(
        \Magento\Quote\Model\Quote $quote,
        $product,
        $mst_request = null,
        $processMode = \Magento\Catalog\Model\Product\Type\AbstractType::PROCESS_MODE_FULL
    ) {
        $this->prepareProductForSample($mst_request);
    }

    /**
     * @param $mst_request
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function prepareProductForSample($mst_request)
    {
        $this->_isSample = 0;
        $sampleLimit = $this->_helper->getStoreConfigValue('advancedsampleorders/general/limit');

        $_isBundle = $this->getProdutType($mst_request);

        $_subProducts = $mst_request->getSuperGroup();
        $mst_request = $this->updateSampleQuantity($mst_request, $_subProducts, $_isBundle);

        $_quoteItems = $this->_checkoutSession->getQuote()->getAllVisibleItems();
        $_sampleCount = $this->orderedQty;
        if ($this->_isSample && $_sampleCount > $sampleLimit && $sampleLimit) {
            throw new \Magento\Framework\Exception\LocalizedException(__("You are not allowed to add more than ".$sampleLimit." sample(s)"));
        }

        foreach ($_quoteItems as $_quoteItem) {

            if ($_quoteItem->getSampleType()) {
                $_sampleCount++;
            }

            if ($this->_isSample && $_sampleCount > $sampleLimit && $sampleLimit) {
                throw new \Magento\Framework\Exception\LocalizedException(__("You are not allowed to add more than ".$sampleLimit." sample(s)"));
            }
            if (isset($_subProducts)) {
                $suProductsCount = count($_subProducts);
            } else {
                $suProductsCount = 0;
            }
            $_subProducts = $suProductsCount ? $_subProducts : [$mst_request->getProduct() => "1"];
            $_subProducts[$mst_request->getProduct()] = "1";

            $_throwError = $this->validateProductOptions($mst_request, $_quoteItem, $_subProducts, $_isBundle);

            if ($_throwError == 1) {
                throw new \Magento\Framework\Exception\LocalizedException(__('You have this product sample already in cart.'));
            } elseif ($_throwError == 2) {
                throw new \Magento\Framework\Exception\LocalizedException(__("You can't add this product since you have sample of this product already in cart. Please remove the sample from cart and then add product."));
            } elseif ($_throwError == 3) {
                throw new \Magento\Framework\Exception\LocalizedException(__("You can't add sample since you have this product already in cart. Please remove the product from cart and then add sample."));
            }
        }
    }

    private function validateProductOptions($mst_request, $_quoteItem, $_subProducts, $_isBundle)
    {
        $_throwError = 0;
        $_curProductId = $_quoteItem->getProductId();
        if ($_isBundle) {
            if ($_quoteItem->getProductType() == 'bundle') { //Bundle Product
                $options = $_quoteItem->getProduct()->getTypeInstance(true)->getOrderOptions($_quoteItem->getProduct());
                $bundle_options = $options["info_buyRequest"]["bundle_option"];
                if (empty(array_diff($mst_request->getBundleOption(), $bundle_options))) {
                    $_throwError = $this->checkSampleTypeError($_quoteItem, $this->_isSample);
                }
            }
        } elseif (($mst_request->getSuperAttribute() instanceof Countable) && !empty($mst_request->getSuperAttribute())) {    //Configurable Product
            if ($_quoteItem->getProductType() == 'configurable') {
                $_reqProduct = $this->_productRepository->getById($mst_request->getProduct());
                $childProduct = $this->_configurableType
                                ->getProductByAttributes($mst_request->getSuperAttribute(), $_reqProduct);

                if ($childProduct->getSku() == $_quoteItem->getSku()) {
                    $_throwError = $this->checkSampleTypeError($_quoteItem, $this->_isSample);
                }
            }
        } else {
            foreach ($_subProducts as $key => $value) {
                if ($key == $_curProductId && $value) {
                    $_throwError = $this->checkSampleTypeError($_quoteItem, $this->_isSample);
                }
            }
        }

        return $_throwError;
    }

    private function updateSampleQuantity($mst_request, $_subProducts, $_isBundle)
    {
        if (isset($_subProducts) && !empty($_subProducts)) {
            $_isGrouped = true;
        } else {
            $_isGrouped = false;
        }
        $this->orderedQty = 1;
        if ($mst_request->getStype() == "sample") {
            $this->_isSample = 1;
            $mst_request->setQty(1);
            if ($_isGrouped) {
                $this->orderedQty = 0;
                $groupedProducts = [];
                foreach ($_subProducts as $key => $value) {
                    $groupedProducts[$key] = $value ? '1' : '0';
                    $this->orderedQty += $value ? 1 : 0;
                }
                $mst_request->setSuperGroup($groupedProducts);
            }
            if ($_isBundle) {
                $bundleQty = $mst_request->getBundleOptionQty();
                $updatedQty = [];
                foreach ($bundleQty as $key => $value) {
                    $updatedQty[$key] = '1';
                }
                $mst_request->setBundleOptionQty($updatedQty);
            }
        }

        return $mst_request;
    }

    private function getProdutType($mst_request)
    {
        $bundleOptions = $mst_request->getBundleOption();
        if (isset($bundleOptions) && !empty($bundleOptions)) {
            $_isBundle = true;
        } else {
            $_isBundle = false;
        }
        return $_isBundle;
    }

    /**
     * @param $_quoteItem
     * @param $_isSample
     * @return int
     */
    private function checkSampleTypeError($_quoteItem, $_isSample)
    {
        $_throwError = 0;
        if ($_quoteItem->getSampleType()) { //Sample in Cart
            $_throwError = $_isSample ? 1 : 2;
        } else {  //Normal in Cart
            if ($_isSample) {
                $_throwError = 3;
            }
        }

        return $_throwError;
    }
}
