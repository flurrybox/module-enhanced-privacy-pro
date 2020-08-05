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

namespace Flurrybox\EnhancedPrivacyPro\Model;

use Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface;
use Flurrybox\EnhancedPrivacy\Api\ScheduleRepositoryInterface;
use Flurrybox\EnhancedPrivacy\Helper\Data as PrivacyHelper;
use Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterface;
use Flurrybox\EnhancedPrivacyPro\Api\ScheduleManagementInterface;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Schedule management actions.
 *
 * @api
 * @since 1.0.0
 */
class ScheduleManagement implements ScheduleManagementInterface
{
    /**
     * @var ScheduleRepositoryInterface
     */
    protected $scheduleRepository;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var PrivacyHelper
     */
    protected $privacyHelper;

    /**
     * ScheduleManagement constructor.
     *
     * @param ScheduleRepositoryInterface $scheduleRepository
     * @param DateTime $dateTime
     * @param Session $session
     * @param PrivacyHelper $privacyHelper
     */
    public function __construct(
        ScheduleRepositoryInterface $scheduleRepository,
        DateTime $dateTime,
        Session $session,
        PrivacyHelper $privacyHelper
    ) {
        $this->scheduleRepository = $scheduleRepository;
        $this->dateTime = $dateTime;
        $this->session = $session;
        $this->privacyHelper = $privacyHelper;
    }

    /**
     * Approve scheduled request.
     *
     * @param ScheduleInterface $schedule
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function approveRequest(ScheduleInterface $schedule)
    {
        $schedule->setScheduledAt(
            date('Y-m-d H:i:s', $this->dateTime->gmtTimestamp() + $this->privacyHelper->getDeletionTime())
        );

        $scheduleStatus = $schedule->getExtensionAttributes()->getScheduleStatus();
        $scheduleStatus
            ->setState(ScheduleStatusInterface::STATE_APPROVED)
            ->setApprovedAt(date('Y-m-d H:i:s', $this->dateTime->gmtTimestamp()))
            ->setReviewerId((int) $this->session->getUser()->getId());

        try {
            $this->scheduleRepository->save($schedule);
        } catch (StateException $e) {
            throw new LocalizedException(__('Failed to approve request'));
        }

        return $schedule;
    }
}
