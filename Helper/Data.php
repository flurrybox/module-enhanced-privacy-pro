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

namespace Flurrybox\EnhancedPrivacyPro\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Data.
 */
class Data extends AbstractHelper
{
    /**
     * Configuration paths.
     */
    const CONFIG_PRO_LOCK_ENABLED = 'enhancedprivacy/pro/lock/enabled';
    const CONFIG_PRO_LOCK_PRIMARY_ACCOUNT = 'enhancedprivacy/pro/lock/primary_account';
    const CONFIG_PRO_LOCK_TIME = 'enhancedprivacy/pro/lock/time';
    const CONFIG_ACCOUNT_DELETION_BEHAVIOUR = 'customer/enhancedprivacy/account/behaviour';

    /**
     * Table fields.
     */
    const SCHEDULE_STATUS = 'status';

    /**
     * Check if account lock is enabled.
     *
     * @return bool
     */
    public function isLockEnabled()
    {
        return (bool) $this->scopeConfig->getValue(self::CONFIG_PRO_LOCK_ENABLED);
    }

    /**
     * Get primary account id.
     *
     * @return int
     */
    public function getPrimaryAccountId()
    {
        return (int) $this->scopeConfig->getValue(self::CONFIG_PRO_LOCK_PRIMARY_ACCOUNT);
    }

    /**
     * Get lock time span in seconds.
     *
     * @return int
     */
    public function getLockTimeSpan()
    {
        return (int) $this->scopeConfig->getValue(self::CONFIG_PRO_LOCK_TIME) * 86400;
    }

    /**
     * Get customer account deletion behaviour.
     *
     * @return int
     */
    public function getCustomerDeletionBehaviour()
    {
        return (int) $this->scopeConfig->getValue(self::CONFIG_ACCOUNT_DELETION_BEHAVIOUR);
    }
}
