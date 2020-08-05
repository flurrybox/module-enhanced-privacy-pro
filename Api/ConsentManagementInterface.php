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

use Magento\Customer\Api\Data\CustomerInterface;

/**
 * Manage customer consents.
 *
 * @api
 * @since 1.0.0
 */
interface ConsentManagementInterface
{
    /**
     * Allow consent to certain service.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param string $code
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function allow(CustomerInterface $customer, string $code);

    /**
     * Mass allow consent to services.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param string[] $codes
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function massAllow(CustomerInterface $customer, array $codes);

    /**
     * Deny consent to certain service.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param string $code
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deny(CustomerInterface $customer, string $code);

    /**
     * Mass deny consent to services.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param string[] $codes
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function massDeny(CustomerInterface $customer, array $codes);

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
    public function update(CustomerInterface $customer, array $data);

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
    public function updateForLoggedInCustomer(array $data);

    /**
     * Check if customer has consent to service.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param string $code
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface|bool
     */
    public function hasConsent(CustomerInterface $customer, string $code);

    /**
     * Get customer consents.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface[]
     */
    public function getConsents(CustomerInterface $customer);
}
