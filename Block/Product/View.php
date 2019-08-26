<?php
/**
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\AdvancedSampleOrders\Block\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Class View
 * @package DCKAP\AdvancedSampleOrders\Block\Product
 */
class View extends \Magento\Catalog\Block\Product\View
{
    /**
     * @var \DCKAP\AdvancedSampleOrders\Helper\Config
     */
    protected $_moduleConfig;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Catalog\Helper\Product $productHelper
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param \Magento\Customer\Model\Session $customerSession
     * @param ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \DCKAP\AdvancedSampleOrders\Helper\Config $moduleConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \DCKAP\AdvancedSampleOrders\Helper\Config $moduleConfig,
        array $data = []
    ) {
        $this->_moduleConfig = $moduleConfig;
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );
    }

    /**
     * @return bool
     */
    public function isAllowSampleItem()
    {
        return $this->_moduleConfig->isAllowSampleItem($this->getProduct());
    }
    
    /**
     * @return string
     */
    public function getSamplePriceLabel()
    {
        return $this->_moduleConfig->getSamplePriceLabel($this->getProduct());
    }
}
