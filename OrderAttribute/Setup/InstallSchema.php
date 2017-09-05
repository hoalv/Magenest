<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */

namespace Magenest\OrderAttribute\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();
        $table = $installer->getConnection()
            ->newTable($installer->getTable('magenest_orderattribute_order_attribute_value'))
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )->addColumn(
                'order_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Order Id'
            )
            ->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Customer Id'
            )
            ->addColumn(
                'attribute_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Attribute Id'
            )
            ->addColumn(
                'attribute_value',
                Table::TYPE_TEXT,
                255,
                ['default' => null, 'nullable' => false],
                'Attribute Value'
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => true, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Created At'
            );

        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable('magenest_orderattribute_shipping_methods'))
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true,
                    'nullable' => false, 'primary' => true],
                'Id'
            )
            ->addColumn(
                'attribute_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Attribute Id'
            )
            ->addColumn(
                'shipping_method',
                Table::TYPE_TEXT,
                255,
                ['default' => null, 'nullable' => false],
                'Shipping Method'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                [],
                'Created At'
            );
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable(
                $installer->getTable('magenest_orderattribute_order_eav_attribute')
            )
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ],
                'ID'
            )
            ->addColumn(
                'attribute_id',
                Table::TYPE_INTEGER,
                5,
                [ 'unsigned' => true, 'nullable' => false],
                'Attribute Id'
            )
            ->addColumn(
                'is_visible_on_front',
                Table::TYPE_SMALLINT,
                1,
                ['nullable' => false, 'unsigned' => true],
                'Visible on Frontend'
            )
            ->addColumn(
                'is_visible_on_back',
                Table::TYPE_SMALLINT,
                1,
                ['nullable' => false, 'unsigned' => true],
                'Visible on Backend'
            )
            ->addColumn(
                'is_used_in_grid',
                Table::TYPE_SMALLINT,
                1,
                ['nullable' => false, 'unsigned' => true],
                'Show on Order Grid'
            )
            ->addColumn(
                'store_ids',
                Table::TYPE_TEXT,
                128,
                ['default' => null, 'nullable' => false],
                'Store Ids'
            )
            ->addColumn(
                'customer_groups',
                Table::TYPE_TEXT,
                128,
                ['default' => null, 'nullable' => false],
                'Customer Groups'
            )
            ->addColumn(
                'sorting_order',
                Table::TYPE_SMALLINT,
                5,
                ['nullable' => false, 'unsigned' => true],
                'Sorting Order'
            )
            ->addColumn(
                'checkout_step',
                Table::TYPE_SMALLINT,
                1,
                ['nullable' => false, 'unsigned' => true],
                'Checkout Step'
            )->addColumn(
                'include_pdf',
                Table::TYPE_SMALLINT,
                1,
                ['nullable' => false, 'unsigned' => true],
                'include_pdf'
            )->addColumn(
                'include_html_print_order',
                Table::TYPE_SMALLINT,
                1,
                ['nullable' => false, 'unsigned' => true],
                'Include on html print'
            )->addColumn(
                'country_id',
                Table::TYPE_TEXT,
                255,
                ['default' => null, 'nullable' => false],
                'Country Id'
            );
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
            $installer->getTable('magenest_orderattribute_order_option_value')
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            [
                'identity' => true,
                'nullable' => false,
                'primary'  => true,
                'unsigned' => true,
            ],
            'ID'
        )->addColumn(
            'attribute_id',
            Table::TYPE_INTEGER,
            11,
            ['nullable => false'],
            'Attribute Id'
        )->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                11,
                ['nullable => false'],
                'Entity  Id'
        )->addColumn(
                'type',
                Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'Type'
        )->addColumn(
                'value',
                Table::TYPE_TEXT,
                null,
                ['nullable => false'],
                'Value'
        )->addColumn(
                'country',
                Table::TYPE_TEXT,
                null,
                ['nullable => false'],
                'Country'
        )->addColumn(
                'country_code',
                Table::TYPE_TEXT,
                null,
                ['nullable => false'],
                'Country Code'
        )->addColumn(
                'ip_address',
                Table::TYPE_TEXT,
                50,
                ['nullable => false'],
                'Ip Address'
        )->addColumn(
                'validate_rules',
                Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'Type'
        )->addColumn(
                'is_checked',
                Table::TYPE_SMALLINT,
                null,
                ['nullable => false'],
                'Is Checked'
        )->addColumn(
                'note',
                Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'Note'
        )
            ->setComment('Option Table');
        $installer->getConnection()->createTable($table);

        $installer->getConnection()->update(
            $installer->getTable('eav_entity_type'),
            ['additional_attribute_table' => 'magenest_orderattribute_order_eav_attribute'],
            "entity_type_code = 'order'"
        );

        $installer->endSetup();
    }
}
