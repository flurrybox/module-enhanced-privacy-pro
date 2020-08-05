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

namespace Flurrybox\EnhancedPrivacyPro\Privacy;

/**
 * Consent pool.
 */
class ConsentPool
{
    /**
     * @var ConsentInterface[]
     */
    protected $consents;

    /**
     * ConsentPool constructor.
     *
     * @param ConsentInterface[] $consents
     */
    public function __construct(array $consents = [])
    {
        $this->consents = $consents;
    }

    /**
     * Get consents.
     *
     * @return ConsentInterface[]
     */
    public function getConsents()
    {
        foreach ($this->consents as $id => $consent) {
            if (!$consent instanceof ConsentInterface) {
                unset($this->consents[$id]);
            }
        }

        return $this->consents;
    }

    /**
     * Get count of consent instances.
     *
     * @return int
     */
    public function count()
    {
        return count($this->consents);
    }
}
