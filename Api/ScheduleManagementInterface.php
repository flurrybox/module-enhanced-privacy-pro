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

namespace Flurrybox\EnhancedPrivacyPro\Api;

use Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface;

/**
 * Schedule management actions.
 *
 * @api
 * @since 1.0.0
 */
interface ScheduleManagementInterface
{
    /**
     * Approve scheduled request.
     *
     * @param ScheduleInterface $schedule
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function approveRequest(ScheduleInterface $schedule);
}
