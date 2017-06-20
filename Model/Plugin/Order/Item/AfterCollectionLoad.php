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

class AfterCollectionLoad
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
     * After Collection Load Plugin
     *
     * @param \Magento\Sales\Model\ResourceModel\Order\Item\Collection $model
     * @param array $items
     * @return mixed
     */
    public function afterGetItems($model, $items)
    {
        foreach ($items as $item) {
            // Get data from item and remove extension_attributes from it
            $data = $item->getData();
            unset($data[\Magento\Framework\Api\ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY]);

            $orderItem = $this->orderItemInterface->create(array('data' => $data));
            $orderItemExtension = $item->getExtensionAttributes()
                ? clone $item->getExtensionAttributes(): $this->orderItemExtensionFactory->create();
            $orderItemExtension->setWc($orderItem);
            $item->setExtensionAttributes($orderItemExtension);
        }
        return $items;
    }
}
