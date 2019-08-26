<?php
/**
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\AdvancedSampleOrders\Plugin\Cart\Item\Renderer;

/**
 * Class Edit
 * @package DCKAP\AdvancedSampleOrders\Plugin\Cart\Item\Renderer
 */
class Edit
{
    /**
     * @param \Magento\Checkout\Block\Cart\Item\Renderer\Actions\Edit $renderer
     * @param $result
     * @return bool
     */
    public function afterIsProductVisibleInSiteVisibility(
        \Magento\Checkout\Block\Cart\Item\Renderer\Actions\Edit $renderer,
        $result
    ) {
        return $renderer->getItem()->getSampleType() ? false : $result;
    }
}
