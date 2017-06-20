<?php
/**
 * Order Item interface
 *
 * @category  WindsorCircle
 * @package   WindsorCircle_Integration
 * @author    Mark Hodge <mhodge@lyonscg.com>
 * @copyright Copyright (c) 2016 WindsorCircle (www.windsorcircle.com)
 */
namespace WindsorCircle\Integration\Api\Data;

use Magento\Sales\Api\Data\OrderItemInterface as SalesOrderItemInterface;

/**
 * Order item interface.
 *
 * @api
 */
interface OrderItemInterface
{
    /**
     * Get Parent Sku of Item
     *
     * @return string
     */
    public function getParentSku();
}
