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

use Flurrybox\EnhancedPrivacyPro\Api\ConsentManagementInterface;
use Flurrybox\EnhancedPrivacyPro\Api\ConsentRepositoryInterface;
use Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface;
use Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterfaceFactory;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\StateException;

/**
 * Manage customer consents.
 *
 * @api
 * @since 1.0.0
 */
class ConsentManagement implements ConsentManagementInterface
{
    /**
     * @var ConsentRepositoryInterface
     */
    protected $consentRepository;

    /**
     * @var ConsentInterfaceFactory
     */
    protected $consentFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $criteriaBuilder;

    /**
     * @var CustomerSession
     */
    protected $session;

    /**
     * @var array
     */
    protected $consents = [];

    /**
     * ConsentManagement constructor.
     *
     * @param ConsentRepositoryInterface $consentRepository
     * @param ConsentInterfaceFactory $consentFactory
     * @param SearchCriteriaBuilder $criteriaBuilder
     * @param CustomerSession $session
     */
    public function __construct(
        ConsentRepositoryInterface $consentRepository,
        ConsentInterfaceFactory $consentFactory,
        SearchCriteriaBuilder $criteriaBuilder,
        CustomerSession $session
    ) {
        $this->consentRepository = $consentRepository;
        $this->consentFactory = $consentFactory;
        $this->criteriaBuilder = $criteriaBuilder;
        $this->session = $session;
    }

    /**
     * Allow consent to certain service.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param string $code
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function allow(CustomerInterface $customer, string $code)
    {
        try {
            $this->consentRepository->save($this->getConsent($customer, $code, true));
        } catch (StateException $e) {
            throw new LocalizedException(__('Failed to allow \'%1\' consent', $code));
        }

        return true;
    }

    /**
     * Mass allow consent to services.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param string[] $codes
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function massAllow(CustomerInterface $customer, array $codes)
    {
        foreach ($codes as $code) {
            $this->allow($customer, $code);
        }

        return true;
    }

    /**
     * Deny consent to certain service.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param string $code
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deny(CustomerInterface $customer, string $code)
    {
        try {
            $this->consentRepository->save($this->getConsent($customer, $code, false));
        } catch (StateException $e) {
            throw new LocalizedException(__('Failed to deny \'%1\' consent', $code));
        }

        return true;
    }

    /**
     * Mass deny consent to services.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param string[] $codes
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function massDeny(CustomerInterface $customer, array $codes)
    {
        foreach ($codes as $code) {
            $this->deny($customer, $code);
        }

        return true;
    }

    /**
     * Update customer consents.
     *
     * Usage: $data = [
     *     'code' => true,
     *     'another-code' => false
     * ]
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param string[] $data
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function update(CustomerInterface $customer, array $data)
    {
        try {
            foreach ($data as $code => $isAllowed) {
                $this->consentRepository->save($this->getConsent($customer, $code, (bool) $isAllowed));
            }
        } catch (StateException $e) {
            throw new LocalizedException(__('Failed to update customer consents'));
        }

        return true;
    }

    /**
     * Update logged in customer consents.
     *
     * Usage: $data = [
     *     'code' => true,
     *     'another-code' => false
     * ]
     *
     * @param string[] $data
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function updateForLoggedInCustomer(array $data)
    {
        return $this->update($this->session->getCustomerData(), $data);
    }

    /**
     * Check if customer has consent to service.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param string $code
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface|bool
     */
    public function hasConsent(CustomerInterface $customer, string $code)
    {
        if (isset($this->consents[$customer->getId()][$code])) {
            return $this->consents[$customer->getId()][$code];
        }

        foreach ($this->getConsents($customer) as $consent) {
            if ($consent->getCode() === $code) {
                $this->consents[$customer->getId()][$code] = $consent;

                return $consent;
            }
        }

        return false;
    }

    /**
     * Get customer consents.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface[]
     */
    public function getConsents(CustomerInterface $customer)
    {
        $extensionAttributes = $customer->getExtensionAttributes();

        if ($extensionAttributes !== null) {
            return $extensionAttributes->getConsents() ?? [];
        }

        $criteria = $this->criteriaBuilder
            ->addFilter(ConsentInterface::CUSTOMER_ID, $customer->getId())
            ->create();
        $consents = $this->consentRepository->getList($criteria);

        if ($consents->getTotalCount()) {
            return $consents->getItems();
        }

        return [];
    }

    /**
     * Load or create consent.
     *
     * @param CustomerInterface $customer
     * @param string $code
     * @param bool $isAllowed
     *
     * @return ConsentInterface
     */
    protected function getConsent(CustomerInterface $customer, string $code, bool $isAllowed = false)
    {
        if ($entity = $this->hasConsent($customer, $code)) {
            return $entity->setIsAllowed($isAllowed);
        }

        return $this->createConsent($code, (int) $customer->getId(), $isAllowed);
    }

    /**
     * Create new consent model.
     *
     * @param string $code
     * @param int $customerId
     * @param bool $isAllowed
     *
     * @return ConsentInterface
     */
    protected function createConsent(string $code, int $customerId, bool $isAllowed = false)
    {
        return $this->consentFactory
            ->create()
            ->setCustomerId($customerId)
            ->setCode($code)
            ->setIsAllowed($isAllowed);
    }
}
