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
 * Privacy consent implementation.
 *
 * @api
 * @since 1.0.0
 */
class Consent implements ConsentInterface
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * Consent constructor.
     *
     * @param string $code
     * @param string $name
     * @param string $description
     */
    public function __construct(string $code, string $name, string $description)
    {
        $this->code = $code;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * Get consent code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get consent name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get consent description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
