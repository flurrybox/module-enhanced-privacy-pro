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

namespace Flurrybox\EnhancedPrivacyPro\Api;

use Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface ConsentRepositoryInterface.
 *
 * @api
 * @since 1.0.0
 */
interface ConsentRepositoryInterface
{
    /**
     * Get consent.
     *
     * @param int $id
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id);

    /**
     * Get scheduled status items that match specific criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Save consent object.
     *
     * @param \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface $scheduleStatus
     *
     * @return \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface
     * @throws \Magento\Framework\Exception\StateException
     */
    public function save(ConsentInterface $scheduleStatus);

    /**
     * Delete consent object.
     *
     * @param \Flurrybox\EnhancedPrivacyPro\Api\Data\ConsentInterface $scheduleStatus
     *
     * @return bool
     * @throws \Magento\Framework\Exception\StateException
     */
    public function delete(ConsentInterface $scheduleStatus);

    /**
     * Delete consent object by id.
     *
     * @param int $id
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById(int $id);
}
