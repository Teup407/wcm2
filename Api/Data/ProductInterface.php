<?php
/**
 * Product Data Interface
 *
 * @category  WindsorCircle
 * @package   WindsorCircle_Integration
 * @author    Mark Hodge <mhodge@lyonscg.com>
 * @copyright Copyright (c) 2016 WindsorCircle (www.windsorcircle.com)
 */
namespace WindsorCircle\Integration\Api\Data;

/**
 * Product Data Interface
 * 
 * @api
 */
interface ProductInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**
     * Product Parent Sku(s) for product
     *
     * @return mixed
     */
    public function getParentSku();

    /**
     * Product Category Tree for product
     *
     * @return string
     */
    public function getProductType();

    /**
     * Product Url Link
     *
     * @return string|null
     */
    public function getProductLink();

    /**
     * Product Image Url
     *
     * @return string|null
     */
    public function getImageLink();

    /**
     * Get all store ids where product is presented
     *
     * @return mixed
     */
    public function getStoreIds();

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Magento\Catalog\Api\Data\ProductExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Magento\Catalog\Api\Data\ProductExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\Magento\Catalog\Api\Data\ProductExtensionInterface $extensionAttributes);
}
