<?php

namespace Flurrybox\EnhancedPrivacyPro\Controller\Adminhtml\Customer;

use Magento\Backend\App\Action;
use Exception;
use Flurrybox\EnhancedPrivacy\Api\CustomerManagementInterface;
use Flurrybox\EnhancedPrivacy\Api\Data\ReasonInterfaceFactory;
use Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterfaceFactory;
use Flurrybox\EnhancedPrivacy\Api\ReasonRepositoryInterface;
use Flurrybox\EnhancedPrivacy\Api\ScheduleRepositoryInterface;
use Flurrybox\EnhancedPrivacy\Helper\Data as PrivacyHelper;
use Magento\Customer\Controller\AbstractAccount;
use Magento\Customer\Model\AuthenticationInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\InvalidEmailOrPasswordException;
use Magento\Framework\Exception\State\UserLockedException;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Customer\Api\CustomerRepositoryInterface;

class SoftDelete extends Action
{
    /**
     * @var PrivacyHelper
     */
    protected $privacyHelper;

    /**
     * @var CustomerManagementInterface
     */
    protected $customerManagement;

    /**
     * @var Validator
     */
    protected $formKeyValidator;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepositoryInterface;

    /**
     * @var AuthenticationInterface
     */
    protected $authentication;

    /**
     * @var ScheduleInterfaceFactory
     */
    protected $scheduleFactory;

    /**
     * @var ScheduleRepositoryInterface
     */
    protected $scheduleRepository;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var ReasonInterfaceFactory
     */
    protected $reasonFactory;

    /**
     * @var ReasonRepositoryInterface
     */
    protected $reasonRepository;

    public function __construct(
        Action\Context $context,
        PrivacyHelper $privacyHelper,
        CustomerManagementInterface $customerManagement,
        Validator $formKeyValidator,
        AuthenticationInterface $authentication,
        ScheduleInterfaceFactory $scheduleFactory,
        ScheduleRepositoryInterface $scheduleRepository,
        ReasonInterfaceFactory $reasonFactory,
        ReasonRepositoryInterface $reasonRepository,
        DateTime $dateTime,
        CustomerRepositoryInterface $customerRepositoryInterface
    ) {
        parent::__construct($context);
        $this->privacyHelper = $privacyHelper;
        $this->customerManagement = $customerManagement;
        $this->formKeyValidator = $formKeyValidator;
        $this->authentication = $authentication;
        $this->scheduleFactory = $scheduleFactory;
        $this->scheduleRepository = $scheduleRepository;
        $this->reasonFactory = $reasonFactory;
        $this->reasonRepository = $reasonRepository;
        $this->dateTime = $dateTime;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
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
            $customer = $this->customerRepositoryInterface->getById($this->getRequest()->getParam('customer_id'));
            if (
                !$this->privacyHelper->isModuleEnabled() ||
                !$this->privacyHelper->isAccountDeletionEnabled() ||
                $this->customerManagement->isCustomerToBeDeleted($customer)
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
            $customer = $this->customerRepositoryInterface->getById($this->getRequest()->getParam('customer_id'));
            $schedule = $this->scheduleFactory->create();
            $schedule
                ->setScheduledAt(
                    date('Y-m-d H:i:s', $this->dateTime->gmtTimestamp() + $this->privacyHelper->getDeletionTime())
                )
                ->setCustomerId((int) $customer->getId())
                ->setType($this->privacyHelper->getDeletionType($customer));

            $this->_eventManager->dispatch('enhancedprivacy_submit_delete_request', ['schedule' => $schedule]);

            $this->scheduleRepository->save($schedule);

            $this->messageManager->addWarningMessage($this->privacyHelper->getSuccessMessage());
        } catch (InvalidEmailOrPasswordException $e) {
            $this->messageManager->addErrorMessage(__('Password you typed does not match this account!'));
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong, please try again later!'));
        }

        return $result = $this->resultRedirectFactory->create()->setPath('customer');
    }
}