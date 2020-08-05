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

use Exception;
use Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterface;
use Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterfaceFactory;
use Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusSearchResultsInterfaceFactory;
use Flurrybox\EnhancedPrivacyPro\Api\ScheduleStatusRepositoryInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;

/**
 * Class ScheduleStatusRepository.
 *
 * @api
 * @since 1.0.0
 */
class ScheduleStatusRepository implements ScheduleStatusRepositoryInterface
{
    /**
     * @var ScheduleStatusInterfaceFactory
     */
    protected $scheduleStatusFactory;

    /**
     * @var ResourceModel\ScheduleStatus
     */
    protected $scheduleStatusResource;

    /**
     * @var ResourceModel\ScheduleStatus\CollectionFactory
     */
    protected $scheduleStatusCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var ScheduleStatusSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * ScheduleStatusRepository constructor.
     *
     * @param ScheduleStatusInterfaceFactory $scheduleStatusFactory
     * @param ResourceModel\ScheduleStatus $scheduleStatusResource
     * @param ResourceModel\ScheduleStatus\CollectionFactory $scheduleStatusCollectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ScheduleStatusSearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        ScheduleStatusInterfaceFactory $scheduleStatusFactory,
        ResourceModel\ScheduleStatus $scheduleStatusResource,
        ResourceModel\ScheduleStatus\CollectionFactory $scheduleStatusCollectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        ScheduleStatusSearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->scheduleStatusFactory = $scheduleStatusFactory;
        $this->scheduleStatusResource = $scheduleStatusResource;
        $this->scheduleStatusCollectionFactory = $scheduleStatusCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * Get schedule status.
     *
     * @param int $id
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id)
    {
        $scheduleStatus = $this->scheduleStatusFactory->create();
        $this->scheduleStatusResource->load($scheduleStatus, $id);

        if (!$scheduleStatus->getId()) {
            throw new NoSuchEntityException(__('Requested schedule status doesn\'t exist!'));
        }

        return $scheduleStatus;
    }

    /**
     * Get schedule status by schedule id.
     *
     * @param int $id
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByScheduleId(int $id)
    {
        $scheduleStatus = $this->scheduleStatusFactory->create();

        try {
            $this->scheduleStatusResource->load($scheduleStatus, $this->scheduleStatusResource->getIdBySchedule($id));

            if (!$scheduleStatus->getId()) {
                throw new NoSuchEntityException(__('Requested schedule status doesn\'t exist!'));
            }
        } catch (LocalizedException $e) {
            throw new NoSuchEntityException(__('Requested schedule status doesn\'t exist!'));
        }

        return $scheduleStatus;
    }

    /**
     * Get scheduled status items that match specific criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->scheduleStatusCollectionFactory->create();
        $collection->addFieldToSelect('*');

        $this->collectionProcessor->process($searchCriteria, $collection);

        $collection->load();

        $searchResult = $this->searchResultsFactory->create();
        $searchResult
            ->setSearchCriteria($searchCriteria)
            ->setItems($collection->getItems())
            ->setTotalCount($collection->getSize());

        return $searchResult;
    }

    /**
     * Save schedule status object.
     *
     * @param \Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterface $scheduleStatus
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterface
     * @throws \Magento\Framework\Exception\StateException
     */
    public function save(ScheduleStatusInterface $scheduleStatus)
    {
        try {
            $this->scheduleStatusResource->save($scheduleStatus);
        } catch (AlreadyExistsException $e) {
            throw new StateException(__('Schedule status could not be saved!'));
        } catch (Exception $e) {
            throw new StateException(__('Schedule status could not be saved!'));
        }

        return $scheduleStatus;
    }

    /**
     * Delete schedule status object.
     *
     * @param \Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterface $scheduleStatus
     *
     * @return bool
     * @throws \Magento\Framework\Exception\StateException
     */
    public function delete(ScheduleStatusInterface $scheduleStatus)
    {
        try {
            $this->scheduleStatusResource->delete($scheduleStatus);
        } catch (Exception $e) {
            throw new StateException(__('Schedule status could not be deleted!'));
        }

        return true;
    }

    /**
     * Delete schedule status object by id.
     *
     * @param int $id
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById(int $id)
    {
        return $this->delete($this->get($id));
    }
}
