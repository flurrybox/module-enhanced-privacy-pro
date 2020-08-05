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

namespace Flurrybox\EnhancedPrivacyPro\Privacy\Export;

use Flurrybox\EnhancedPrivacy\Api\DataExportInterface;
use Flurrybox\EnhancedPrivacyPro\Api\ConsentManagementInterface;
use Flurrybox\EnhancedPrivacyPro\Privacy\ConsentPool;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Session as CustomerSession;

/**
 * Export customer consents.
 */
class Consents implements DataExportInterface
{
    /**
     * @var CustomerSession
     */
    protected $session;

    /**
     * @var ConsentManagementInterface
     */
    protected $consentManagement;

    /**
     * @var ConsentPool
     */
    protected $consentPool;

    /**
     * Consents constructor.
     *
     * @param CustomerSession $session
     * @param ConsentManagementInterface $consentManagement
     * @param ConsentPool $consentPool
     */
    public function __construct(
        CustomerSession $session,
        ConsentManagementInterface $consentManagement,
        ConsentPool $consentPool
    ) {
        $this->session = $session;
        $this->consentManagement = $consentManagement;
        $this->consentPool = $consentPool;
    }

    /**
     * Executed upon exporting customer data.
     *
     * Expected return structure:
     *      array(
     *          array('HEADER1', 'HEADER2', 'HEADER3', ...),
     *          array('VALUE1', 'VALUE2', 'VALUE3', ...),
     *          ...
     *      )
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     *
     * @return array
     */
    public function export(CustomerInterface $customer)
    {
        $data[] = ['CODE', 'NAME', 'DESCRIPTION', 'STATUS'];
        //$customer = $this->session->getCustomerData();
        $consentPool = $this->consentPool->getConsents();

        foreach ($this->consentManagement->getConsents($customer) as $consent) {
            $consentData = $consentPool[$consent->getCode()];

            $data[] = [
                $consent->getCode(),
                $consentData->getName(),
                $consentData->getDescription(),
                $consent->getIsAllowed() ? __('Allowed') : __('Disallowed')
            ];
        }

        return $data;
    }
}
