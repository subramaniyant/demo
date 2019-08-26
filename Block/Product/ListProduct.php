<?php

namespace DCKAP\AdvancedSampleOrders\Block\Product;

use Magento\Catalog\Model\Product;

class ListProduct extends \Magento\Catalog\Block\Product\ListProduct
{
    /**
     * @var \DCKAP\AdvancedSampleOrders\Helper\Config
     */
    protected $moduleConfig;

    public $pro;

    protected $_catalogLayer;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param \DCKAP\AdvancedSampleOrders\Helper\Config $_moduleConfig
     * @param \Magento\Catalog\Block\Product\View $_product
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \DCKAP\AdvancedSampleOrders\Helper\Config $moduleConfig,
        \Magento\Catalog\Block\Product\View $pro,
        array $data = []
    ) {
        $this->_moduleConfig = $moduleConfig;
        $this->pro = $pro;
        $this->_catalogLayer = $layerResolver->get();
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper,
            $data
        );
    }

    /**
     * @return bool
     */
    public function isAllowSampleItem()
    {
        return $this->_moduleConfig->isAllowSampleItem($this->pro->getProduct());
    }

    public function isPdpDisplay(Product $product)
    {
        return $this->_moduleConfig->isPdpDisplay($product);
    }

    /**
     * @return string
     */
    public function getSamplePriceLabel()
    {
        return $this->_moduleConfig->getSamplePriceLabel($this->pro->getProduct());
    }
}
