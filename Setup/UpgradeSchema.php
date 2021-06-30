<?php
namespace ConfigWise\Configurator\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (!$context->getVersion()) {
        }

        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            //code to upgrade to 1.0.2
            if (!$setup->tableExists('configwise_frame')) {
                $table = $setup->getConnection()->newTable($setup->getTable('configwise_frame'))
                    ->addColumn(
                        'entity_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'Configwise Frame Id'
                    )
                    ->addColumn(
                        'product_number',
                        Table::TYPE_TEXT,
                        250,
                        [],
                        'Product Number'
                    )
                    ->addColumn(
                        'original',
                        Table::TYPE_TEXT,
                        500,
                        [],
                        'Original'
                    )
                    ->addColumn(
                        'small',
                        Table::TYPE_TEXT,
                        500,
                        [],
                        'Small'
                    )
                    ->addColumn(
                        'medium',
                        Table::TYPE_TEXT,
                        500,
                        [],
                        'Medium'
                    )
                    ->addColumn(
                        'large',
                        Table::TYPE_TEXT,
                        500,
                        [],
                        'large'
                    )
                    ->setComment('ConfigWise Frame');
                         $setup->getConnection()->createTable($table);
            }
        }
        $setup->endSetup();
    }
}
