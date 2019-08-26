<?php
namespace DCKAP\AdvancedSampleOrders\Model\Address\Total;

use Magento\Quote\Model\Quote\Address\Total\Subtotal as AddressTotal;
use Magento\Quote\Model\Quote\Address\Item as AddressItem;
use Magento\Quote\Model\Quote\Item;

class Subtotal extends AddressTotal
{
    /**
     * @var \DCKAP\AdvancedSampleOrders\Helper\Config
     */
    private $moduleConfig;

    /**
     * Subtotal constructor.
     * @param \Magento\Quote\Model\QuoteValidator $quoteValidator
     * @param \DCKAP\AdvancedSampleOrders\Helper\Config $moduleConfig
     */
    public function __construct(
        \Magento\Quote\Model\QuoteValidator $quoteValidator,
        \DCKAP\AdvancedSampleOrders\Helper\Config $moduleConfig
    ) {
        parent::__construct($quoteValidator);
        $this->moduleConfig = $moduleConfig;
        $this->quoteValidator = $quoteValidator;
    }

    /**
     * @param \Magento\Quote\Model\Quote\Address $address
     * @param AddressItem|Item $item
     * @return bool
     */
    protected function _initItem($address, $item)
    {
        if ($item instanceof AddressItem) {
            $quoteItem = $item->getAddress()->getQuote()->getItemById($item->getQuoteItemId());
        } else {
            $quoteItem = $item;
        }
        $product = $quoteItem->getProduct();
        $product->setCustomerGroupId($quoteItem->getQuote()->getCustomerGroupId());

        /**
         * Quote super mode flag mean what we work with quote without restriction
         */
        if ($item->getQuote()->getIsSuperMode()) {
            if (!$product) {
                return false;
            }
        } else {
            if (!$product || !$product->isVisibleInCatalog()) {
                return false;
            }
        }

        $quoteItem->setConvertedPrice(null);
        $originalPrice = $product->getPrice();
        if ($quoteItem->getParentItem() && $quoteItem->isChildrenCalculated()) {
            $finalPrice = $quoteItem->getParentItem()->getProduct()->getPriceModel()->getChildFinalPrice(
                $quoteItem->getParentItem()->getProduct(),
                $quoteItem->getParentItem()->getQty(),
                $product,
                $quoteItem->getQty()
            );

            $finalPrice = $this->getSamplePrice($quoteItem, $finalPrice);
            $this->_calculateRowTotal($item, $finalPrice, $originalPrice);
        } elseif (!$quoteItem->getParentItem()) {
            $finalPrice = $product->getFinalPrice($quoteItem->getQty());
            $finalPrice = $this->getSamplePrice($quoteItem, $finalPrice);
            $this->_calculateRowTotal($item, $finalPrice, $originalPrice);
            $this->_addAmount($item->getRowTotal());
            $this->_addBaseAmount($item->getBaseRowTotal());
            $address->setTotalQty($address->getTotalQty() + $item->getQty());
        }
        return true;
    }

    /**
     * @param Item $quoteItem
     * @param $finalPrice
     * @return int|mixed
     */
    private function getSamplePrice(Item $quoteItem, $finalPrice)
    {
        $samplePrice = $finalPrice;

        if ($quoteItem->getSampleType()) {
            $samplePrice = $this->moduleConfig->getSamplePrice(
                $quoteItem->getProduct()->getId(),
                $quoteItem->getProductType(),
                $finalPrice
            );
        }
        return $samplePrice;
    }
}
