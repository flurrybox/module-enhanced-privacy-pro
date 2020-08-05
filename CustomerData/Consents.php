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

namespace Flurrybox\EnhancedPrivacyPro\CustomerData;

use Flurrybox\EnhancedPrivacyPro\Privacy\ConsentPool;
use Magento\Customer\CustomerData\SectionSourceInterface;

/**
 * Save consents in local storage.
 */
class Consents implements SectionSourceInterface
{
    /**
     * @var ConsentPool
     */
    protected $consentPool;

    /**
     * Consents constructor.
     *
     * @param ConsentPool $consentPool
     */
    public function __construct(ConsentPool $consentPool)
    {
        $this->consentPool = $consentPool;
    }

    /**
     * Get consent data.
     *
     * @return array
     */
    public function getSectionData()
    {
        $data = [];

        foreach ($this->consentPool->getConsents() as $consent) {
            $data[] = [
                'code' => $consent->getCode(),
                'name' => $consent->getName(),
                'description' => $consent->getDescription()
            ];
        }

        return $data;
    }
}
