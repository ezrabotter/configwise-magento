<?php
namespace ConfigWise\Configurator\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (!$setup->tableExists('configwise_product')) {
            $table = $setup->getConnection()->newTable($setup->getTable('configwise_product'))
                ->addColumn(
                    'entity_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Configwise Product Id'
                )
                ->addColumn(
                    'product_number',
                    Table::TYPE_TEXT,
                    250,
                    [],
                    'Product Number'
                )
                ->addColumn(
                    'app_name',
                    Table::TYPE_TEXT,
                    500,
                    [],
                    'App Name'
                )
                ->addColumn(
                    'product_url',
                    Table::TYPE_TEXT,
                    500,
                    [],
                    'Product Url'
                )
                ->setComment('ConfigWise Product');
              $setup->getConnection()->createTable($table);
        }
        $setup->endSetup();
    }
}
