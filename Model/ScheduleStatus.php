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

namespace Flurrybox\EnhancedPrivacyPro\Model;

use Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusExtensionInterface;
use Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * Schedule status entity model.
 *
 * @api
 * @since 1.0.0
 */
class ScheduleStatus extends AbstractExtensibleModel implements ScheduleStatusInterface
{
    /**
     * Initialize resource.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\ScheduleStatus::class);
    }

    /**
     * Get request id.
     *
     * @return int|null
     */
    public function getScheduleId()
    {
        return $this->getData(self::SCHEDULE_ID);
    }

    /**
     * Get request state.
     *
     * @return int|null
     */
    public function getState()
    {
        return $this->getData(self::STATE);
    }

    /**
     * Get timestamp when request was approved.
     *
     * @return string|null
     */
    public function getApprovedAt()
    {
        return $this->getData(self::APPROVED_AT);
    }

    /**
     * Get administrator id who approved request.
     *
     * @return int|null
     */
    public function getReviewerId()
    {
        return $this->getData(self::REVIEWER_ID);
    }

    /**
     * Set schedule id.
     *
     * @param int $id
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterface
     */
    public function setScheduleId(int $id)
    {
        $this->setData(self::SCHEDULE_ID, $id);

        return $this;
    }

    /**
     * Set request state.
     *
     * @param int $state
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterface
     */
    public function setState(int $state)
    {
        $this->setData(self::STATE, $state);

        return $this;
    }

    /**
     * Set timestamp when request was approved.
     *
     * @param string $time
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterface
     */
    public function setApprovedAt(string $time)
    {
        $this->setData(self::APPROVED_AT, $time);

        return $this;
    }

    /**
     * Set reviewer id.
     *
     * @param int $id
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterface
     */
    public function setReviewerId(int $id)
    {
        $this->setData(self::REVIEWER_ID, $id);

        return $this;
    }

    /**
     * Get extension attributes.
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusExtensionInterface
     */
    public function getExtensionAttributes()
    {
        $extensionAttributes = $this->_getExtensionAttributes();

        if ($extensionAttributes === null) {
            /** @var \Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusExtensionInterface $extensionAttributes */
            $extensionAttributes = $this->extensionAttributesFactory->create(ScheduleStatusInterface::class);
            $this->setExtensionAttributes($extensionAttributes);
        }

        return $extensionAttributes;
    }

    /**
     * Set extension attributes.
     *
     * @param \Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusExtensionInterface $extensionAttributes
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ScheduleStatusInterface
     */
    public function setExtensionAttributes(ScheduleStatusExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
