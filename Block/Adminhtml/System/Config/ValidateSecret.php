<?php

namespace Simpl\Checkout\Block\Adminhtml\System\Config;

class ValidateSecret extends \Magento\Config\Block\System\Config\Form\Field
{
    protected $_template = 'system/config/validatesecret.phtml';

    protected $url;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        $this->url = $context->getUrlBuilder();
        parent::__construct($context, $data);
    }

    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->_toHtml();
    }

    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'validatesecret',
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
    public function getValidateUrl() {
        return $this->url->getUrl('simplcheckout/index/validatesecret');
    }
}
