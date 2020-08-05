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

namespace Flurrybox\EnhancedPrivacyPro\Plugin\DataProvider;

use Flurrybox\EnhancedPrivacy\Api\Data\ReasonInterface;
use Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory;

/**
 * Class CollectionPlugin.
 */
class CollectionFactoryPlugin
{
    /**
     * Join schedule status to schedule grid.
     *
     * @param CollectionFactory $subject
     * @param callable $proceed
     * @param string $requestName
     *
     * @see \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory::getReport()
     *
     * @return \Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult
     */
    public function aroundGetReport(CollectionFactory $subject, callable $proceed, string $requestName)
    {
        /** @var \Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult $result */
        $result = $proceed($requestName);

        if ($requestName !== 'deletion_request_listing_data_source') {
            return $result;
        }

        $result
            ->getSelect()
            ->join(
                ['schedule_status' => ScheduleStatusInterface::TABLE],
                'main_table.id = schedule_status.schedule_id',
                [ScheduleStatusInterface::STATE]
            )
            ->join(
                ['reason' => ReasonInterface::TABLE],
                'main_table.reason_id = reason.id',
                [ReasonInterface::REASON]
            );

        return $result;
    }
}
