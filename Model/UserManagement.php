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

use Flurrybox\EnhancedPrivacyPro\Api\UserManagementInterface;
use Flurrybox\EnhancedPrivacyPro\Helper\Data;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\User\Model\ResourceModel\User;
use Magento\User\Model\ResourceModel\User\CollectionFactory as UserCollectionFactory;

/**
 * Class UserManagement.
 */
class UserManagement implements UserManagementInterface
{
    /**
     * Max int value (32 bit)
     */
    const MAX_INT = 2147483647;

    /**
     * @var UserCollectionFactory
     */
    protected $userCollectionFactory;

    /**
     * @var User
     */
    protected $userResource;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * LockUnusedAccounts constructor.
     *
     * @param UserCollectionFactory $userCollectionFactory
     * @param User $userResource
     * @param Data $helper
     * @param DateTime $dateTime
     */
    public function __construct(
        UserCollectionFactory $userCollectionFactory,
        User $userResource,
        Data $helper,
        DateTime $dateTime
    ) {
        $this->userCollectionFactory = $userCollectionFactory;
        $this->userResource = $userResource;
        $this->helper = $helper;
        $this->dateTime = $dateTime;
    }

    /**
     * Lock unused administrator accounts.
     *
     * Locks all accounts that haven't been used for a defined time span.
     *
     * @return void
     */
    public function lockUnusedAccounts()
    {
        $time = $this->dateTime->gmtTimestamp();
        $collection = $this->userCollectionFactory->create();
        $collection
            ->addFieldToFilter('logdate', ['lteq' => date('Y-m-d H:i:s', $time - $this->helper->getLockTimeSpan())])
            ->addFieldToFilter(
                ['lock_expires', 'lock_expires'],
                [
                    ['null' => true],
                    ['lteq' => date('Y-m-d H:i:s', $time)]
                ]
            );

        if (!$collection->count()) {
            return;
        }

        $userIds = [];

        foreach ($collection->getItems() as $item) {
            /** @var \Magento\User\Model\User $item */
            $userIds[] = $item->getId();
        }

        $this->userResource
            ->lock($userIds, $this->helper->getPrimaryAccountId(), static::MAX_INT - $this->dateTime->gmtTimestamp());
    }
}
