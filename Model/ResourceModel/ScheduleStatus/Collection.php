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

namespace Flurrybox\EnhancedPrivacyPro\Model\ResourceModel\ScheduleStatus;

use Flurrybox\EnhancedPrivacyPro\Model\ScheduleStatus;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Schedule status collection model.
 */
class Collection extends AbstractCollection
{
    /**
     * Initialize resource.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ScheduleStatus::class, \Flurrybox\EnhancedPrivacyPro\Model\ResourceModel\ScheduleStatus::class);
    }
}
