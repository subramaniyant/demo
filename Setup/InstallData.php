<?php
/**
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\AdvancedSampleOrders\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class InstallData
 * @package DCKAP\AdvancedSampleOrders\Setup
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'dc_sample_allow',
            [
                'group' => 'Sample Product Information',
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Allow Sample',
                'input' => 'boolean',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => 'simple,configurable,bundle,grouped'
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'dc_sample_group',
            [
                'group' => 'Sample Product Information',
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Apply Product Price',
                'input' => 'select',
                'note'  => 'If Use Base Config is selected, 
                below prices will not be considered (General price configuration will be taken)',
                'class' => '',
                'source' => \DCKAP\AdvancedSampleOrders\Model\Product\Attribute\Source\Price\Group::class,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => 'simple,configurable,bundle,grouped'
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'dc_sample_price_type',
            [
                'group' => 'Sample Product Information',
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Price Type',
                'input' => 'select',
                'class' => '',
                'source' => \DCKAP\AdvancedSampleOrders\Model\Product\Attribute\Source\Price\Type::class,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => 'simple'
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'dc_sample_complex_price_type',
            [
                'group' => 'Sample Product Information',
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Price Type',
                'input' => 'select',
                'note' => 'Fixed will not be available for configurable, bundle and grouped products.',
                'class' => '',
                'source' => \DCKAP\AdvancedSampleOrders\Model\Product\Attribute\Source\Price\ComplexType::class,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => 'configurable,bundle,grouped'
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'dc_sample_price_amount',
            [
                'group' => 'Sample Product Information',
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Amount',
                'input' => 'text',
                'note' => "If price type is percent, 
                then the price will be n% of original price where n is above defined value. Also 'n' should be a positive number.",
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => 'simple,configurable,bundle,grouped'
            ]
        );
    }
}
