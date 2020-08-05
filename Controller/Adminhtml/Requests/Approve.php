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

namespace Flurrybox\EnhancedPrivacyPro\Controller\Adminhtml\Requests;

use Flurrybox\EnhancedPrivacy\Api\ScheduleRepositoryInterface;
use Flurrybox\EnhancedPrivacyPro\Api\ScheduleManagementInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Approve schedule request action.
 */
class Approve extends Action
{
    /**
     * ACL resource.
     */
    const ADMIN_RESOURCE = 'Flurrybox_EnhancedPrivacy::enhancedprivacy';

    /**
     * @var ScheduleRepositoryInterface
     */
    protected $scheduleRepository;

    /**
     * @var ScheduleManagementInterface
     */
    protected $scheduleManagement;

    /**
     * Approve constructor.
     *
     * @param Action\Context $context
     * @param ScheduleRepositoryInterface $scheduleRepository
     * @param ScheduleManagementInterface $scheduleManagement
     */
    public function __construct(
        Action\Context $context,
        ScheduleRepositoryInterface $scheduleRepository,
        ScheduleManagementInterface $scheduleManagement
    ) {
        parent::__construct($context);

        $this->scheduleRepository = $scheduleRepository;
        $this->scheduleManagement = $scheduleManagement;
    }

    /**
     * Execute action.
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $redirectResult = $this->resultRedirectFactory->create()->setPath('*/*');
        $id = $this->getRequest()->getParam('id');

        if (!$id) {
            return $redirectResult;
        }

        try {
            $this->scheduleManagement->approveRequest($this->scheduleRepository->get((int) $id));

            $this->messageManager->addSuccessMessage(__('Request approved successfully'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage(__('Failed to approve request'));
        }

        return $redirectResult;
    }
}
