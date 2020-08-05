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
use Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface;
use Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterfaceFactory;
use Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentSearchResultsInterfaceFactory;
use Flurrybox\EnhancedPrivacyPro\Api\ConsentRepositoryInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;

/**
 * Class ConsentRepository.
 *
 * @api
 * @since 1.0.0
 */
class ConsentRepository implements ConsentRepositoryInterface
{
    /**
     * @var ConsentInterfaceFactory
     */
    protected $consentFactory;

    /**
     * @var ResourceModel\Consent
     */
    protected $consentResource;

    /**
     * @var ResourceModel\Consent\CollectionFactory
     */
    protected $consentCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var ConsentSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * ConsentRepository constructor.
     *
     * @param ConsentInterfaceFactory $consentFactory
     * @param ResourceModel\Consent $consentResource
     * @param ResourceModel\Consent\CollectionFactory $consentCollectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ConsentSearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        ConsentInterfaceFactory $consentFactory,
        ResourceModel\Consent $consentResource,
        ResourceModel\Consent\CollectionFactory $consentCollectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        ConsentSearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->consentFactory = $consentFactory;
        $this->consentResource = $consentResource;
        $this->consentCollectionFactory = $consentCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * Get consent.
     *
     * @param int $id
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id)
    {
        $consent = $this->consentFactory->create();
        $this->consentResource->load($consent, $id);

        if (!$consent->getId()) {
            throw new NoSuchEntityException(__('Requested consent doesn\'t exist!'));
        }

        return $consent;
    }

    /**
     * Get scheduled status items that match specific criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->consentCollectionFactory->create();
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
     * Save consent object.
     *
     * @param \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface $consent
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface
     * @throws \Magento\Framework\Exception\StateException
     */
    public function save(ConsentInterface $consent)
    {
        try {
            $this->consentResource->save($consent);
        } catch (AlreadyExistsException $e) {
            throw new StateException(__('Consent could not be saved!'));
        } catch (Exception $e) {
            throw new StateException(__('Consent could not be saved!'));
        }

        return $consent;
    }

    /**
     * Delete consent object.
     *
     * @param \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface $consent
     *
     * @return bool
     * @throws \Magento\Framework\Exception\StateException
     */
    public function delete(ConsentInterface $consent)
    {
        try {
            $this->consentResource->delete($consent);
        } catch (Exception $e) {
            throw new StateException(__('Consent could not be deleted!'));
        }

        return true;
    }

    /**
     * Delete consent object by id.
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
