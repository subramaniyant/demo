<?php
/**
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\AdvancedSampleOrders\Plugin\Cart\Item;

/**
 * Class Renderer
 * @package DCKAP\AdvancedSampleOrders\Plugin\Cart\Item
 */
class Renderer
{
    /**
     * @param \Magento\Checkout\Block\Cart\Item\Renderer $renderer
     * @param $result
     * @return string
     */
    public function afterGetProductName(\Magento\Checkout\Block\Cart\Item\Renderer $renderer, $result)
    {
        if ($renderer->getItem()->getSampleType()) {
            $result .= __(" ( SAMPLE )");
        }
        return $result;
    }
}
