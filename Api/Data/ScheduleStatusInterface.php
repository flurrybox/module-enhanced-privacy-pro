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

namespace Flurrybox\EnhancedPrivacyPro\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface ScheduleStatusInterface.
 *
 * @api
 * @since 1.0.0
 */
interface ScheduleStatusInterface extends ExtensibleDataInterface
{
    /**
     * Table name.
     */
    const TABLE = 'flurrybox_enhancedprivacypro_schedule_status';

    /**#@+
     * Table column.
     */
    const ID = 'id';
    const SCHEDULE_ID = 'schedule_id';
    const STATE = 'state';
    const APPROVED_AT = 'approved_at';
    const REVIEWER_ID = 'reviewer_id';
    /**#@-*/

    /**
     * #@+
     * Request state.
     */
    const STATE_UNAPPROVED = 0;
    const STATE_APPROVED = 1;
    /**#@-*/

    /**
     * Get status id.
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get request id.
     *
     * @return int|null
     */
    public function getScheduleId();

    /**
     * Get request state.
     *
     * @return int|null
     */
    public function getState();

    /**
     * Get timestamp when request was approved.
     *
     * @return string|null
     */
    public function getApprovedAt();

    /**
     * Get administrator id who approved request.
     *
     * @return int|null
     */
    public function getReviewerId();

    /**
     * Set schedule id.
     *
     * @param int $id
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterface
     */
    public function setScheduleId(int $id);

    /**
     * Set request state.
     *
     * @param int $state
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterface
     */
    public function setState(int $state);

    /**
     * Set timestamp when request was approved.
     *
     * @param string $time
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterface
     */
    public function setApprovedAt(string $time);

    /**
     * Set reviewer id.
     *
     * @param int $id
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterface
     */
    public function setReviewerId(int $id);

    /**
     * Get extension attributes.
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusExtensionInterface
     */
    public function getExtensionAttributes();

    /**
     * Set extension attributes.
     *
     * @param \Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusExtensionInterface $extensionAttributes
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterface
     */
    public function setExtensionAttributes(ScheduleStatusExtensionInterface $extensionAttributes);
}
