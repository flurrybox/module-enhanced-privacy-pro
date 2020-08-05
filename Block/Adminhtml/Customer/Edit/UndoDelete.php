<?php

namespace Flurrybox\EnhancedPrivacyPro\Block\Adminhtml\Customer\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Block\Adminhtml\Edit\GenericButton;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;
use Flurrybox\EnhancedPrivacy\Api\CustomerManagementInterface;
use Exception;


class UndoDelete extends GenericButton implements ButtonProviderInterface
{
    /**
     * @var CustomerManagementInterface
     */
    protected $customerManagement;

    /**
     * @var \Magento\Framework\AuthorizationInterface
     */
    protected $_authorization;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * SoftDelete constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        Context $context,
        Registry $registry,
        CustomerRepositoryInterface $customerRepository,
        CustomerManagementInterface $customerManagement
    ) {
        parent::__construct($context, $registry);
        $this->customerRepository = $customerRepository;
        $this->customerManagement = $customerManagement;
    }

    /**
     * Retrieve button-specified settings
     *
     * @return array
     */
    public function getButtonData()
    {
        if($this->isAccountToBeDeleted()){
            $undodeleteConfirmMsg = __('You are going to undo deletion of the customer\'s data?');
            $data = [
                'label' => __('Undo Delete'),
                'class' => 'undodelete-customer',
                'on_click' => 'deleteConfirm("' . $undodeleteConfirmMsg . '", "' . $this->getUndoDeleteUrl() . '")',
                'sort_order' => 30,
            ];
            return $data;
        }
        return null;

    }

    public function getUndoDeleteUrl()
    {
        return $this->getUrl('enhancedprivacy/customer/undodelete', ['customer_id' => $this->getCustomerId()]);
    }

    public function isAccountToBeDeleted()
    {
        $customerId = $this->getCustomerId();
        if(!$customer = $this->customerRepository->getById($customerId)){
            return false;
        }
        return $this->customerManagement->isCustomerToBeDeleted($this->customerRepository->getById($customerId));
    }
}