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

namespace Flurrybox\EnhancedPrivacyPro\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Behaviour.
 */
class Behaviour implements ArrayInterface
{
    /**
     * Behaviours.
     */
    const MANUAL = 0;
    const AUTOMATIC = 1;

    /**
     * Get behaviours.
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::AUTOMATIC, 'label' => __('Automatic')],
            ['value' => self::MANUAL, 'label' => __('Manual')]
        ];
    }
}
