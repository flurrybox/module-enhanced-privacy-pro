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

namespace Flurrybox\EnhancedPrivacyPro\Plugin\Customer\CustomerData;

use Flurrybox\EnhancedPrivacyPro\Api\ConsentManagementInterface;
use Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface;
use Flurrybox\EnhancedPrivacyPro\Privacy\ConsentPool;
use Magento\Customer\CustomerData\Customer;
use Magento\Customer\Model\Session as CustomerSession;

/**
 * Add customer consents to customer section.
 */
class CustomerPlugin
{
    /**
     * @var CustomerSession
     */
    protected $session;

    /**
     * @var ConsentPool
     */
    protected $consentPool;

    /**
     * @var ConsentManagementInterface
     */
    protected $consentManagement;

    /**
     * Consents constructor.
     *
     * @param CustomerSession $session
     * @param ConsentPool $consentPool
     * @param ConsentManagementInterface $consentManagement
     */
    public function __construct(
        CustomerSession $session,
        ConsentPool $consentPool,
        ConsentManagementInterface $consentManagement
    ) {
        $this->session = $session;
        $this->consentPool = $consentPool;
        $this->consentManagement = $consentManagement;
    }

    /**
     * Get section data.
     *
     * @param Customer $subject
     * @param array $result
     *
     * @see \Magento\Customer\CustomerData\Customer::getSectionData()
     *
     * @return array
     */
    public function afterGetSectionData(Customer $subject, array $result)
    {
        $result['consents'] = [
            'codes' => [],
            'total' => 0
        ];

        if (!$this->session->getCustomerId()) {
            return $result;
        }

        $result['consents']['total'] = $this->consentPool->count();

        $customer = $this->session->getCustomerData();
        $customerConsents = $this->consentManagement->getConsents($customer);
        $consents = $this->consentPool->getConsents();

        if (empty($customerConsents)) {
            return $result;
        }

        $result['consents']['codes'] = array_values(array_map(function (ConsentInterface $consent) use ($consents) {
            if (!isset($consents[$consent->getCode()])) {
                return [];
            }

            return [
                'code' => $consent->getCode(),
                'isAllowed' => $consent->getIsAllowed()
            ];
        }, $customerConsents));

        return $result;
    }
}
