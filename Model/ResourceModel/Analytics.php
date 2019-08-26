<?php
/**
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\AdvancedSampleOrders\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Analytics
 * @package DCKAP\AdvancedSampleOrders\Model\ResourceModel
 */
class Analytics extends AbstractDb
{

    /**
     * Initialize with table name and primary key
     */
    protected function _construct()
    {
        $this->_init('dckap_advancedsampleorders', 'item_id');
    }
}
