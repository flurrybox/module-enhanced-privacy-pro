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

namespace Flurrybox\EnhancedPrivacyPro\Cron;

use Flurrybox\EnhancedPrivacyPro\Api\UserManagementInterface;
use Flurrybox\EnhancedPrivacyPro\Helper\Data;

/**
 * Class LockUnusedAccounts.
 */
class LockUnusedAccounts
{
    /**
     * @var UserManagementInterface
     */
    protected $userManagement;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * LockUnusedAccounts constructor.
     *
     * @param UserManagementInterface $userManagement
     * @param Data $helper
     */
    public function __construct(UserManagementInterface $userManagement, Data $helper)
    {
        $this->userManagement = $userManagement;
        $this->helper = $helper;
    }

    /**
     * Process unused account lock down.
     *
     * @return void
     */
    public function execute()
    {
        if (!$this->helper->isLockEnabled()) {
            return;
        }

        $this->userManagement->lockUnusedAccounts();
    }
}
