<?php
/**
 * This file is part of the Flurrybox EnhancedPrivacyPro package.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Flurrybox EnhancedPrivacyPro
 * to newer versions in the future.
 *
 * @copyright Copyright (c) 2018 Flurrybox, Ltd. (https://flurrybox.com/)
 * @license   GNU General Public License ("GPL") v3.0
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Flurrybox\EnhancedPrivacyPro\Setup;

use Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface;
use Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Module install schema.
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module.
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $table = $setup->getConnection()
            ->newTable($setup->getTable(ScheduleStatusInterface::TABLE))
            ->addColumn(
                ScheduleStatusInterface::ID,
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )
            ->addColumn(
                ScheduleStatusInterface::SCHEDULE_ID,
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false],
                'Schedule Id'
            )
            ->addColumn(
                ScheduleStatusInterface::STATE,
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false],
                'State'
            )
            ->addColumn(
                ScheduleStatusInterface::REVIEWER_ID,
                Table::TYPE_INTEGER,
                null,
                ['nullable' => true],
                'Reviewer Id'
            )
            ->addColumn(
                ScheduleStatusInterface::APPROVED_AT,
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => true, 'default' => null],
                'Approved At'
            )
            ->setComment('Schedule item status');

        $setup->getConnection()->createTable($table);

        $table = $setup->getConnection()
            ->newTable($setup->getTable(ConsentInterface::TABLE))
            ->addColumn(
                ConsentInterface::ID,
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )
            ->addColumn(
                ConsentInterface::CUSTOMER_ID,
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Customer Id'
            )
            ->addColumn(
                ConsentInterface::CODE,
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Consent Code'
            )
            ->addColumn(
                ConsentInterface::IS_ALLOWED,
                Table::TYPE_BOOLEAN,
                null,
                ['nullable' => false, 'default' => 0],
                'Is Allowed'
            )
            ->addForeignKey(
                $setup->getFkName(
                    ConsentInterface::TABLE,
                    ConsentInterface::CUSTOMER_ID,
                    'customer_entity',
                    'entity_id'
                ),
                ConsentInterface::CUSTOMER_ID,
                $setup->getTable('customer_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            )
            ->setComment('Consents');

        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
