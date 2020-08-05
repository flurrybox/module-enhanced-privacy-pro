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

namespace Flurrybox\EnhancedPrivacyPro\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface ConsentInterface.
 *
 * @api
 * @since 1.0.0
 */
interface ConsentInterface extends ExtensibleDataInterface
{
    /**
     * Table name.
     */
    const TABLE = 'flurrybox_enhancedprivacypro_consents';

    /**#@+
     * Table column.
     */
    const ID = 'id';
    const CUSTOMER_ID = 'customer_id';
    const CODE = 'code';
    const IS_ALLOWED = 'is_allowed';
    /**#@-*/

    /**
     * Get id.
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get customer id.
     *
     * @return int|null
     */
    public function getCustomerId();

    /**
     * Get consent code.
     *
     * @return string|null
     */
    public function getCode();

    /**
     * Get is allowed.
     *
     * @return int
     */
    public function getIsAllowed();

    /**
     * Set customer id.
     *
     * @param int $id
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface
     */
    public function setCustomerId(int $id);

    /**
     * Set code.
     *
     * @param string $code
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface
     */
    public function setCode(string $code);

    /**
     * Set if consent is allowed.
     *
     * @param bool $isAllowed
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface
     */
    public function setIsAllowed(bool $isAllowed);

    /**
     * Get extension attributes.
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentExtensionInterface
     */
    public function getExtensionAttributes();

    /**
     * Set extension attributes.
     *
     * @param \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentExtensionInterface $extensionAttributes
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface
     */
    public function setExtensionAttributes(ConsentExtensionInterface $extensionAttributes);
}
