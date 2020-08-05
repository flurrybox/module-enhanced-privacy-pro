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

namespace Flurrybox\EnhancedPrivacyPro\Plugin\EnhancedPrivacy\Model;

use Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface;
use Flurrybox\EnhancedPrivacy\Api\ScheduleRepositoryInterface;
use Flurrybox\EnhancedPrivacyPro\Api\ScheduleStatusRepositoryInterface;
use Magento\Framework\Api\SearchResultsInterface;

/**
 * Class ScheduleRepositoryPlugin.
 */
class ScheduleRepositoryPlugin
{
    /**
     * @var ScheduleStatusRepositoryInterface
     */
    protected $scheduleStatusRepository;

    /**
     * ScheduleRepositoryPlugin constructor.
     *
     * @param ScheduleStatusRepositoryInterface $scheduleStatusRepository
     */
    public function __construct(ScheduleStatusRepositoryInterface $scheduleStatusRepository)
    {
        $this->scheduleStatusRepository = $scheduleStatusRepository;
    }

    /**
     * Assign extension attributes.
     *
     * @param ScheduleRepositoryInterface $subject
     * @param ScheduleInterface $entity
     *
     * @see \Flurrybox\EnhancedPrivacy\Api\ScheduleRepositoryInterface::get()
     *
     * @return ScheduleInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterGet(ScheduleRepositoryInterface $subject, ScheduleInterface $entity)
    {
        $scheduleStatus = $this->scheduleStatusRepository->getByScheduleId((int) $entity->getId());

        $extensionAttributes = $entity->getExtensionAttributes();
        $extensionAttributes->setScheduleStatus($scheduleStatus);
        $entity->setExtensionAttributes($extensionAttributes);

        return $entity;
    }

    /**
     * Assign extension attributes to list.
     *
     * @param ScheduleRepositoryInterface $subject
     * @param SearchResultsInterface $entities
     *
     * @see \Flurrybox\EnhancedPrivacy\Api\ScheduleRepositoryInterface::getList()
     *
     * @return SearchResultsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterGetList(ScheduleRepositoryInterface $subject, SearchResultsInterface $entities)
    {
        foreach ($entities->getItems() as $entity) {
            $this->afterGet($subject, $entity);
        }

        return $entities;
    }

    /**
     * Save schedule status.
     *
     * @param ScheduleRepositoryInterface $subject
     * @param ScheduleInterface $entity
     *
     * @see \Flurrybox\EnhancedPrivacy\Api\ScheduleRepositoryInterface::save()
     *
     * @return ScheduleInterface
     * @throws \Magento\Framework\Exception\StateException
     */
    public function afterSave(ScheduleRepositoryInterface $subject, ScheduleInterface $entity)
    {
        $extensionAttributes = $entity->getExtensionAttributes();
        $scheduleStatus = $extensionAttributes->getScheduleStatus();

        if (!$scheduleStatus) {
            return $entity;
        }

        $scheduleStatus->setScheduleId((int) $entity->getId());

        $this->scheduleStatusRepository->save($scheduleStatus);

        return $entity;
    }
}
