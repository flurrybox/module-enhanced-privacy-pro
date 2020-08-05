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

use Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentExtensionInterface;
use Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * Class Consent.
 *
 * @api
 * @since 1.0.0
 */
class Consent extends AbstractExtensibleModel implements ConsentInterface
{
    /**
     * Initialize resource.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Consent::class);
    }

    /**
     * Get customer id.
     *
     * @return int|null
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * Get consent code.
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->getData(self::CODE);
    }

    /**
     * Get is allowed.
     *
     * @return int
     */
    public function getIsAllowed()
    {
        return $this->getData(self::IS_ALLOWED);
    }

    /**
     * Set customer id.
     *
     * @param int $id
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface
     */
    public function setCustomerId(int $id)
    {
        $this->setData(self::CUSTOMER_ID, $id);

        return $this;
    }

    /**
     * Set code.
     *
     * @param string $code
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface
     */
    public function setCode(string $code)
    {
        $this->setData(self::CODE, $code);

        return $this;
    }

    /**
     * Set if consent is allowed.
     *
     * @param bool $isAllowed
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface
     */
    public function setIsAllowed(bool $isAllowed)
    {
        $this->setData(self::IS_ALLOWED, $isAllowed);

        return $this;
    }

    /**
     * Get extension attributes.
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentExtensionInterface
     */
    public function getExtensionAttributes()
    {
        $extensionAttributes = $this->_getExtensionAttributes();

        if ($extensionAttributes === null) {
            /** @var \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentExtensionInterface $extensionAttributes */
            $extensionAttributes = $this->extensionAttributesFactory->create(ConsentInterface::class);
            $this->setExtensionAttributes($extensionAttributes);
        }

        return $extensionAttributes;
    }

    /**
     * Set extension attributes.
     *
     * @param \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentExtensionInterface $extensionAttributes
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface
     */
    public function setExtensionAttributes(ConsentExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
