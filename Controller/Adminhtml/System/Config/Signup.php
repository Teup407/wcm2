<?php
/**
 * Admin System Config Signup class
 *
 * @category  WindsorCircle
 * @package   WindsorCircle_Integration
 * @author    Mark Hodge <mhodge@lyonscg.com>
 * @copyright Copyright (c) 2016 WindsorCircle (www.windsorcircle.com)
 */
namespace WindsorCircle\Integration\Controller\Adminhtml\System\Config;

use Magento\Integration\Model\Integration as IntegrationModel;

abstract class Signup extends \Magento\Backend\App\Action
{
    /** Integration Name */
    const INTEGRATION_NAME = 'WindsorCircle Integration';

    /**
     * Creates a new Integration or returns the current one
     *
     * @return \Magento\Framework\DataObject
     */
    protected function _createApiUser()
    {
        $apiIntegrationService = $this->_objectManager->get('\Magento\Integration\Api\IntegrationServiceInterface');
        $integration = $apiIntegrationService->findByName(self::INTEGRATION_NAME);

        if ($integration->getId()) {
            $this->_addOauthConsumerData($integration);
            $this->_addOauthTokenData($integration);
            return $integration;
        } else {
            $integration = $apiIntegrationService->create([
                'name'      =>  'WindsorCircle Integration',
                'email'     =>  'mhodge@lyonscg.com',
                'endpoint'  =>  'https://magento2.dev',
                'resource'  =>  [
                    'Magento_Sales::sales',
                    'Magento_Catalog::catalog',
                    'Magento_Catalog::products',
                    'Magento_Catalog::categories'
                ]
            ]);

            $this->_activateIntegration($integration);

            $this->_addOauthConsumerData($integration);
            $this->_addOauthTokenData($integration);
            return $integration;
        }
    }

    /**
     * Add Oauth Consumer Data to IntegrationModel
     *
     * @param IntegrationModel $integration
     */
    protected function _addOauthConsumerData(IntegrationModel $integration)
    {
        if ($integration->getId()) {
            $consumer = $this->_objectManager->get('Magento\Integration\Api\OauthServiceInterface')
                ->loadConsumer($integration->getConsumerId());
            $integration->setData('consumer_key', $consumer->getKey());
            $integration->setData('consumer_secret', $consumer->getSecret());
        }
    }

    /**
     * Add Oath Token and Token Secret to IntegrationModel
     *
     * @param IntegrationModel $integration
     */
    protected function _addOauthTokenData(IntegrationModel $integration)
    {
        if ($integration->getId()) {
            $accessToken = $this->_objectManager->get('Magento\Integration\Api\OauthServiceInterface')
                ->getAccessToken($integration->getConsumerId());
            if ($accessToken) {
                $integration->setData('token', $accessToken->getToken());
                $integration->setData('token_secret', $accessToken->getSecret());
            }
        }
    }

    /**
     * Activate Integration by generating access tokens
     *
     * @param IntegrationModel $integration
     */
    protected function _activateIntegration(IntegrationModel $integration)
    {
        if ($integration->getId()) {
            $oauthService = $this->_objectManager->get('\Magento\Integration\Api\OauthServiceInterface');

            if ($oauthService->createAccessToken($integration->getConsumerId(), true)) {
                $integration->setStatus(IntegrationModel::STATUS_ACTIVE)->save();
            }
        }
    }
}
