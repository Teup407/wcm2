<?php
/**
 * Newsletter interface
 *
 * @category  WindsorCircle
 * @package   WindsorCircle_Integration
 * @author    Mark Hodge <mhodge@lyonscg.com>
 * @copyright Copyright (c) 2016 WindsorCircle (www.windsorcircle.com)
 */
namespace WindsorCircle\Integration\Api\Data;

/**
 * Newsletter interface.
 *
 * @api
 */
interface NewsletterInterface
{
    /**
     * Subscriber ID
     */
    const SUBSCRIBER_ID = 'subscriber_id';

    /**
     * Store ID
     */
    const STORE_ID = 'store_id';

    /**
     * Customer ID
     */
    const CUSTOMER_ID = 'customer_id';

    /**
     * Subscriber Email
     */
    const SUBSCRIBER_EMAIL = 'subscriber_email';

    /**
     * Subscriber Status
     */
    const SUBSCRIBER_STATUS = 'subscriber_status';

    /**
     * Get Subscriber ID
     *
     * @return int
     */
    public function getSubscriberId();

    /**
     * Get Store ID
     *
     * @return int
     */
    public function getStoreId();

    /**
     * Get Customer ID
     *
     * @return int
     */
    public function getCustomerId();

    /**
     * Get Subscriber Email
     *
     * @return string
     */
    public function getSubscriberEmail();

    /**
     * Get Subscriber Status
     *
     * @return int
     */
    public function getSubscriberStatus();
}
