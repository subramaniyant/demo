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
class Status implements \Magento\Framework\Option\ArrayInterface
{
    private $analytics;

    /**
     * Status constructor.
     */
    public function __construct(\DCKAP\AdvancedSampleOrders\Model\Analytics $analytics)
    {
        $this->analytics = $analytics;
    }

    /**
     * List all Statuses
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getAvailableStatuses();
    }

    /**
     * @return array
     */
    private function getAvailableStatuses()
    {
        $options = [];
        $_statuses = $this->analytics->getStatuses();
        foreach ($_statuses as $status) {
            $options[] = ['label' => $status, 'value' => $status];
        }
        return $options;
    }
}
