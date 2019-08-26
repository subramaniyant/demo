<?php
/**
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\AdvancedSampleOrders\Model\ResourceModel\Analytics;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package DCKAP\AdvancedSampleOrders\Model\ResourceModel\Analytics
 */
class Collection extends AbstractCollection
{
    /**
     * Initialize model and resource model
     */
    protected function _construct()
    {
        $this->_init(
            \DCKAP\AdvancedSampleOrders\Model\Analytics::class,
            \DCKAP\AdvancedSampleOrders\Model\ResourceModel\Analytics::class
        );
    }
}
