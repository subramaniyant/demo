<?php
namespace DCKAP\AdvancedSampleOrders\Model\Quote;

class Discount extends \Magento\SalesRule\Model\Quote\Discount
{
    /**
     * Distribute discount at parent item to children items
     *
     * @param \Magento\Quote\Model\Quote\Item\AbstractItem $item
     * @return $this
     */
    protected function distributeDiscount(\Magento\Quote\Model\Quote\Item\AbstractItem $item)
    {
        $parentBaseRowTotalAlt = $item->getBaseRowTotal();
        $keysAlt = [
            'discount_amount',
            'base_discount_amount',
            'original_discount_amount',
            'base_original_discount_amount',
        ];
        $roundingDelta = [];
        foreach ($keysAlt as $keyAlt) {
            //Initialize the rounding delta to a tiny number to avoid floating point precision problem
            $roundingDelta[$keyAlt] = 0.0000001;
        }
        foreach ($item->getChildren() as $childAlt) {
            $ratio = ($parentBaseRowTotalAlt) ? $childAlt->getBaseRowTotal() / $parentBaseRowTotalAlt : 0;
            foreach ($keysAlt as $keyAlt) {
                if (!$item->hasData($keyAlt)) {
                    continue;
                }
                $value = $item->getData($keyAlt) * $ratio;
                $roundedValue = $this->priceCurrency->round($value + $roundingDelta[$keyAlt]);
                $roundingDelta[$keyAlt] += $value - $roundedValue;
                $childAlt->setData($keyAlt, $roundedValue);
            }
        }

        foreach ($keysAlt as $keyAlt) {
            $item->setData($keyAlt, 0);
        }
        return $this;
    }
}
