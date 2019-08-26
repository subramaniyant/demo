<?php
/**
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace DCKAP\AdvancedSampleOrders\Ui\Component\Listing\Column\SampleType;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Options
 */
class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = [];

            $this->options[] = [
                'value' => 1,
                'label' => "Yes"
            ];
            $this->options[] = [
                'value' => 0,
                'label' => "No"
            ];
        }
        return $this->options;
    }
}
