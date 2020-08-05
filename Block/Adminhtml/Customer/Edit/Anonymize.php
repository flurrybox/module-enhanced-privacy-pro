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

namespace Flurrybox\EnhancedPrivacyPro\Block\Adminhtml\Customer\Edit;

use Exception;
use Flurrybox\EnhancedPrivacy\Api\Data\CustomerManagementInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Block\Adminhtml\Edit\GenericButton;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class Anonymize.
 */
class Anonymize extends GenericButton implements ButtonProviderInterface
{
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * Anonymize constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        Context $context,
        Registry $registry,
        CustomerRepositoryInterface $customerRepository
    ) {
        parent::__construct($context, $registry);

        $this->customerRepository = $customerRepository;
    }

    /**
     * Get anonymize button data.
     *
     * @return array
     */
    public function getButtonData()
    {
        $customerId = $this->getCustomerId();
        $data = [];

        if (!$customerId) {
            return $data;
        }

        /**
         * Show Anonymize button only if customer has not been anonymized yet.
         */
        try {
            $customer = $this->customerRepository->getById($customerId);
            $attribute = $customer->getCustomAttribute(CustomerManagementInterface::ATTRIBUTE_IS_ANONYMIZED);

            if ($attribute && (int) $attribute->getValue() === 1) {
                return $data;
            }
        } catch (Exception $e) {
            return $data;
        }

        $deleteConfirmMsg = __('Are you sure you want to do this?');
        $data = [
            'label' => __('Anonymize Customer'),
            'class' => 'anonymize-customer',
            'on_click' => 'deleteConfirm("' . $deleteConfirmMsg . '", "' . $this->getAnonymizeUrl() . '")',
            'sort_order' => 25,
        ];

        return $data;
    }

    /**
     * Get anonymization URL.
     *
     * @return string
     */
    public function getAnonymizeUrl()
    {
        return $this->getUrl('enhancedprivacy/customer/anonymize', ['customer_id' => $this->getCustomerId()]);
    }
}
