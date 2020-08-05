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

namespace Flurrybox\EnhancedPrivacyPro\Api;

/**
 * Interface UserManagementInterface.
 *
 * @api
 * @since 1.0.0
 */
interface UserManagementInterface
{
    /**
     * Lock unused administrator accounts.
     *
     * Locks all accounts which haven't been used for a defined time span.
     *
     * @return void
     */
    public function lockUnusedAccounts();
}
