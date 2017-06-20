<?php
/**
 * Newsletter search result interface
 *
 * @category  WindsorCircle
 * @package   WindsorCircle_Integration
 * @author    Mark Hodge <mhodge@lyonscg.com>
 * @copyright Copyright (c) 2016 WindsorCircle (www.windsorcircle.com)
 */
namespace WindsorCircle\Integration\Api\Data;

/**
 * Newsletter search result interface.
 *
 * @api
 */
interface NewsletterSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get items.
     *
     * @return \WindsorCircle\Integration\Api\Data\NewsletterInterface[] Array of collection items.
     */
    public function getItems();
}
