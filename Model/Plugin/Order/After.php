<?php
/**
 * Plugin for adding new fields for salesOrderRepository
 *
 * @category  WindsorCircle
 * @package   WindsorCircle_Integration
 * @author    Mark Hodge <mhodge@lyonscg.com>
 * @copyright Copyright (c) 2016 WindsorCircle (www.windsorcircle.com)
 */

namespace WindsorCircle\Integration\Model\Plugin\Order;

class After
{
    /**
     * @var \WindsorCircle\Integration\Api\Data\OrderInterfaceFactory
     */
    protected $orderInterface;

    /**
     * @var \Magento\Sales\Api\Data\OrderExtensionFactory
     */
    protected $orderExtensionFactory;

    /**
     * @param \WindsorCircle\Integration\Api\Data\OrderInterfaceFactory $orderInterface
     * @param \Magento\Sales\Api\Data\OrderExtensionFactory $orderExtensionFactory
     */
    public function __construct(
        \WindsorCircle\Integration\Api\Data\OrderInterfaceFactory $orderInterface,
        \Magento\Sales\Api\Data\OrderExtensionFactory $orderExtensionFactory
    ) {
        $this->orderInterface = $orderInterface;
        $this->orderExtensionFactory = $orderExtensionFactory;
    }

    /**
     * Plugin for Order getList After
     *
     * @param \Magento\Sales\Model\OrderRepository $subject
     * @param \Magento\Sales\Api\Data\OrderSearchResultInterface $model
     * @return mixed
     */
    public function afterGetList($subject, $model)
    {
        /** @var \Magento\Sales\Model\Order $item */
        foreach ($model->getItems() as $item) {
            // Get data from item and remove extension_attributes from it
            $data = $item->getData();
            unset($data[\Magento\Framework\Api\ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY]);

            $order = $this->orderInterface->create(array('data' => $data));
            $orderExtension = $item->getExtensionAttributes()
                ? clone $item->getExtensionAttributes() : $this->orderExtensionFactory->create();
            $orderExtension->setWc($order);
            $item->setExtensionAttributes($orderExtension);
        }
        return $model;
    }

    /**
     * Plugin for Order get After
     *
     * @param \Magento\Sales\Model\OrderRepository $subject
     * @param \Magento\Sales\Model\Order $model
     * @return mixed
     */
    public function afterGet($subject, $model)
    {
        $data = $model->getData();
        unset($data[\Magento\Framework\Api\ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY]);

        $order = $this->orderInterface->create(array('data' => $data));
        $orderExtension = $model->getExtensionAttributes()
            ?: $this->orderExtensionFactory->create();

        $orderExtension->setWc($order);
        $model->setExtensionAttributes($orderExtension);

        return $model;
    }
}
