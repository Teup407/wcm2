<?php
/**
 * Catalog Product Extended Model
 *
 * @category  WindsorCircle
 * @package   WindsorCircle_Integration
 * @author    Mark Hodge <mhodge@lyonscg.com>
 * @copyright Copyright (c) 2016 WindsorCircle (www.windsorcircle.com)
 */
namespace WindsorCircle\Integration\Model;

use \WindsorCircle\Integration\Api\Data\ProductInterface;
use \Magento\Catalog\Model\AbstractModel;
use \Magento\Catalog\Model\Product\Type;
use \Magento\Catalog\Api\CategoryRepositoryInterface;
use \Magento\Framework\Api\AttributeValueFactory;
use \Magento\Catalog\Helper\ImageFactory as HelperFactory;
use \Magento\Catalog\Helper\Product as ProductHelper;

/**
 * Catalog Product Extended Model
 *
 * @method bool hasWebsiteIds()
 * @method array setWebsiteIds($ids)
 * @method bool hasStoreIds()
 * @method array setStoreIds($storeIds)
 */
class Product extends AbstractModel implements ProductInterface
{
    /**
     * @var HelperFactory
     */
    protected $_helperFactory;

    /**
     * @var ProductHelper
     */
    protected $_productHelper;

    /**
     * @var \Magento\Catalog\Model\Product\Type
     */
    protected $_productType;

    /**
     * Product Type Instances cache
     *
     * @var array
     */
    protected $_productTypes = [];

    /**
     * @var CategoryRepositoryInterface
     */
    protected $_categoryRepository;

    /**
     * @var \Magento\Catalog\Api\Data\ProductInterface
     */
    protected $_item;

    /**
     * Instance of category collection.
     *
     * @var \Magento\Catalog\Model\ResourceModel\Category\Collection
     */
    protected $categoryCollection;

    /**
     * @var int
     */
    protected $_productIdCached;

    /**
     * Product constructor.
     *
     * @param HelperFactory $helperFactory
     * @param ProductHelper $productHelper
     * @param \Magento\Catalog\Model\Product\Type $productType
     * @param CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $customAttributeFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\ResourceModel\Product $resource
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        HelperFactory $helperFactory,
        ProductHelper $productHelper,
        \Magento\Catalog\Model\Product\Type $productType,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Product $resource,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $resourceCollection,
        array $data = []
    ) {
        $this->_helperFactory = $helperFactory;
        $this->_productHelper = $productHelper;
        $this->_productType = $productType;
        $this->_categoryRepository = $categoryRepository;
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $storeManager,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function getParentSku()
    {
        $parentIds = [];
        foreach ($this->getProductTypeInstances() as $typeInstance) {
            /* @var $typeInstance AbstractType */
            $parentIds = array_merge($parentIds, $typeInstance->getParentIdsByChild($this->getId()));
        }

        if (!$parentIds) {
            return null;
        }

        $skus = [];
        $productSkus = $this->_resource->getProductsSku($parentIds);
        foreach ($productSkus as $productSku) {
            $skus[] = $productSku['sku'];
        }
        return $skus;
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function getProductLink()
    {
        return $this->_productHelper->getProductUrl($this->getId());
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getImageLink()
    {
        /** @var \Magento\Catalog\Helper\ImageFactory $helper */
        $helper = $this->_helperFactory->create()->init($this, 'windsorcircle_small_image');
        return $helper->getUrl();
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getProductType()
    {
        $categoryList = [];
        $categoryCollection = $this->getCategoryCollection();
        foreach ($categoryCollection as $categoryObject) {
            $categoryIds = explode('/', $categoryObject->getPath());
            // Remove Root Category ID (1)
            $categoryIds = array_diff($categoryIds, [1]);
            $categoryPath = [];
            foreach ($categoryIds as $categoryId) {
                $category = $this->_categoryRepository->get($categoryId);
                if ($category->getLevel() > 1) {
                    $categoryPath[] = $category->getName();
                }
            }
            $categoryList[] = implode(' > ', $categoryPath);
        }
        return implode(' | ', $categoryList);
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getStoreIds()
    {
        if (!$this->hasStoreIds()) {
            $storeIds = [];
            if ($websiteIds = $this->getWebsiteIds()) {
                foreach ($websiteIds as $websiteId) {
                    $websiteStores = $this->_storeManager->getWebsite($websiteId)->getStoreIds();
                    $storeIds = array_merge($storeIds, $websiteStores);
                }
            }
            $this->setStoreIds($storeIds);
        }
        return $this->getData('store_ids');
    }

    /**
     * {@inheritdoc}
     *
     * @return \Magento\Catalog\Api\Data\ProductExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     *
     * @param \Magento\Catalog\Api\Data\ProductExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\Magento\Catalog\Api\Data\ProductExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Retrieve product categories
     *
     * @return \Magento\Framework\Data\Collection
     */
    protected function getCategoryCollection()
    {
        if ($this->categoryCollection === null || $this->getId() != $this->_productIdCached) {
            $categoryCollection = $this->_getResource()->getCategoryCollection($this);
            $this->setCategoryCollection($categoryCollection);
            $this->_productIdCached = $this->getId();
        }
        return $this->categoryCollection;
    }

    /**
     * Retrieve product websites identifiers
     *
     * @return array
     */
    protected function getWebsiteIds()
    {
        if (!$this->hasWebsiteIds()) {
            $ids = $this->_getResource()->getWebsiteIds($this->getId());
            $this->setWebsiteIds($ids);
        }
        return $this->getData('website_ids');
    }

    /**
     * Retrieve Product Type Instances
     * as key - type code, value - instance model
     *
     * @return array
     */
    protected function getProductTypeInstances()
    {
        if (empty($this->_productTypes)) {
            $productEmulator = new \Magento\Framework\DataObject();
            foreach (array_keys($this->_productType->getTypes()) as $typeId) {
                $productEmulator->setTypeId($typeId);
                $this->_productTypes[$typeId] = $this->_productType->factory($productEmulator);
            }
        }
        return $this->_productTypes;
    }

    /**
     * Set product categories.
     *
     * @param \Magento\Framework\Data\Collection $categoryCollection
     * @return $this
     */
    protected function setCategoryCollection(\Magento\Framework\Data\Collection $categoryCollection)
    {
        $this->categoryCollection = $categoryCollection;
        return $this;
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Magento\Framework\Api\ExtensionAttributesInterface
     */
    protected function _getExtensionAttributes()
    {
        return $this->getData(self::EXTENSION_ATTRIBUTES_KEY);
    }

    /**
     * Set an extension attributes object.
     *
     * @param \Magento\Framework\Api\ExtensionAttributesInterface $extensionAttributes
     * @return $this
     */
    protected function _setExtensionAttributes(\Magento\Framework\Api\ExtensionAttributesInterface $extensionAttributes)
    {
        $this->_data[self::EXTENSION_ATTRIBUTES_KEY] = $extensionAttributes;
        return $this;
    }
}
