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

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface ConsentSearchResultsInterface.
 *
 * @api
 * @since 1.0.0
 */
interface ConsentSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get schedule list.
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface[]
     */
    public function getItems();

    /**
     * Set reasons.
     *
     * @param \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface[] $items
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentSearchResultsInterface
     */
    public function setItems(array $items);
}
