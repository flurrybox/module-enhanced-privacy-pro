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

namespace Flurrybox\EnhancedPrivacyPro\Controller\Adminhtml\Customer;

use Exception;
use Flurrybox\EnhancedPrivacy\Api\CustomerManagementInterface;
use Magento\Backend\App\Action;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Anonymize.
 */
class Anonymize extends Action
{
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var CustomerManagementInterface
     */
    protected $customerManagement;

    /**
     * Anonymize constructor.
     *
     * @param Action\Context $context
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerManagementInterface $customerManagement
     */
    public function __construct(
        Action\Context $context,
        CustomerRepositoryInterface $customerRepository,
        CustomerManagementInterface $customerManagement
    ) {
        parent::__construct($context);

        $this->customerRepository = $customerRepository;
        $this->customerManagement = $customerManagement;
    }

    /**
     * Perform customer anonymization.
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $customerId = $this->getRequest()->getParam('customer_id');
        $result = $this->resultRedirectFactory->create()->setPath('customer');

        if (!$customerId) {
            $this->messageManager->addErrorMessage(__('No customer id provided!'));

            return $result;
        }

        try {
            $this->customerManagement->anonymizeCustomer($this->customerRepository->getById($customerId));

            $this->messageManager->addSuccessMessage(__('Customer anonymized successfully!'));
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('Requested customer could not be found!'));
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__('Failed to anonymize customer!'));
        }

        return $result;
    }
}
