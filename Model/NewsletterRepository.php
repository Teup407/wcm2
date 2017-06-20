<?php
/**
 * Newsletter Repository Model
 *
 * @category  WindsorCircle
 * @package   WindsorCircle_Integration
 * @author    Mark Hodge <mhodge@lyonscg.com>
 * @copyright Copyright (c) 2016 WindsorCircle (www.windsorcircle.com)
 */
namespace WindsorCircle\Integration\Model;

use \Magento\Newsletter\Model\ResourceModel\Subscriber as SubscriberResource;
use WindsorCircle\Integration\Api\Data\NewsletterSearchResultInterfaceFactory as SearchResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\InputException;

/**
 * Repository class for @see \WindsorCircle\Integration\Api\Data\NewsletterRepositoryInterface
 */
class NewsletterRepository implements \WindsorCircle\Integration\Api\NewsletterRepositoryInterface
{
    /**
     * @var SearchResultFactory
     */
    protected $searchResultFactory = null;

    /**
     *
     * @var SubscriberResource
     */
    protected $subscriberResource = null;

    /**
     * NewsletterRepository constructor.
     *
     * @param SearchResultFactory $searchResultFactory
     * @param SubscriberResource $resource
     */
    public function __construct(
        SearchResultFactory $searchResultFactory,
        SubscriberResource $resource
    ) {
        $this->searchResultFactory = $searchResultFactory;
        $this->subscriberResource = $resource;
    }

    /**
     * Find entities by criteria
     *
     * @return \WindsorCircle\Integration\Api\Data\NewsletterInterface[]
     */
    public function getList()
    {
        /** @var \WindsorCircle\Integration\Api\Data\NewsletterSearchResultInterface $searchResult */
        $searchResult = $this->searchResultFactory->create();
        return $searchResult;
    }

    /**
     * {@inheritdoc}
     */
    public function get($email, $storeId = null)
    {
        $searchResult = $this->searchResultFactory->create();
        $searchResult->addFieldToFilter('subscriber_email', ['eq' => $email]);
        return $searchResult;
    }
}
