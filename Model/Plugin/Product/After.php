<?php
/**
 * Plugin for adding new fields for catalogProductRepository
 *
 * @category  WindsorCircle
 * @package   WindsorCircle_Integration
 * @author    Mark Hodge <mhodge@lyonscg.com>
 * @copyright Copyright (c) 2016 WindsorCircle (www.windsorcircle.com)
 */

namespace WindsorCircle\Integration\Model\Plugin\Product;

class After
{
    /**
     * @var \WindsorCircle\Integration\Api\Data\ProductInterfaceFactory
     */
    protected $productInterface;

    /**
     * @var \Magento\Catalog\Api\Data\ProductExtensionFactory
     */
    protected $productExtensionFactory;

    /**
     * @param \WindsorCircle\Integration\Api\Data\ProductInterfaceFactory $productInterface
     * @param \Magento\Catalog\Api\Data\ProductExtensionFactory $productExtensionFactory
     */
    public function __construct(
        \WindsorCircle\Integration\Api\Data\ProductInterfaceFactory $productInterface,
        \Magento\Catalog\Api\Data\ProductExtensionFactory $productExtensionFactory
    ) {
        $this->productInterface = $productInterface;
        $this->productExtensionFactory = $productExtensionFactory;
    }

    /**
     * Plugin for Product getList After
     *
     * @param \Magento\Catalog\Model\ProductRepository $subject
     * @param \Magento\Framework\Api\SearchResults $model
     * @return mixed
     */
    public function afterGetList($subject, $model)
    {
        /** @var \Magento\Catalog\Model\Product $item */
        foreach ($model->getItems() as $item) {
            // Get data from item and remove extension_attributes from it
            $data = $item->getData();
            unset($data[\Magento\Framework\Api\ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY]);

            $product = $this->productInterface->create(array('data' => $data));
            $productExtension = $item->getExtensionAttributes()
                ?: $this->productExtensionFactory->create();
            $productExtension->setWc($product);
            $item->setExtensionAttributes($productExtension);
        }
        return $model;
    }

    /**
     * Plugin for Product get After
     *
     * @param \Magento\Catalog\Model\ProductRepository $subject
     * @param \Magento\Catalog\Model\Product $model
     * @return mixed
     */
    public function afterGet($subject, $model)
    {
        $data = $model->getData();
        unset($data[\Magento\Framework\Api\ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY]);

        $product = $this->productInterface->create(array('data' => $data));
        $productExtension = $model->getExtensionAttributes()
            ?: $this->productExtensionFactory->create();

        $productExtension->setWc($product);
        $model->setExtensionAttributes($productExtension);

        return $model;
    }
}
