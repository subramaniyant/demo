<?php
/**
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\AdvancedSampleOrders\Plugin\Minicart;

/**
 * Class Item
 * @package DCKAP\AdvancedSampleOrders\Plugin\Minicart
 */
class Item
{
    /**
     * Minicart constructor.
     * @param \Magento\Framework\View\Element\Context $context
     */
    public function __construct(\Magento\Framework\View\Element\Context $context)
    {
        $this->_assetRepo = $context->getAssetRepository();
    }

    /**
     * Retrieve url of a view file
     *
     * @param string $fileId
     * @param array $params
     * @return string
     */
    public function getViewFileUrl($fileId, array $params = [])
    {
        try {
            return $this->_assetRepo->getUrlWithParams($fileId, $params);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            return $this->_getNotFoundUrl();
        }
    }

    /**
     * Get 404 file not found url
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    protected function _getNotFoundUrl($route = '', $params = ['_direct' => 'core/index/notFound'])
    {
        return $this->getUrl($route, $params);
    }

    /**
     * Change the result values of Mini Cart Item
     *
     * @param \Magento\Checkout\CustomerData\AbstractItem $subject
     * @param $proceed
     * @param $item
     * @return mixed
     */
    public function aroundGetItemData(\Magento\Checkout\CustomerData\AbstractItem $subject, $proceed, $item)
    {
        $isSample = $item->getSampleType() ? 1 : 0;
        $result = $proceed($item);

        $result["isSampleItem"] = $isSample;
        $result["sampleTagUrl"] = $this->getViewFileUrl("DCKAP_AdvancedSampleOrders::images/sample-tag.png");
        return $result;
    }
}
