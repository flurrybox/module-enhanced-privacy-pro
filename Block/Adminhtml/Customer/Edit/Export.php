<?php
namespace Flurrybox\EnhancedPrivacyPro\Block\Adminhtml\Customer\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Block\Adminhtml\Edit\GenericButton;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;
use Exception;


class Export extends GenericButton implements ButtonProviderInterface
{
    /**
     * @var \Magento\Framework\AuthorizationInterface
     */
    protected $_authorization;
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * Export constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        Context $context,
        Registry $registry,
        CustomerRepositoryInterface $customerRepository
    )
    {
        parent::__construct($context, $registry);
        $this->customerRepository = $customerRepository;
    }

    /**
     * Retrieve button-specified settings
     *
     * @return array
     */
    public function getButtonData()
    {
        $exportConfirmMsg = __('Are you sure you want to Export customer\'s data?');
        $data = [
            'label' => __('Export'),
            'class' => 'export-customer',
            'on_click' => 'deleteConfirm("' . $exportConfirmMsg . '", "' . $this->getExportUrl() . '")',
            'sort_order' => 30,
        ];

        return $data;
    }
    public function getExportUrl()
    {
        return $this->getUrl('enhancedprivacy/customer/export', ['customer_id' => $this->getCustomerId()]);
    }
}