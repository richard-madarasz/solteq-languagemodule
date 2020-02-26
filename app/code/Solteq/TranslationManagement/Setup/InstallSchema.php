<?php
namespace Solteq\TranslationManagement\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('solteq_translationmanagement')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('solteq_translationmanagement')
            )
                ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false,
                    'primary'  => true,
                ],
                'ID'
            )
                ->addColumn(
                    'string',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default'=>''],
                    'String variable name'
                )
                ->addColumn(
                    'translation',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default'=>''],
                    'Translation text'
                )
                ->addColumn(
                    'location',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default'=>''],
                    'Location of file'
                )
                ->addColumn(
                    'parent_type',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => true],
                    'Module or else'
                )
                ->addColumn(
                    'parent_name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => true],
                    'Name of module/etc'
                );
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}

