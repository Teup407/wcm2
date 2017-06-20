<?php
/**
 * Plugin for the OrderSearchResultInterfaceFactory
 *
 * @category  WindsorCircle
 * @package   WindsorCircle_Integration
 * @author    Mark Hodge <mhodge@lyonscg.com>
 * @copyright Copyright (c) 2016 WindsorCircle (www.windsorcircle.com)
 */

namespace WindsorCircle\Integration\Model\Plugin\Order;

class Search
{
    /**
     * Order Search Results Interface Factory plugin around create
     * for joining the customer_group_code to the OrderSearchResultInterface
     *
     * @param \Magento\Sales\Api\Data\OrderSearchResultInterfaceFactory $subject
     * @param \Closure $proceed
     * @return mixed
     */
    public function aroundCreate($subject, \Closure $proceed)
    {
        /** @var \Magento\Sales\Model\ResourceModel\Order\Collection $search */
        $search = $proceed();

        $search->getSelect()->joinLeft(
            'customer_group',
            'customer_group.customer_group_id = main_table.customer_group_id',
            'customer_group_code'
        );

        return $search;
    }
}
