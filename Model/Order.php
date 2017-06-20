<?php
/**
 * Order Model
 *
 * @category  WindsorCircle
 * @package   WindsorCircle_Integration
 * @author    Mark Hodge <mhodge@lyonscg.com>
 * @copyright Copyright (c) 2016 WindsorCircle (www.windsorcircle.com)
 */
namespace WindsorCircle\Integration\Model;

use \Magento\Sales\Model\AbstractModel;
use \WindsorCircle\Integration\Api\Data\OrderInterface;
use \Magento\Framework\Api\AttributeValueFactory;

/**
 * Order model
 *
 * @method string getCreatedAt()
 * @method string getCustomerFirstname()
 * @method string getCustomerLastname()
 *
 */
class Order extends AbstractModel implements OrderInterface
{
    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $customAttributeFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory $orderItemCollectionFactory,
        \Magento\Sales\Model\ResourceModel\Order\Address\CollectionFactory $addressCollectionFactory,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getOrderDate()
    {
        $date = new \DateTime($this->getCreatedAt());
        return $date->format('Ymd');
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getOrderTime()
    {
        $date = new \DateTime($this->getCreatedAt());
        return $date->format('H:i:s');
    }

    /**
     * Gets the order cancel state
     *
     * @return bool Order canceled
     */
    public function getCanceled()
    {
        // TODO: Get value from admin for checking if order is in a canceled state
    }

    /**
     * Get the product total for the order
     *
     * @return float|null Product Total for Order - Subtotal minus DiscountAmount.
     */
    public function getProductTotal()
    {
        return $this->getData('base_subtotal') - $this->getData('discount_amount');
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getCustName()
    {
        return $this->getCustomerFirstname() . ' ' . $this->getCustomerLastname();
    }

    /**
     * {@inheritdoc}
     *
     * return string
     */
    public function getCustomerGroupName()
    {
        return $this->getData(self::CUSTOMER_GROUP_CODE);
    }

    /**
     * Returns shipping_address_id
     *
     * @return int
     */
    public function getShippingAddressId()
    {
        return $this->getData(OrderInterface::SHIPPING_ADDRESS_ID);
    }
}
