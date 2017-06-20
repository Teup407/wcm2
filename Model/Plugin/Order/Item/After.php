<?php
/**
 * File Description here....
 *
 * @category  WindsorCircle
 * @package   WindsorCircle_Integration
 * @author    Mark Hodge <mhodge@lyonscg.com>
 * @copyright Copyright (c) 2016 WindsorCircle (www.windsorcircle.com)
 */

namespace WindsorCircle\Integration\Model\Plugin\Order\Item;

class After
{
    /**
     * @var \WindsorCircle\Integration\Api\Data\OrderItemInterfaceFactory
     */
    protected $orderItemInterface;

    /**
     * @var \Magento\Sales\Api\Data\OrderItemExtensionFactory
     */
    protected $orderItemExtensionFactory;

    /**
     * @param \WindsorCircle\Integration\Api\Data\OrderItemInterfaceFactory $orderItemInterface
     * @param \Magento\Sales\Api\Data\OrderItemExtensionFactory $orderItemExtensionFactory
     */
    public function __construct(
        \WindsorCircle\Integration\Api\Data\OrderItemInterfaceFactory $orderItemInterface,
        \Magento\Sales\Api\Data\OrderItemExtensionFactory $orderItemExtensionFactory
    ) {
        $this->orderItemInterface = $orderItemInterface;
        $this->orderItemExtensionFactory = $orderItemExtensionFactory;
    }

    /**
     * Plugin for Order getList After
     *
     * @param \Magento\Sales\Model\Order\ItemRepository $subject
     * @param \Magento\Sales\Api\Data\OrderItemSearchResultInterface $model
     * @return mixed
     */
    public function afterGetList($subject, $model)
    {
        /** @var \Magento\Sales\Model\Order\Items $item */
        foreach ($model->getItems() as $item) {
            // Get data from item and remove extension_attributes from it
            $data = $item->getData();
            unset($data[\Magento\Framework\Api\ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY]);

            $orderItem = $this->orderItemInterface->create(array('data' => $data));
            $orderItemExtension = $item->getExtensionAttributes()
                ? clone $item->getExtensionAttributes(): $this->orderItemExtensionFactory->create();
            $orderItemExtension->setWc($orderItem);
            $item->setExtensionAttributes($orderItemExtension);
        }
        return $model;
    }

    /**
     * Plugin for Order get After
     *
     * @param \Magento\Sales\Model\Order\ItemRepository $subject
     * @param \Magento\Sales\Model\Order\Item $model
     * @return mixed
     */
    public function afterGet($subject, $model)
    {
        $data = $model->getData();
        unset($data[\Magento\Framework\Api\ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY]);

        $orderItem = $this->orderItemInterface->create(array('data' => $data));
        $orderItemExtension = $model->getExtensionAttributes()
            ?: $this->orderItemExtensionFactory->create();

        $orderItemExtension->setWc($orderItem);
        $model->setExtensionAttributes($orderItemExtension);

        return $model;
    }
}
