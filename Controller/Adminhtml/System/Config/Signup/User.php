<?php
/**
 * Controller for Admin Signup form to create a rest api user
 *
 * @category  WindsorCircle
 * @package   WindsorCircle_Integration
 * @author    Mark Hodge <mhodge@lyonscg.com>
 * @copyright Copyright (c) 2016 WindsorCircle (www.windsorcircle.com)
 */
namespace WindsorCircle\Integration\Controller\Adminhtml\System\Config\Signup;

use Magento\Framework\Controller\Result\JsonFactory;

class User extends \WindsorCircle\Integration\Controller\Adminhtml\System\Config\Signup
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Check whether vat is valid
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $valid = false;
        $message = 'Error';
        $params = [];

        $result = $this->_createApiUser();

        if ($result->getId()) {
            $valid = true;
            $message = 'Success';

            $params = array(
                'consumerkey'       =>  $result->getConsumerKey(),
                'consumersecret'    =>  $result->getConsumerSecret(),
                'token'             =>  $result->getToken(),
                'tokensecret'       =>  $result->getTokenSecret()
            );
        }

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData([
            'valid'     =>  (bool)$valid,
            'message'   =>  $message,
            'params'    =>  $params
        ]);
    }
}
