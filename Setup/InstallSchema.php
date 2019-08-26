<?php
/**
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\AdvancedSampleOrders\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface as Db;

/**
 * Class InstallSchema
 * @package DCKAP\AdvancedSampleOrders\Setup
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * TABLE_NAME
     */
    const TABLE_NAME = "dckap_advancedsampleorders";

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (!$setup->tableExists(self::TABLE_NAME)) {
            $this->createAnalyticsTable($setup);
        }

        $this->addSampleTypeColumn($setup);
    }

    /**
     * @param $setup
     */
    private function createAnalyticsTable($setup)
    {
        $table = $setup->getConnection()->newTable(self::TABLE_NAME);
        $table->addColumn('item_id', Table::TYPE_INTEGER, null, [
            'unsigned'    =>    true,
            'primary'    =>    true,
            'identity'    =>    true,
            'nullable'    =>    false
        ]);
        $table->addColumn('product_id', Table::TYPE_TEXT, 5, [
            'nullable'    =>    false
        ]);
        $table->addColumn('customer_id', Table::TYPE_TEXT, 5, [
            'nullable'    =>    false
        ]);
        $table->addColumn('email', Table::TYPE_TEXT, 100, [
            'nullable'    =>    false
        ]);
        $table->addColumn('sample_ordered_date', Table::TYPE_DATETIME, null, [
            'nullable'    =>    false
        ]);
        $table->addColumn('order_placed_date', Table::TYPE_DATETIME, null, [
            'nullable'    =>    false
        ]);
        $table->addColumn('sample_price', Table::TYPE_TEXT, 50, [
            'nullable'    =>    false
        ]);
        $table->addColumn('order_price', Table::TYPE_TEXT, 50, [
            'nullable'    =>    false
        ]);
        $table->addColumn('sample_order_id', Table::TYPE_TEXT, 50, [
            'nullable'    =>    false
        ]);
        $table->addColumn('converted_order_id', Table::TYPE_TEXT, null, [
            'nullable'    =>    false
        ]);
        $table->addColumn('total_count', Table::TYPE_TEXT, 10, [
            'nullable'    =>    false
        ]);
        $table->addColumn('order_status', Table::TYPE_TEXT, 100, [
            'nullable'    =>    false
        ]);
        $table->addColumn('converted', Table::TYPE_TEXT, 11, [
            'nullable'    =>    true,
            'default'   => 0
        ]);

        $table->addIndex(
            $setup->getIdxName(
                self::TABLE_NAME,
                ['item_id']
            ),
            ['item_id']
        );

        $setup->getConnection()->createTable($table);
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private function addSampleTypeColumn(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order'),
            'sample_type',
            [
                'type' => Table::TYPE_INTEGER,
                'length' => '2',
                'default' => 0,
                'nullable' => false,
                'comment'   =>  'Sample Type'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order_item'),
            'sample_type',
            [
                'type' => Table::TYPE_INTEGER,
                'length' => '2',
                'default' => 0,
                'nullable' => false,
                'comment'   =>  'Sample Type'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order_grid'),
            'sample_type',
            [
                'type' => Table::TYPE_INTEGER,
                'length' => '2',
                'default' => 0,
                'nullable' => false,
                'comment'   =>  'Sample Type'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('quote_item'),
            'sample_type',
            [
                'type' => Table::TYPE_INTEGER,
                'length' => '2',
                'default' => 0,
                'nullable' => false,
                'comment'   =>  'Sample Type'
            ]
        );
    }
}
