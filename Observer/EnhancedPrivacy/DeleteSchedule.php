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

namespace Flurrybox\EnhancedPrivacyPro\Observer\EnhancedPrivacy;

use Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface;
use Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterface;
use Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterfaceFactory;
use Flurrybox\EnhancedPrivacyPro\Helper\Data;
use Flurrybox\EnhancedPrivacyPro\Model\Config\Source\Behaviour;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Add status before saving schedule item.
 */
class DeleteSchedule implements ObserverInterface
{
    /**
     * @var ScheduleStatusInterfaceFactory
     */
    protected $scheduleStatusFactory;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * DeleteSchedule constructor.
     *
     * @param ScheduleStatusInterfaceFactory $scheduleStatusFactory
     * @param Data $helper
     */
    public function __construct(ScheduleStatusInterfaceFactory $scheduleStatusFactory, Data $helper)
    {
        $this->scheduleStatusFactory = $scheduleStatusFactory;
        $this->helper = $helper;
    }

    /**
     * Execute observer.
     *
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var ScheduleInterface $schedule */
        $schedule = $observer->getEvent()->getData('schedule');

        $scheduleStatus = $this->scheduleStatusFactory->create();

        if ($this->helper->getCustomerDeletionBehaviour() !== Behaviour::AUTOMATIC) {
            $scheduleStatus->setState(ScheduleStatusInterface::STATE_UNAPPROVED);
            $schedule->setScheduledAt('');
        } else {
            $scheduleStatus->setState(ScheduleStatusInterface::STATE_APPROVED);
        }

        $schedule->getExtensionAttributes()->setScheduleStatus($scheduleStatus);
    }
}
