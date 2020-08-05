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

namespace Flurrybox\EnhancedPrivacyPro\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Magento\User\Model\User;
use Magento\User\Model\ResourceModel\User\CollectionFactory as UserCollectionFactory;

/**
 * Class Accounts.
 */
class Accounts implements ArrayInterface
{
    /**
     * @var UserCollectionFactory
     */
    protected $userCollectionFactory;

    /**
     * Accounts constructor.
     *
     * @param UserCollectionFactory $userCollectionFactory
     */
    public function __construct(UserCollectionFactory $userCollectionFactory)
    {
        $this->userCollectionFactory = $userCollectionFactory;
    }

    /**
     * Get administrator accounts.
     *
     * @return array
     */
    public function toOptionArray()
    {
        $collection = $this->userCollectionFactory->create();

        return array_map(function(User $user) {
            return ['value' => $user->getId(), 'label' => sprintf('%s (%s)', $user->getName(), $user->getUserName())];
        }, $collection->getItems());
    }
}
