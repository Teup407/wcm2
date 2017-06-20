<?php
/**
 * Block class for Signup Button in admin panel
 *
 * @category  WindsorCircle
 * @package   WindsorCircle_Integration
 * @author    Mark Hodge <mhodge@lyonscg.com>
 * @copyright Copyright (c) 2016 WindsorCircle (www.windsorcircle.com)
 */

namespace WindsorCircle\Integration\Block\Adminhtml\System\Config;

class Signup extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * Signup Button Label
     *
     * @var string
     */
    protected $_signupButtonLabel = 'Sign Up';

    /**
     * Set template to itself
     *
     * @return \WindsorCircle\Integration\Block\Adminhtml\System\Config\Signup
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('system/config/signup.phtml');
        }

        return $this;
    }

    /**
     * Get the button and scripts contents
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $originalData = $element->getOriginalData();
        $buttonLabel = !empty($originalData['button_label']) ? $originalData['button_label'] : $this->_signupButtonLabel;
        $this->addData(
            [
                'button_label'  =>  __($buttonLabel),
                'html_id'       =>  $element->getHtmlId(),
                'ajax_url'      =>  $this->_urlBuilder->getUrl('windsorcircle/system_config_signup/user'),
                'redirect_url'  =>  $this->_scopeConfig->getValue('windsorcircle/redirect/url')
            ]
        );

        return $this->_toHtml();
    }
}
