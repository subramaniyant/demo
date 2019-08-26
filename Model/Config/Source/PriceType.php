<?php
/**
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\AdvancedSampleOrders\Model\Config\Source;

/**
 * Price Type Options
 *
 */
class PriceType implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Percent | Fixed Options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 0, 'label' => __('Percent')], ['value' => 1, 'label' => __('Fixed')]];
    }
}
