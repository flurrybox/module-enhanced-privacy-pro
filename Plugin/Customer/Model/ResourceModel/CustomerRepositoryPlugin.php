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

namespace Flurrybox\EnhancedPrivacyPro\Plugin\Customer\Model\ResourceModel;

use Flurrybox\EnhancedPrivacyPro\Api\ConsentRepositoryInterface;
use Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchResultsInterface;

/**
 * Class CustomerRepositoryPlugin.
 */
class CustomerRepositoryPlugin
{
    /**
     * @var ConsentRepositoryInterface
     */
    protected $consentRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $criteriaBuilder;

    /**
     * @var ExtensionAttributesFactory
     */
    protected $extensionAttributesFactory;

    /**
     * CustomerRepositoryPlugin constructor.
     *
     * @param ConsentRepositoryInterface $consentRepository
     * @param SearchCriteriaBuilder $criteriaBuilder
     * @param ExtensionAttributesFactory $extensionAttributesFactory
     */
    public function __construct(
        ConsentRepositoryInterface $consentRepository,
        SearchCriteriaBuilder $criteriaBuilder,
        ExtensionAttributesFactory $extensionAttributesFactory
    ) {
        $this->consentRepository = $consentRepository;
        $this->criteriaBuilder = $criteriaBuilder;
        $this->extensionAttributesFactory = $extensionAttributesFactory;
    }

    /**
     * Load customer consents.
     *
     * @param CustomerRepositoryInterface $subject
     * @param CustomerInterface $entity
     *
     * @see \Magento\Customer\Api\CustomerRepositoryInterface::get()
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    public function afterGet(CustomerRepositoryInterface $subject, CustomerInterface $entity)
    {
        $criteria = $this->criteriaBuilder
            ->addFilter(ConsentInterface::CUSTOMER_ID, $entity->getId())
            ->create();
        $consents = $this->consentRepository->getList($criteria);

        $extensionAttributes = $entity->getExtensionAttributes();

        if ($extensionAttributes === null) {
            /** @var \Magento\Customer\Api\Data\CustomerExtensionInterface $extensionAttributes */
            $extensionAttributes = $this->extensionAttributesFactory->create(CustomerInterface::class);
        }

        $extensionAttributes->setConsents($consents->getItems());
        $entity->setExtensionAttributes($extensionAttributes);

        return $entity;
    }

    /**
     * Load customer consents.
     *
     * @param CustomerRepositoryInterface $subject
     * @param CustomerInterface $entity
     *
     * @see \Magento\Customer\Api\CustomerRepositoryInterface::getById()
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    public function afterGetById(CustomerRepositoryInterface $subject, CustomerInterface $entity)
    {
        return $this->afterGet($subject, $entity);
    }

    /**
     * Load customer consents on collection.
     *
     * @param CustomerRepositoryInterface $subject
     * @param SearchResultsInterface $entities
     *
     * @see \Magento\Customer\Api\CustomerRepositoryInterface::getList()
     *
     * @return SearchResultsInterface
     */
    public function afterGetList(CustomerRepositoryInterface $subject, SearchResultsInterface $entities)
    {
        foreach ($entities->getItems() as $entity) {
            $this->afterGet($subject, $entity);
        }

        return $entities;
    }

    /**
     * Save customer consents.
     *
     * @param CustomerRepositoryInterface $subject
     * @param CustomerInterface $entity
     *
     * @see \Magento\Customer\Api\CustomerRepositoryInterface::save()
     *
     * @return CustomerInterface
     * @throws \Magento\Framework\Exception\StateException
     */
    public function afterSave(CustomerRepositoryInterface $subject, CustomerInterface $entity)
    {
        $extensionAttributes = $entity->getExtensionAttributes();
        $consents = $extensionAttributes->getConsents();

        if (!$consents) {
            return $entity;
        }

        foreach ($consents as $consent) {
            $consent->setCustomerId((int) $entity->getId());

            $this->consentRepository->save($consent);
        }

        return $entity;
    }
}
