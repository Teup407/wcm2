<?php
/**
 * Newsletter Model
 *
 * @category  WindsorCircle
 * @package   WindsorCircle_Integration
 * @author    Mark Hodge <mhodge@lyonscg.com>
 * @copyright Copyright (c) 2016 WindsorCircle (www.windsorcircle.com)
 */
namespace WindsorCircle\Integration\Model;

use Magento\Newsletter\Model\Subscriber;
use WindsorCircle\Integration\Api\Data\NewsletterInterface;

/**
 * Newsletter model
 */
class Newsletter extends Subscriber implements NewsletterInterface
{
    /**
     * Get Subscriber ID
     *
     * @return int
     */
    public function getSubscriberId()
    {
        return $this->getData(self::SUBSCRIBER_ID);
    }

    /**
     * Get Store ID
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * Get Customer ID
     *
     * @return int
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * Get Subscriber Email
     *
     * @return string
     */
    public function getSubscriberEmail()
    {
        return $this->getData(self::SUBSCRIBER_EMAIL);
    }

    /**
     * Get Subscriber Status
     *
     * @return int
     */
    public function getSubscriberStatus()
    {
        return $this->getData(self::SUBSCRIBER_STATUS);
    }
}
