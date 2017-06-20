<?php
/**
 * Newsletter Repository Interface
 *
 * @category  WindsorCircle
 * @package   WindsorCircle_Integration
 * @author    Mark Hodge <mhodge@lyonscg.com>
 * @copyright Copyright (c) 2016 WindsorCircle (www.windsorcircle.com)
 */
namespace WindsorCircle\Integration\Api;

/**
 * Newsletter repository interface.
 *
 * @api
 */
interface NewsletterRepositoryInterface
{
    /**
     * Lists orders that match specified search criteria.
     *
     * @return \WindsorCircle\Integration\Api\Data\NewsletterSearchResultInterface Newsletter search result interface.
     */
    public function getList();

    /**
     * Get newsletter data by $email
     *
     * @param string $email
     * @param int|null $storeId
     * @return \WindsorCircle\Integration\Api\Data\NewsletterSearchResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($email, $storeId = null);
}
