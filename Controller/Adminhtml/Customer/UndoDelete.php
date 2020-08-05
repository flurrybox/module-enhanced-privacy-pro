<?php
namespace Flurrybox\EnhancedPrivacyPro\Controller\Adminhtml\Customer;

use Exception;
use Flurrybox\EnhancedPrivacy\Api\CustomerManagementInterface;
use Flurrybox\EnhancedPrivacy\Helper\Data as PrivacyHelper;
use Magento\Customer\Controller\AbstractAccount;
use Magento\Backend\App\Action;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;


class UndoDelete extends Action{
    /**
     * @var PrivacyHelper
     */
    protected $privacyHelper;

    /**
     * @var CustomerManagementInterface
     */
    protected $customerManagement;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * UndoDelete constructor.
     *
     * @param Context $context
     * @param PrivacyHelper $privacyHelper
     * @param CustomerSession $customerSession
     * @param CustomerManagementInterface $customerManagement
     */
    public function __construct(
        Action\Context $context,
        PrivacyHelper $privacyHelper,
        CustomerManagementInterface $customerManagement,
        CustomerRepositoryInterface $customerRepository

    ) {
        parent::__construct($context);
        $this->privacyHelper = $privacyHelper;
        $this->customerRepository = $customerRepository;
        $this->customerManagement = $customerManagement;
    }

    /**
     * Dispatch controller.
     *
     * @param RequestInterface $request
     *
     * @return \Magento\Framework\App\ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function dispatch(RequestInterface $request)
    {
        try {
            $customer = $this->customerRepository->getById($this->getRequest()->getParam('customer_id'));
            if (
                !$this->privacyHelper->isModuleEnabled() &&
                !$this->privacyHelper->isAccountDeletionEnabled() &&
                !$this->customerManagement->isCustomerToBeDeleted($customer)
            ) {
                $this->_forward('noroute');
            }
        } catch (Exception $e) {
            $this->_forward('noroute');
        }

        return parent::dispatch($request);
    }

    /**
     * Execute action.
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\SessionException
     */
    public function execute()
    {
        try {
            $customer = $this->customerRepository->getById($this->getRequest()->getParam('customer_id'));
            $this->customerManagement->cancelCustomerDeletion($customer);
            $this->messageManager->addSuccessMessage(__('Your account deletion has been canceled!'));
        } catch (Exception $e) {
            $this->messageManager->addWarningMessage(__('Something went wrong, please try again later!'));
        }
        return $result = $this->resultRedirectFactory->create()->setPath('customer');
    }
}