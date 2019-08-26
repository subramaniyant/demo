<?php

/**
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace DCKAP\AdvancedSampleOrders\Api\Data;

/**
 * Interface for managing sample type
 */
interface TotalsItemInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**
     *  Sample Type
     */
    const KEY_SAMPLE_TYPE = "sample_type";

    /**
     * Returns the product name.
     *
     * @return string|null Sample Type. Otherwise, null.
     */
    public function getSampleType();

    /**
     * Sets the product name.
     *
     * @param string $type
     * @return $this
     */
    public function setSampleType($type);
}
