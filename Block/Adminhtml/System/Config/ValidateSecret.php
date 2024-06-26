<?php

namespace Simpl\Checkout\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Simpl\Checkout\Helper\Config;

class ValidateSecret extends Field
{

    protected $_template = 'system/config/validatesecret.phtml';

    protected $url;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param Context $context
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $config,
        array $data = []
    ) {
        $this->url = $context->getUrlBuilder();
        $this->config = $config;
        parent::__construct($context, $data);
    }

    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'btn-validate-secret',
                'label' => __('Validate Credentials'),
                'class' => 'primary'
            ]
        );

        return $button->toHtml();
    }

    /**
     * Function to get URL
     * @return string
     */
    public function getValidateUrl()
    {
        return $this->url->getUrl('simplcheckout/index/validatesecret');
    }

    /**
     * To get the Simpl payment mode live/test
     * @return string
     */
    public function getSecretId()
    {
        if ($this->config->getIntegrationMode() == 'live') {
            return 'payment_other_simplcheckout_live_secret';
        }
        return 'payment_other_simplcheckout_test_secret';
    }
}
