<?php
/**
 * Order interface
 *
 * @category  WindsorCircle
 * @package   WindsorCircle_Integration
 * @author    Mark Hodge <mhodge@lyonscg.com>
 * @copyright Copyright (c) 2016 WindsorCircle (www.windsorcircle.com)
 */
namespace WindsorCircle\Integration\Api\Data;

/**
 * Order interface.
 *
 * An order is a document that a web store issues to a customer. Magento generates a sales order that lists the product
 * items, billing and shipping addresses, and shipping and payment methods. A corresponding external document, known as
 * a purchase order, is emailed to the customer.
 * @api
 */
interface OrderInterface
{
    /**
     * Canceled Order
     */
    const CANCELED = 'canceled';

    /**
     * Customer Email Option
     */
    const CUSTOMER_EMAIL_OPT = 'customer_email_option';

    /*
     * Product Total.
     */
    const PRODUCT_TOTAL = 'product_total';

    /**
     * Customer Group Code
     */
    const CUSTOMER_GROUP_CODE = 'customer_group_code';

    /*
     * Shipping address ID.
     */
    const SHIPPING_ADDRESS_ID = 'shipping_address_id';

    /**
     * Items Constant
     */
    const ITEMS = 'items';

    /**
     * Address Constant
     */
    const ADDRESS = 'address';

    /**
     * Get Order Date
     *
     * @return string
     */
    public function getOrderDate();

    /**
     * Get Order Time
     *
     * @return string
     */
    public function getOrderTime();

    /**
     * Gets the order cancel state
     *
     * @return bool Order canceled
     */
    public function getCanceled();

    /**
     * Get the product total for the order
     *
     * @return float|null Product Total for Order - Subtotal minus DiscountAmount.
     */
    public function getProductTotal();

    /**
     * Customer Name (firstname lastname)
     *
     * @return string
     */
    public function getCustName();

    /**
     * Get Customer Group Name (customer_group_code)
     *
     * @return string
     */
    public function getCustomerGroupName();

    /**
     * Gets the shipping address ID for the order.
     *
     * @return int|null Billing address ID.
     */
    public function getShippingAddressId();

//    /**
//     * Gets the shipping address, if any, for the order.
//     *
//     * @return \Magento\Sales\Api\Data\OrderAddressInterface|null Shipping address. Otherwise, null.
//     */
//    public function getShippingAddress();
}
