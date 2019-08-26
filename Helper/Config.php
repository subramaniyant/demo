<?php
/**
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\AdvancedSampleOrders\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Catalog\Model\Product;

/**
 * Backend Configurations
 */
class Config
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $_scopeConfig;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $_session;

    /**
     * @var array
     */
    //private $_complexProductTypes;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    private $_productRepository;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    private $_pricingHelper;

    /**
     * @var int
     */
    private $customerGroupId;

    private $loggedIn;
    private $_complexProductTypes = ["bundle", "grouped", "configurable"];
    const PRICECONFIG = 'advancedsampleorders/pricing/amount';
    const GENERALTYPE = "advancedsampleorders/general/type";

    /**
     * Config constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Customer\Model\SessionFactory $session
     * @param \Magento\Framework\Pricing\Helper\Data $pricingHelper
     * @param \Magento\Catalog\Model\ProductRepository $productRepository
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Customer\Model\SessionFactory $session,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        \Magento\Catalog\Model\ProductRepository $productRepository
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_session = $session;
        $this->_productRepository = $productRepository;
        $this->_pricingHelper = $pricingHelper;
    }

    /**
     * @param Product $_product
     * @return bool
     */
    public function isAllowSampleItem(Product $_product)
    {
        $_isAllow = false;
        $productWise = $this->getStoreConfigValue("advancedsampleorders/general/type");
        $isEnabled = $this->getStoreConfigValue("advancedsampleorders/general/enabled");
        $loggedIn = $this->getStoreConfigValue("advancedsampleorders/general/loggedin");
        $_invalidTypes = ["downloadable","virtual"];

        if ($isEnabled && !in_array($_product->getTypeId(), $_invalidTypes)) {
            $_isAllow = $loggedIn ? $this->allowSampleForCurrentUser() : true;
            if ($_isAllow) {
                $_isAllow = $productWise ? $this->getProductConfig($_product) : true;
            }
        }

        return $_isAllow;
    }

    public function isPdpDisplay(Product $_product)
    {
        $_isAllow = $this->isAllowSampleItem($_product);
        if ($_isAllow) {
            return $this->getStoreConfigValue("advancedsampleorders/general/displaypage");
        }
//        $displayPage = $this->getStoreConfigValue("advancedsampleorders/general/displaypage");

        return false;
    }
    /**
     * @param $productId
     * @return \Magento\Catalog\Api\Data\ProductInterface|mixed
     */
    private function getProduct($productId)
    {
        return $this->_productRepository->getById($productId);
    }

    /**
     * @param Product $_product
     * @return bool
     */
    private function getProductConfig(Product $_product)
    {
        return $_product->getDcSampleAllow() ? true : false;
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getStoreConfigValue($path)
    {
        return $this->_scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    private function allowSampleForCurrentUser()
    {
        $this->loggedIn = $this->_session->create()->isLoggedIn();
        return $this->loggedIn ? $this->matchCustomerGroups() : false;
    }

    /**
     * @return bool
     */
    private function matchCustomerGroups()
    {
        $selectedGroups = $this->getStoreConfigValue("advancedsampleorders/general/customergroup");
        $this->customerGroupId = $this->_session->create()->getCustomerGroupId();
        return in_array($this->customerGroupId, explode(",", $selectedGroups)) ? true : false;
    }

    /**
     * @param $itemId
     * @param $productType
     * @param $finalPrice
     * @return int|mixed
     */
    public function getSamplePrice($itemId, $productType, $finalPrice)
    {
        $customPrice = $finalPrice;
        $_product = $this->getProduct($itemId);
        $_priceGroup = $this->getStoreConfigValue("advancedsampleorders/general/type")
            ? $_product->getDcSampleGroup() : '0';

        switch ($_priceGroup) {
            case '0':
                $_genPriceType = in_array($productType, $this->_complexProductTypes)
                    ? 0 : $this->getStoreConfigValue('advancedsampleorders/pricing/type');
                $_genPriceVal = $this->getStoreConfigValue(self::PRICECONFIG)
                    ? $this->getStoreConfigValue(self::PRICECONFIG) : 0;

                if (!$_genPriceType) {
                    $customPrice = $customPrice * ( $_genPriceVal / 100 );
                } else {
                    $customPrice = $_genPriceVal;
                }
                break;
            case '1':
                $_priceType = in_array($productType, $this->_complexProductTypes)
                    ? $_product->getDcSampleComplexPriceType() : $_product->getDcSamplePriceType();
                $_newPriceVal = $_product->getDcSamplePriceAmount() ? $_product->getDcSamplePriceAmount() : 0;

                if (!$_priceType) {
                    $customPrice = $customPrice * ( $_newPriceVal / 100 );
                } else {
                    $customPrice = $_newPriceVal;
                }
                break;
        }

        return $customPrice;
    }

    /**
     * @param Product $_product
     * @return string
     */
    public function getSamplePriceLabel(Product $_product)
    {
        $_priceLabel = "at regular price";
        $_priceGroup = $this->getStoreConfigValue(self::GENERALTYPE) ? $_product->getDcSampleGroup() : '0';

        switch ($_priceGroup) {
            case '0':
                $_priceLabel = $this->getBaseConfigPriceLabel($_product);
                break;
            case '1':
                $_priceLabel = $this->getProductSpecificPriceLabel($_product);
                break;
        }

        return $_priceLabel;
    }

    /**
     * @param Product $_product
     * @return string
     */
    private function getBaseConfigPriceLabel(Product $_product)
    {
        $_genPriceType = $this->getStoreConfigValue('advancedsampleorders/pricing/type');
        $_genPriceVal = $this->getStoreConfigValue(self::PRICECONFIG)
            ? $this->getStoreConfigValue(self::PRICECONFIG) : 0;

        if (!$_genPriceType || in_array($_product->getTypeId(), $this->_complexProductTypes)) {
            $_priceLabel = $_genPriceVal ? $this->getPercentText($_genPriceVal) : "FREE";
        } else {
            $_priceLabel = $this->getFixedPriceText($_genPriceVal);
        }

        return $_priceLabel;
    }

    /**
     * @param Product $_product
     * @return string
     */
    private function getProductSpecificPriceLabel(Product $_product)
    {
        $_priceType = in_array($_product->getTypeId(), $this->_complexProductTypes)
            ? $_product->getDcSampleComplexPriceType() : $_product->getDcSamplePriceType();
        $_newPriceVal = $_product->getDcSamplePriceAmount() ? $_product->getDcSamplePriceAmount() : 0;

        if (!$_priceType) {
            $_priceLabel = $_newPriceVal ? $this->getPercentText($_newPriceVal) : "FREE";
        } else {
            $_priceLabel = $this->getFixedPriceText($_newPriceVal);
        }

        return $_priceLabel;
    }

    /**
     * @param $_priceVal
     * @return string
     */
    private function getPercentText($_priceVal)
    {
        return $_priceVal == 100 ? 'at regular price' : 'at ' . $_priceVal . '% of regular price';
    }

    /**
     * @param $_priceVal
     * @return string
     */
    private function getFixedPriceText($_priceVal)
    {
        return $_priceVal ? 'for ' . $this->_pricingHelper->currency($_priceVal, true, false) : 'FREE';
    }
}
