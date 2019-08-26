<?php
/**
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\AdvancedSampleOrders\Model\Cart\Totals;

use DCKAP\AdvancedSampleOrders\Api\Data\TotalsItemInterface;

/**
 * Includes getter and setter for column sample_type
 */
class Item extends \Magento\Quote\Model\Cart\Totals\Item implements TotalsItemInterface
{
    /**
     * Set Sample Type
     *
     * @param int $type
     * @return $this
     */
    public function setSampleType($type)
    {
        return $this->setData(self::KEY_SAMPLE_TYPE, $type);
    }

    /**
     * Get Sample Type
     *
     * @return int Sample Type
     */
    public function getSampleType()
    {
        return $this->_get(self::KEY_SAMPLE_TYPE);
    }
}
